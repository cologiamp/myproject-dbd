<?php

namespace App\Services;

use App\Models\Client;
use App\Models\RiskOutcome;
use App\Repositories\CapacityForLossRepository;
use App\Repositories\ClientRepository;
use App\Repositories\KnowledgeRepository;
use App\Repositories\RiskProfileRepository;
use App\Repositories\RiskOutcomeRepository;
use Illuminate\Support\Facades\Validator;

class RiskAssessmentSectionDataService
{
    protected ClientRepository $cr;

    public function __construct(
        ClientRepository $clientRepository,
        KnowledgeRepository $knowledgeRepository,
        CapacityForLossRepository $capacityForLossRepository,
        RiskProfileRepository $riskProfileRepository,
        RiskOutcomeRepository $riskOutcomeRepository
) {
        $this->clientRepository = $clientRepository;
        $this->knowledgeRepository = $knowledgeRepository;
        $this->capacityForLossRepository = $capacityForLossRepository;
        $this->riskProfileRepository = $riskProfileRepository;
        $this->riskOutcomeRepository = $riskOutcomeRepository;
    }
    //get the data for a single section of a risk from a single client
    public static function get($client, $step, $section): array
    {
        $riskInv = $client->risk_profile;
        return [
            'enums' => $riskInv->loadEnumsForStep($step, $section),
            'model' => $riskInv->presenter()->formatForStep($step, $section), //here we load the data for that part of the form
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

        $validatedData['type'] = config('enums.risk_assessment.type')['INVESTMENT_TYPE'];
        $validatedData['experience_buying_cash'] = json_encode($validatedData['experience_buying_cash']);
        $validatedData['experience_buying_bonds'] = json_encode($validatedData['experience_buying_bonds']);
        $validatedData['experience_buying_equities'] = json_encode($validatedData['experience_buying_equities']);
        $validatedData['experience_buying_insurance'] = json_encode($validatedData['experience_buying_insurance']);

        $this->knowledgeRepository->createOrUpdate($validatedData);
    }

    private function _12(array $validatedData): void
    {
        // define any explicit mutators that are not handled
        $this->capacityForLossRepository->setClient($this->clientRepository->getClient());

        $validatedData['type'] = config('enums.risk_assessment.type')['INVESTMENT_TYPE'];
        $this->capacityForLossRepository->createOrUpdate($validatedData);
    }

    /**
     * Section: 1
     * Step: 3
     * @param array $validatedData
     * @return void
     */
    private function _13(array $validatedData): void
    {
        if ($this->clientRepository->getClient()->client_two) {
            // define any explicit mutators that are not handled
            $this->knowledgeRepository->setClient($this->clientRepository->getClient()->client_two);

            $validatedData['type'] = config('enums.risk_assessment.type')['INVESTMENT_TYPE'];
            $validatedData['experience_buying_cash'] = json_encode($validatedData['experience_buying_cash']);
            $validatedData['experience_buying_bonds'] = json_encode($validatedData['experience_buying_bonds']);
            $validatedData['experience_buying_equities'] = json_encode($validatedData['experience_buying_equities']);
            $validatedData['experience_buying_insurance'] = json_encode($validatedData['experience_buying_insurance']);

            $this->knowledgeRepository->createOrUpdate($validatedData);
        }
    }

    private function _14(array $validatedData): void
    {
        if ($this->clientRepository->getClient()->client_two) {
            // define any explicit mutators that are not handled
            $this->capacityForLossRepository->setClient($this->clientRepository->getClient()->client_two);

            $validatedData['type'] = config('enums.risk_assessment.type')['INVESTMENT_TYPE'];
            $this->capacityForLossRepository->createOrUpdate($validatedData);
        }
    }

    private function _21(array $validatedData): void
    {
        // define any explicit mutators that are not handled
        $this->knowledgeRepository->setClient($this->clientRepository->getClient());

        $validatedData['type'] = config('enums.risk_assessment.type')['PENSION_TYPE'];
        $this->knowledgeRepository->createOrUpdate($validatedData);
    }

    private function _22(array $validatedData): void
    {
        // define any explicit mutators that are not handled
        $this->capacityForLossRepository->setClient($this->clientRepository->getClient());

        $validatedData['type'] = config('enums.risk_assessment.type')['PENSION_TYPE'];
        $this->capacityForLossRepository->createOrUpdate($validatedData);
    }

    private function _23(array $validatedData): void
    {
        if ($this->clientRepository->getClient()->client_two) {
            // define any explicit mutators that are not handled
            $this->knowledgeRepository->setClient($this->clientRepository->getClient()->client_two);

            $validatedData['type'] = config('enums.risk_assessment.type')['PENSION_TYPE'];
            $this->knowledgeRepository->createOrUpdate($validatedData);
        }
    }

    private function _24(array $validatedData): void
    {
        if ($this->clientRepository->getClient()->client_two) {
            // define any explicit mutators that are not handled
            $this->capacityForLossRepository->setClient($this->clientRepository->getClient()->client_two);

            $validatedData['type'] = config('enums.risk_assessment.type')['PENSION_TYPE'];
            $this->capacityForLossRepository->createOrUpdate($validatedData);
        }
    }

    private function _31(array $validatedData): void
    {
        // define any explicit mutators that are not handled
        $this->riskProfileRepository->setClient($this->clientRepository->getClient());

        $validatedData['short_term_volatility'] = json_encode($validatedData['short_term_volatility']);
        $this->riskProfileRepository->createOrUpdate($validatedData);
    }

    private function _32(array $validatedData): void
    {
        if ($this->clientRepository->getClient()->client_two) {
            // define any explicit mutators that are not handled
            $this->riskProfileRepository->setClient($this->clientRepository->getClient()->client_two);

            $validatedData['short_term_volatility'] = json_encode($validatedData['short_term_volatility']);
            $this->riskProfileRepository->createOrUpdate($validatedData);
        }
    }

    private function _41(array $validatedData): void
    {
        // define any explicit mutators that are not handled
        if (array_key_exists('using_calculated_risk_profile_investment', $validatedData) && $validatedData['using_calculated_risk_profile_investment']) {
            $validatedData['adviser_recommendation_investment'] = null;
            $validatedData['why_investment'] = null;
        }

        if (array_key_exists('using_calculated_risk_profile_pension', $validatedData) && $validatedData['using_calculated_risk_profile_pension']) {
            $validatedData['adviser_recommendation_pension'] = null;
            $validatedData['why_pension'] = null;
        }

        $client = $this->clientRepository->getClient();
        $this->riskOutcomeRepository->setClient($client);
        $this->riskOutcomeRepository->setRiskOutcome($client->risk_outcome);

        $this->riskOutcomeRepository->updateFromValidated($validatedData);
    }

    private function _42(array $validatedData): void
    {
        if ($this->clientRepository->getClient()->client_two) {
            // define any explicit mutators that are not handled
            if (array_key_exists('using_calculated_risk_profile_investment', $validatedData) && $validatedData['using_calculated_risk_profile_investment']) {
                $validatedData['adviser_recommendation_investment'] = null;
                $validatedData['why_investment'] = null;
            }

            if (array_key_exists('using_calculated_risk_profile_pension', $validatedData) && $validatedData['using_calculated_risk_profile_pension']) {
                $validatedData['adviser_recommendation_pension'] = null;
                $validatedData['why_pension'] = null;
            }

            $client = $this->clientRepository->getClient()->client_two;
            $this->riskOutcomeRepository->setClient($client);

            $this->riskOutcomeRepository->setRiskOutcome($client->risk_outcome);

            $this->riskOutcomeRepository->updateFromValidated($validatedData);
        }
    }

    public function assessMatrixResult(RiskOutcome $outcome): void
    {
        $investmentAssessmentResult = $this->getMatrixAssessmentResult('investment', $outcome);
        $pensionAssessmentResult = $this->getMatrixAssessmentResult('pension', $outcome);

        $this->riskOutcomeRepository->setRiskOutcome($outcome);
        $this->riskOutcomeRepository->updateFromValidated([
            'assessment_result_investment' => $investmentAssessmentResult,
            'assessment_result_pension' => $pensionAssessmentResult
        ]);
    }

    private function getMatrixAssessmentResult(string $type, RiskOutcome $outcome): int
    {
        return match (true) {
            $outcome['attitude_to_risk'] == 3 && $this->checkCapacityForLoss($type, $outcome, 2) && $this->checkKnE($type, $outcome, [0,1,2]) => config('enums.risk_assessment.assessment_result')['ADVENTUROUS'],
            $outcome['attitude_to_risk'] == 2 && $this->checkCapacityForLoss($type, $outcome, 2) && $this->checkKnE($type, $outcome, [0,1,2]) => config('enums.risk_assessment.assessment_result')['BALANCED'],
            $outcome['attitude_to_risk'] == 1 && $this->checkCapacityForLoss($type, $outcome, 2) && $this->checkKnE($type, $outcome, [0,1,2]) => config('enums.risk_assessment.assessment_result')['CAUTIOUS'],
            $outcome['attitude_to_risk'] == 3 && $this->checkCapacityForLoss($type, $outcome, 1) && $this->checkKnE($type, $outcome, [1,2]) => config('enums.risk_assessment.assessment_result')['ADVENTUROUS'],
            $outcome['attitude_to_risk'] == 3 && $this->checkCapacityForLoss($type, $outcome, 1) && $this->checkKnE($type, $outcome, [0]) => config('enums.risk_assessment.assessment_result')['BALANCED'],
            $outcome['attitude_to_risk'] == 2 && $this->checkCapacityForLoss($type, $outcome, 1) && $this->checkKnE($type, $outcome, [1,2]) => config('enums.risk_assessment.assessment_result')['BALANCED'],
            $outcome['attitude_to_risk'] == 2 && $this->checkCapacityForLoss($type, $outcome, 1) && $this->checkKnE($type, $outcome, [0]) => config('enums.risk_assessment.assessment_result')['CAUTIOUS'],
            $outcome['attitude_to_risk'] == 1 && $this->checkCapacityForLoss($type, $outcome, 1) && $this->checkKnE($type, $outcome, [1,2]) => config('enums.risk_assessment.assessment_result')['CAUTIOUS'],
            $outcome['attitude_to_risk'] == 1 && $this->checkCapacityForLoss($type, $outcome, 1) && $this->checkKnE($type, $outcome, [0]) => config('enums.risk_assessment.assessment_result')['NOT_SUITABLE'],
            $outcome['attitude_to_risk'] == 3 && $this->checkCapacityForLoss($type, $outcome, 0) && $this->checkKnE($type, $outcome, [1,2]) => config('enums.risk_assessment.assessment_result')['BALANCED'],
            $outcome['attitude_to_risk'] == 3 && $this->checkCapacityForLoss($type, $outcome, 0) && $this->checkKnE($type, $outcome, [0]) => config('enums.risk_assessment.assessment_result')['CAUTIOUS'],
            $outcome['attitude_to_risk'] == 2 && $this->checkCapacityForLoss($type, $outcome, 0) && $this->checkKnE($type, $outcome, [1,2]) => config('enums.risk_assessment.assessment_result')['CAUTIOUS'],
            $outcome['attitude_to_risk'] == 2 && $this->checkCapacityForLoss($type, $outcome, 0) && $this->checkKnE($type, $outcome, [0]) => config('enums.risk_assessment.assessment_result')['NOT_SUITABLE'],
            $outcome['attitude_to_risk'] == 1 && $this->checkCapacityForLoss($type, $outcome, 0) && $this->checkKnE($type, $outcome, [0,1,2]) => config('enums.risk_assessment.assessment_result')['NOT_SUITABLE'],
            default => config('enums.risk_assessment.assessment_result')['NOT_SUITABLE']
        };
    }

    private function checkCapacityForLoss(string $type, RiskOutcome $outcome, int $score): bool
    {
        return $outcome['capacity_for_loss_' . $type] == $score;
    }

    private function checkKnE(string $type, RiskOutcome $outcome, array $range): bool
    {
        return in_array($outcome['knowledge_and_experience_' . $type], $range);
    }
}
