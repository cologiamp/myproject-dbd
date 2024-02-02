<?php

namespace App\Services;


use App\Concerns\FormatsCurrency;
use App\Models\Client;
use App\Repositories\AssetRepository;
use App\Repositories\ClientRepository;
use App\Repositories\DependentRepository;
use App\Repositories\HealthRepository;
use App\Repositories\EmploymentDetailRepository;
use App\Repositories\InvestmentRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Throwable;

class FactFindSectionDataService
{
    use FormatsCurrency;
    protected ClientRepository $cr;
    protected DependentRepository $dependentRepository;
    protected HealthRepository $healthRepository;
    protected EmploymentDetailRepository $employmentDetailRepository;

    protected AssetRepository $assetRepository;

    public function __construct(
        ClientRepository $clientRepository,
        DependentRepository $dependentRepository,
        HealthRepository $healthRepository,
        EmploymentDetailRepository $employmentDetailRepository,
        AssetRepository $assetRepository
    ) {
        $this->cr = $clientRepository;
        $this->dependentRepository = $dependentRepository;
        $this->healthRepository = $healthRepository;
        $this->employmentDetailRepository = $employmentDetailRepository;
        $this->assetRepository = $assetRepository;
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


    private function _31(array $validatedData): void
    {
        $client= $this->cr->getClient();
        try{
            if (array_key_exists('fixed_assets', $validatedData)) {
                $fixed_assets = collect($validatedData['fixed_assets'])->map(function ($asset) use ($client) {
                    if (array_key_exists('purchased_at',$asset)){
                        $asset['start_at'] = Carbon::parse($asset['purchased_at']);
                        unset($asset['purchased_at']);
                    }
                    $asset['category'] = array_flip(config('enums.assets.categories'))['fixed_assets'];
                    $asset['type'] = $asset['asset_type'];
                    if (array_key_exists('original_value',$asset) && $asset['original_value'] != null){
                        $asset['original_value'] = $this->currencyStringToInt($asset['original_value']);
                    }
                    if (array_key_exists('retained_value',$asset) && $asset['retained_value'] != null){
                        $asset['retained_value'] = $this->currencyStringToInt($asset['retained_value']);
                    }
                    if (array_key_exists('current_value',$asset) && $asset['current_value'] != null){
                        $asset['current_value'] = $this->currencyStringToInt($asset['current_value']);
                    }
                    unset($asset['asset_type']);//remove
                    return $asset;
                });
                $validatedData['assets'] = $fixed_assets->toArray();

            }
            $this->assetRepository->setClient($client);
            $this->assetRepository->createOrUpdateAssets($validatedData);

        }
        catch(Throwable $e){
            Log::warning($e);
        }
    }
    private function _32(array $validatedData): void
    {
        $client = $this->cr->getClient();
        try{
            if (array_key_exists('saving_assets', $validatedData)) {
                $saving_assets = collect($validatedData['saving_assets'])->map(function ($asset) use ($client) {
                    if (array_key_exists('start_date',$asset)){
                        if( $asset['start_date'] != null)
                        {
                            $asset['start_at'] = Carbon::parse($asset['start_date']);
                        }
                        else
                        {
                            $asset['start_at'] = null;
                        }
                        unset($asset['start_date']);
                    }
                    if (array_key_exists('end_date',$asset)){
                        if( $asset['end_date'] != null)
                        {
                            $asset['end_at'] = Carbon::parse($asset['end_date']);
                        }
                        else
                        {
                            $asset['end_at'] = null;
                        }
                        unset($asset['end_date']);
                    }
                    if (array_key_exists('name',$asset)){
                        $asset['product_name'] = $asset['name'];
                        unset($asset['name']);
                    }
                    $asset['category'] = array_flip(config('enums.assets.categories'))['savings'];
                    $asset['type'] = array_flip(config('enums.assets.types'))['Cash'];
                    unset($asset['asset_type']);//remove
                    if (array_key_exists('current_balance',$asset)){
                        if( $asset['current_balance'] != null)
                        {
                            $asset['current_value'] = $this->currencyStringToInt($asset['current_balance']);
                        }
                        unset($asset['current_balance']);
                    }
                    if (array_key_exists('retained_value',$asset) && $asset['retained_value'] != null){
                        $asset['retained_value'] = $this->currencyStringToInt($asset['retained_value']);
                    }

                    return $asset;
                });
                $validatedData['assets'] = $saving_assets->toArray();
            }
            $this->assetRepository->setClient($client);
            $this->assetRepository->createOrUpdateAssets($validatedData);

        }
        catch(Throwable $e){
            Log::warning($e);
        }
    }

    private function _33(array $validatedData): void
    {


        $validatedData = collect($validatedData['investments'])->map(function ($item){

            if(array_key_exists('owner',$item) && $item['owner'] != null)
            {
                $item['client_id'] = Client::where('io_id',$item['owner'])->first()->id;
            }
            else{
                $item['client_id'] = $this->cr->getClient()->id;
            }

            unset($item['owner']);
            if(array_key_exists('account_type',$item)){
                $item['contract_type'] = $item['account_type'];
                unset($item['account_type']);
            }
            if (array_key_exists('start_date',$item)  && $item['start_date'] != null){
                $item['start_date'] = Carbon::parse($item['start_date']);
            }
            else{
                unset($item['start_date']);
            }
            if (array_key_exists('maturity_date',$item) && $item['maturity_date'] != null){
                $item['maturity_date'] = Carbon::parse($item['maturity_date']);
            }
            else{
                unset($item['maturity_date']);
            }
            if (array_key_exists('valuation_at',$item) && $item['valuation_at'] != null){
                $item['valuation_at'] = Carbon::parse($item['valuation_at']);
            }
            else{
                unset($item['valuation_at']);
            }

            if (array_key_exists('current_value',$item) && $item['current_value'] != null){
                $item['current_value'] = $this->currencyStringToInt($item['current_value']);
            }
            if (array_key_exists('regular_contribution',$item) && $item['regular_contribution'] != null){
                $item['regular_contribution'] = $this->currencyStringToInt($item['regular_contribution']);
            }
            if (array_key_exists('retained_value',$item) && $item['retained_value'] != null){
                $item['retained_value'] = $this->currencyStringToInt($item['retained_value']);
            }

            return $item;
        });

        $ir = App::make(InvestmentRepository::class);
        try{
            $ir->createOrUpdateInvestments($validatedData);
        }
        catch(Throwable $e){
            Log::warning($e);
            dd($e);
        }
    }
}
