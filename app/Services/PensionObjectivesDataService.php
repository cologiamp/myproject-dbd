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
use App\Repositories\PensionRepository;
use App\Repositories\LiabilityRepository;
use App\Repositories\IncomeRepository;
use App\Repositories\ExpenditureRepository;
use App\Repositories\RetirementRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Throwable;

class PensionObjectivesDataService
{
    use FormatsCurrency;
    protected RetirementRepository $retirementRepository;
    public function __construct(RetirementRepository $retirementRepository)
    {
        $this->retirementRepository = $retirementRepository;
    }

    //get the data for a single section of a factfind from a single client
    public static function get($retirement, $step): array
    {
        return [
            'enums' => $retirement->loadEnumsForPOStep($step),
            'model' => $retirement->presenter()->formatForPOStep($step), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
            'submit_url' => '/api/client/' . $retirement->client->io_id . '/pension-objectives/' . $step  //here we hydrate the autosave URL
        ];
    }


    public function validate(int $step, Request $request): array
    {
        return Validator::make($request->all(), config('navigation_structures.pensionobjectives.' . $step  . '.rules'))->validate();
    }

    public function validated(int $step, Request $request): array
    {
        return Validator::make($request->all(), config('navigation_structures.pensionobjectives.' . $step .  '.rules'))->validated();
    }

    /**
     * @param $client
     * @param $section
     * @param $step
     * @param array $validatedData
     * @return true
     */
    public function store(Client $client, int $step, array $validatedData): true
    {
        $this->retirementRepository->setRetirement($client->retirement);
        $this->{"saveTab" . $step}($validatedData);
        return true;
    }

    //These methods will PARSE the data before passing it to the REPOSITORY to save in the database
    private function saveTab1(array $validatedData):void
    {

        if(array_key_exists('intended_retirement',$validatedData))
        {
            if($validatedData['intended_retirement'] != null)
            {
                $validatedData['intended_retirement_at'] = Carbon::now()->addYears($validatedData['intended_retirement'] + 1)->subDay(); //Saving +1 because of the rounding down that getDiffInYears does
            }
            unset($validatedData['intended_retirement']);

        }

        if(array_key_exists('intended_benefits_drawn',$validatedData))
        {
            if($validatedData['intended_benefits_drawn'] != null)
            {
                $validatedData['intended_benefits_drawn_at'] = Carbon::now()->addYears($validatedData['intended_benefits_drawn'] + 1)->subDay(); //Saving +1 because of the rounding down that getDiffInYears does
            }
            unset($validatedData['intended_benefits_drawn']);
        }

        if(array_key_exists('lifetime_allowance_protection',$validatedData))
        {
            if($validatedData['lifetime_allowance_protection'] != null)
            {
                $validatedData['lifetime_allowance_protection'] = json_encode($validatedData['lifetime_allowance_protection']);
            }
        }

        try{
            $this->retirementRepository->update($validatedData);
        }
        catch(Throwable $e){
            Log::warning($e);
            dd($e);
        }

    }

    private function saveTab2(array $validatedData):void
    {
        try{
            $this->retirementRepository->update($validatedData);
        }
        catch(Throwable $e){
            Log::warning($e);
        }
    }

    private function saveTab3(array $validatedData):void
    {
        if(array_key_exists('tax_free_lump_sum_value', $validatedData))
        {
            if($validatedData['tax_free_lump_sum_value'] != null)
            {
                $validatedData['tax_free_lump_sum_value'] = $this->currencyStringToInt($validatedData['tax_free_lump_sum_value']);
            }
        }

        try{
            $this->retirementRepository->update($validatedData);
        }
        catch(Throwable $e){
            Log::warning($e);
        }
    }


}
