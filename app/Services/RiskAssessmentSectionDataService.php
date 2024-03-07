<?php

namespace App\Services;


use App\Concerns\FormatsCurrency;
use App\Models\Client;
use App\Repositories\CapacityForLossRepository;
use App\Repositories\ClientRepository;
use App\Repositories\KnowledgeRepository;
use App\Repositories\RiskProfileRepository;
use Illuminate\Support\Facades\Validator;

class RiskAssessmentSectionDataService
{
    protected ClientRepository $cr;

    public function __construct(
        ClientRepository $clientRepository,
        KnowledgeRepository $knowledgeRepository,
        CapacityForLossRepository $capacityForLossRepository,
        RiskProfileRepository $riskProfileRepository
) {
        $this->clientRepository = $clientRepository;
        $this->knowledgeRepository = $knowledgeRepository;
        $this->capacityForLossRepository = $capacityForLossRepository;
        $this->riskProfileRepository = $riskProfileRepository;
    }
    //get the data for a single section of a risk from a single client
    public static function get($client, $step, $section): array
    {
        return [
            'enums' => $client->risk_profile->loadEnumsForStep($step, $section),
            'model' => $client->risk_profile->presenter()->formatForStep($step, $section), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
            'submit_url' => '/api/client/' . $client->io_id . '/risk-assessment/' . $step . '/' . $section //here we hydrate the autosave URL
        ];
    }

    public function validate(int $step, int $section, $request)
    {
        $messages = config('navigation_structures.riskassessment.' . $step . '.sections.' . $section . '.messages');
        $rules = config('navigation_structures.riskassessment.' . $step . '.sections.' . $section . '.rules');

        if(!is_array($request))
        {
            $request = $request->all();
        }
        return Validator::make($request, $rules, $messages)->validate();
    }

    public function validated(int $step, int $section,  $request)
    {
        $messages = config('navigation_structures.riskassessment.' . $step . '.sections.' . $section . '.messages');
        $rules = config('navigation_structures.riskassessment.' . $step . '.sections.' . $section . '.rules');

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

        $this->clientRepository->setClient($client);
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
    private function _11(array $validatedData): void
    {
        // define any explicit mutators that are not handled
        $this->knowledgeRepository->setClient($this->clientRepository->getClient());

        $validatedData['type'] = 0; // Investment Risk type
        $this->knowledgeRepository->createOrUpdate($validatedData);
    }

    private function _12(array $validatedData): void
    {
        // define any explicit mutators that are not handled
        $this->capacityForLossRepository->setClient($this->clientRepository->getClient());

        $validatedData['type'] = 0; // Investment Risk type
        $this->capacityForLossRepository->createOrUpdate($validatedData);
    }

    private function _13(array $validatedData): void
    {
        // define any explicit mutators that are not handled
        $this->riskProfileRepository->setClient($this->clientRepository->getClient());

        $validatedData['type'] = 0; // Investment Risk type
        $this->riskProfileRepository->createOrUpdate($validatedData);
    }
}
