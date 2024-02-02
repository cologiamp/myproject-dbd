<?php

namespace App\Services;


use App\Concerns\FormatsCurrency;
use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Repositories\DependentRepository;
use App\Repositories\HealthRepository;
use App\Repositories\EmploymentDetailRepository;
use App\Repositories\IncomeRepository;
use App\Repositories\ExpenditureRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Throwable;

class FactFindSectionDataService
{
    use FormatsCurrency;
    protected ClientRepository $cr;
    protected DependentRepository $dependentRepository;
    protected HealthRepository $healthRepository;
    protected EmploymentDetailRepository $employmentDetailRepository;
    protected IncomeRepository $incomeRepository;
    protected ExpenditureRepository $expenditureRepository;

    public function __construct(
        ClientRepository $clientRepository,
        DependentRepository $dependentRepository,
        HealthRepository $healthRepository,
        EmploymentDetailRepository $employmentDetailRepository,
        IncomeRepository $incomeRepository,
        ExpenditureRepository $expenditureRepository
    ) {
        $this->cr = $clientRepository;
        $this->dependentRepository = $dependentRepository;
        $this->healthRepository = $healthRepository;
        $this->employmentDetailRepository = $employmentDetailRepository;
        $this->incomeRepository = $incomeRepository;
        $this->expenditureRepository = $expenditureRepository;
    }
    //get the data for a single section of a factfind from a single client
    public static function get($client, $step, $section): array
    {
        return [
            'enums' => $client->loadEnumsForStep($step, $section),
            'model' => $client->presenter()->formatForStep($step, $section), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
            'submit_url' => '/client/' . $client->io_id . '/fact-find/' . $step . '/' . $section //here we hydrate the autosave URL
        ];
    }

    public function validate(int $step, int $section, Request $request)
    {
        return Validator::make($request->all(), config('navigation_structures.factfind.' . $step . '.sections.' . $section . '.rules'))->validate();
    }

    public function validated(int $step, int $section, Request $request)
    {
        return Validator::make($request->all(), config('navigation_structures.factfind.' . $step . '.sections.' . $section . '.rules'))->validated();
    }

    /**
     * @param $client
     * @param $section
     * @param $step
     * @param array $validatedData
     * @return true
     */
    public function store(Client $client, int $step, int $section, array $validatedData): true
    {
        $this->cr->setClient($client);
        $this->{"_" . $step . $section}($validatedData);
        return true;
    }


    //FactFind:// Need to write a function named _{section}{step} for every form
    /**
     * Section: 1
     * Step: 1
     * @param array $validatedData
     * @return void
     */
    private function _11(array $validatedData): void
    {
        //define any explicit mutators that are not handled
        if (array_key_exists('date_of_birth', $validatedData)) {
            $validatedData['date_of_birth'] = Carbon::parse($validatedData['date_of_birth']);
        }
        if (array_key_exists('country_of_domicile', $validatedData)  && $validatedData['country_of_domicile'] != null) {
            $validatedData['country_of_domicile'] = array_flip(config('enums.client.iso_2_int'))[$validatedData['country_of_domicile']];
        }
        if (array_key_exists('country_of_residence', $validatedData) && $validatedData['country_of_residence'] != null) {
            $validatedData['country_of_residence'] = array_flip(config('enums.client.iso_2_int'))[$validatedData['country_of_residence']];
        }
        //This example only has data from one table. This would be different if not the case. May need multiple repositories.
        $this->cr->updateFromValidated($validatedData);
    }

