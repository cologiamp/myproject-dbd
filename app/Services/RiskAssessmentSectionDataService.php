<?php

namespace App\Services;


use App\Concerns\FormatsCurrency;
use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Support\Facades\Validator;

class RiskAssessmentSectionDataService
{
    protected ClientRepository $cr;

    public function __construct(
        ClientRepository $clientRepository
) {
        $this->cr = $clientRepository;
    }
    //get the data for a single section of a risk from a single client
    public static function get($risk, $step, $section): array
    {
        return [
            'enums' => $risk->loadEnumsForStep($step, $section),
            'model' => $risk->presenter()->formatForStep($step, $section), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
            'submit_url' => '/api/client/' . $risk->client->io_id . '/risk/' . $step . '/' . $section //here we hydrate the autosave URL
        ];
    }

    public function validate(int $step, int $section, $request)
    {
        $messages = config('navigation_structures.risk.' . $step . '.sections.' . $section . '.messages');
        $rules = config('navigation_structures.risk.' . $step . '.sections.' . $section . '.rules');

        if(!is_array($request))
        {
            $request = $request->all();
        }
        return Validator::make($request, $rules, $messages)->validate();
    }

    public function validated(int $step, int $section,  $request)
    {
        $messages = config('navigation_structures.risk.' . $step . '.sections.' . $section . '.messages');
        $rules = config('navigation_structures.risk.' . $step . '.sections.' . $section . '.rules');

        if(!is_array($request))
        {
            $request = $request->all();
        }
        return Validator::make($request, $rules, $messages)->validated();
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


    //Risk:// Need to write a function named _{section}{step} for every form
    /**
     * Section: 1
     * Step: 1
     * @param array $validatedData
     * @return void
     */
//    private function _11(array $validatedData): void
//    {
        //define any explicit mutators that are not handled
//    }
}
