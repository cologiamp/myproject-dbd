<?php
namespace App\Services;


use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Repositories\DependentRepository;
use App\Repositories\HealthRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

use Throwable;

class FactFindSectionDataService
{
    protected ClientRepository $cr;
    protected DependentRepository $dependentRepository;
    protected HealthRepository $healthRepository;
    
    public function __construct(
        ClientRepository $clientRepository,
        DependentRepository $dependentRepository, 
        HealthRepository $healthRepository)
    {
        $this->cr = $clientRepository;
        $this->dependentRepository = $dependentRepository;
        $this->healthRepository = $healthRepository;
    }
    //get the data for a single section of a factfind from a single client
    public static function get($client,$step,$section):array
    {
        return [
            'enums' => $client->loadEnumsForStep($step,$section),
            'model' => $client->presenter()->formatForStep( $step,$section), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
            'submit_url' => '/client/' . $client->io_id . '/fact-find/' . $step .'/' . $section //here we hydrate the autosave URL
        ];
    }

    public function validate(int $step,int $section,Request $request)
    {
        return Validator::make($request->all(),config('navigation_structures.factfind.'.$step.'.sections.'.$section.'.rules'))->validated();
    }

    /**
     * @param $client
     * @param $section
     * @param $step
     * @param array $validatedData
     * @return true
     */
    public function store(Client $client,int $step, int $section, array $validatedData): true
    {
                $this->cr->setClient($client);
        $this->{"_".$step.$section}($validatedData);
        return true;
    }


//FactFind:// Need to write a function named _{section}{step} for every form
    /**
     * Section: 1
     * Step: 1
     * @param array $validatedData
     * @return void
     */
    private function _11(array $validatedData):void
    {
        //define any explicit mutators that are not handled
        if(array_key_exists('date_of_birth',$validatedData))
        {
            $validatedData['date_of_birth'] = Carbon::parse($validatedData['date_of_birth']);
        }
        if(array_key_exists('country_of_domicile',$validatedData)  && $validatedData['country_of_domicile'] != null)
        {
            $validatedData['country_of_domicile'] = array_flip(config('enums.client.iso_2_int'))[$validatedData['country_of_domicile']];
        }
        if(array_key_exists('country_of_residence',$validatedData) && $validatedData['country_of_residence'] != null)
        {
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
    private function _12(array $validatedData):void
    {

        if(array_key_exists('is_in_good_health',$validatedData))
        {
            if($validatedData['is_in_good_health'] == true){
                $validatedData['health_details'] = '';
            }
        }

        if(array_key_exists('has_life_expectancy_concerns',$validatedData))
        {
            if($validatedData['has_life_expectancy_concerns'] == false){
                $validatedData['health_details'] = '';
            }
        }

        try {
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
    private function _13(array $validatedData):void
    {
        try {
            if(array_key_exists('addresses',$validatedData)){
                collect($validatedData['addresses'])->each(function ($item){
                    if($item['date_from'])
                    {
                        $item['date_from'] = Carbon::parse($item['date_from']);
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
     * Step: 3
     * @param array $validatedData
     * @return void
     */
    private function _14(array $validatedData):void
    {
        try {
            if(array_key_exists('dependents',$validatedData)){
                $dependents = collect($validatedData['dependents'])->map(function ($dependent){
                    if($dependent['born_at'])
                    {
                        $dependent['born_at'] = Carbon::parse($dependent['born_at']);
                    }
                    return $dependent;
                });

                $validatedData['dependents'] = $dependents->toArray();
            }

            $this->dependentRepository->createOrUpdateDependentDetails($validatedData);

        } catch (Throwable $e) {
            Log::warning($e);
        }
    }
}