    /**
     * Section: 1
     * Step: 2
     * @param array $validatedData
     * @return void
     */
    private function _12(array $validatedData): void
    {
        if (array_key_exists('is_in_good_health', $validatedData)) {
            if ($validatedData['is_in_good_health'] == true) {
                $validatedData['health_details'] = '';
            }
        }

        if (array_key_exists('has_life_expectancy_concerns', $validatedData)) {
            if ($validatedData['has_life_expectancy_concerns'] == false) {
                $validatedData['life_expectancy_details'] = '';
            }
        }

        try {
            $this->healthRepository->setClient($this->cr->getClient());
            $this->healthRepository->createOrUpdateHealthDetails($validatedData);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Section: 1
     * Step: 3
     * @param array $validatedData
     * @return void
     */
    private function _13(array $validatedData): void
    {
        try {
            if (array_key_exists('addresses', $validatedData)) {

                //addresses can belong to multiple clients, so we don't destroy them, just detach
                $this->cr->getClient()->addresses()->detach($this->cr->getClient()->addresses->whereNotIn('id',
                    collect($validatedData['addresses'])->pluck('address_id')->filter())->pluck('id')
                );


                collect($validatedData['addresses'])->each(function ($item) {
                    if ($item['date_from'] && $item['date_from'] != null) {
                        $item['date_from'] = Carbon::parse($item['date_from']);
                    }
                    if($item['country'] && $item['country'] != null)
                    {
                        $item['country'] = (int)$item['country'];
                    }
                    $this->cr->createOrUpdateAddress($item);
                });
            }

            $contactDetails = array(
                'phone_number' => $validatedData['phone_number'],
                'email_address' => $validatedData['email_address']
            );

            $this->cr->updateFromValidated($contactDetails);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Section: 1
     * Step: 4
     * @param array $validatedData
     * @return void
     */
    private function _14(array $validatedData): void
    {
        try {
            if (array_key_exists('dependents', $validatedData)) {
                $dependents = collect($validatedData['dependents'])->map(function ($dependent) {
                    if ($dependent['born_at']) {
                        $dependent['born_at'] = Carbon::parse($dependent['born_at']);
                    }
                    if($dependent['is_living_with_clients'] == null)
                    {
                        $dependent['is_living_with_clients'] = true;
                    }
                    return $dependent;
                });

                $validatedData['dependents'] = $dependents->toArray();
            }
            $this->dependentRepository->setClient($this->cr->getClient());
            $this->dependentRepository->createOrUpdateDependentDetails($validatedData);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Section: 1
     * Step: 5
     * @param array $validatedData
     * @return void
     */
    private function _15(array $validatedData): void
    {
        try {
            if (array_key_exists('employment_details', $validatedData)) {
                $employment_details = collect($validatedData['employment_details'])->map(function ($employment) {
                    if ($employment['start_at']) {
                        $employment['start_at'] = Carbon::parse($employment['start_at']);
                    }
                    if ($employment['end_at']) {
                        $employment['end_at'] = Carbon::parse($employment['end_at']);
                    }

                    return $employment;
                });

                $validatedData['employment_details'] = $employment_details->toArray();
            }

            $this->employmentDetailRepository->setClient($this->cr->getClient());
            $this->employmentDetailRepository->createOrUpdateEmploymentDetails($validatedData);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Section: 2
     * Step: 1
     * @param array $validatedData
     * @return void
     */
    private function _21(array $validatedData): void
    {
        try {
            if (array_key_exists('incomes', $validatedData)) {
                $incomes = collect($validatedData['incomes'])->map(function ($income) {
                    if ($income['ends_at'] && $income['ends_at'] != null) {
                        $income['ends_at'] = Carbon::parse($income['ends_at']);
                    }
                    if ($income['gross_amount'] && $income['gross_amount'] != null) {
                        $income['gross_amount'] = $this->currencyStringToInt($income['gross_amount']);
                    }
                    if ($income['net_amount'] && $income['net_amount'] != null) {
                        $income['net_amount'] = $this->currencyStringToInt($income['net_amount']);
                    }
                    if ($income['expenses'] && $income['expenses'] != null) {
                        $income['expenses'] = $this->currencyStringToInt($income['expenses']);
                    }

                    return $income;
                });

                $validatedData['incomes'] = $incomes->toArray();
            }

            $this->incomeRepository->setClient($this->cr->getClient());
            $this->incomeRepository->createOrUpdateIncomeDetails($validatedData);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Section: 2
     * Step: 2
     * @param array $validatedData
     * @return void
     */
    private function _22(array $validatedData): void
    {
        $this->parseAndUpdateExpenditure($validatedData);
    }

    /**
     * Section: 2
     * Step: 3
     * @param array $validatedData
     * @return void
     */
    private function _23(array $validatedData): void
    {
        $this->parseAndUpdateExpenditure($validatedData);
    }

    /**
     * Section: 2
     * Step: 4
     * @param array $validatedData
     * @return void
     */
    private function _24(array $validatedData): void
    {
        $this->parseAndUpdateExpenditure($validatedData);
    }

    /**
     * Section: 2
     * Step: 5
     * @param array $validatedData
     * @return void
     */
    private function _25(array $validatedData): void
    {
        $this->parseAndUpdateExpenditure($validatedData);
    }

    /**
     * Parse and update expenditure data
     * @param array $validatedData
     * @return void
     */
    private function parseAndUpdateExpenditure(array $validatedData): void
    {
        try {
            if (array_key_exists('expenditures', $validatedData)) {
                $expenditures = collect($validatedData['expenditures'])->map(function ($expenditure) {
                    if ($expenditure['amount'] && $expenditure['amount'] != null) {
                        $expenditure['amount'] = $this->currencyStringToInt($expenditure['amount']);
                    }
                    if ($expenditure['starts_at'] && $expenditure['starts_at'] != null) {
                        $expenditure['starts_at'] = Carbon::parse($expenditure['starts_at']);
                    }
                    if ($expenditure['ends_at'] && $expenditure['ends_at'] != null) {
                        $expenditure['ends_at'] = Carbon::parse($expenditure['ends_at']);
                    }

                    return $expenditure;
                });

                $validatedData['expenditures'] = $expenditures->toArray();
            }
        
            $this->expenditureRepository->setClient($this->cr->getClient());
            $this->expenditureRepository->createOrUpdateExpenditureDetails($validatedData);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }
}
