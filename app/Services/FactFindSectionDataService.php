<?php
namespace App\Services;


use App\Models\Client;
use App\Repositories\ClientRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FactFindSectionDataService
{
    protected ClientRepository $cr;
    public function __construct(ClientRepository $clientRepository)
    {
        $this->cr = $clientRepository;
    }
    //get the data for a single section of a factfind from a single client
    public static function get($client,$section,$step):array
    {
        // dd($client->presenter()->formatForStep($section, $step));
        return [
            'enums' => $client->loadEnumsForStep($section,$step),
            'model' => $client->presenter()->formatForStep($section, $step), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
            'submit_url' => '/client/' . $client->io_id . '/fact-find/' . $section .'/' . $step //here we hydrate the autosave URL
        ];
    }

    public function validate(int $section,int $step,Request $request)
    {
        return Validator::make($request->all(),config('navigation_structures.factfind.'.$section.'.sections.'.$step.'.rules'))->validated();
    }

    /**
     * @param $client
     * @param $section
     * @param $step
     * @param array $validatedData
     * @return true
     */
    public function store(Client $client, int $section, int $step, array $validatedData): true
    {
        $this->cr->setClient($client);
        $this->{"_".$section.$step}($validatedData);
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

//        ray($validatedData['country_of_domicile'])->purple();
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
}
