<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Repositories\ClientRepository;
use App\Repositories\RiskProfileRepository;

class RiskProfileQuestionnaireController extends Controller
{

    protected ClientRepository $clientRepository;
    protected RiskProfileRepository $riskProfileRepository;
    public function __construct(
        ClientRepository $cr,
        RiskProfileRepository $riskProfileRepository)
    {
        $this->clientRepository = $cr;
        $this->riskProfileRepository = $riskProfileRepository;
    }

    public function show(Client $client): \Inertia\Response
    {
        $this->clientRepository->setClient($client);
        $this->riskProfileRepository->setClient($client);

        return Inertia::render('RiskProfileQuestionnaire',[
            'client_id' => $client->io_id
        ]);
    }


    public function store(Client $client, int $question, Request $request):\Illuminate\Http\RedirectResponse
    {
        $this->clientRepository->setClient($client);
        $this->riskProfileRepository->setClient($client);

        switch ($question){
            case(1):
                $request->validate([
                    'risk_tolerance' => 'required|boolean',
                ]);
                $this->saveData('comfort_fluctuate_market', $request->risk_tolerance);
                break;
            case(2):
                $request->validate([
                    'volatility' => 'required|integer',
                ]);
                $this->saveData('day_to_day_volatility', $request->volatility);
                break;
            case(3):
                $request->validate([
                    'amount_invested' => 'required|numeric',
                    'concern5percent' => 'required|numeric',
                    'concern10percent' => 'required|numeric',
                    'concern20percent' => 'required|numeric',
                    'concern30percent' => 'required|numeric',
                    'concern40percent' => 'required|numeric',
                    'concern50percent' => 'required|numeric',
                ]);
                $this->saveData('short_term_volatility', json_encode([
                    '0' => ['value' => (int)$request->concern5percent],
                    '1' => ['value' => (int)$request->concern10percent],
                    '2' => ['value' => (int)$request->concern20percent],
                    '3' => ['value' => (int)$request->concern30percent],
                    '4' => ['value' => (int)$request->concern40percent],
                    '5' => ['value' => (int)$request->concern50percent]
                ]));
                break;
            case(4):
                $request->validate([
                    'comfortable_range' => 'required|integer',
                ]);
                $this->saveData('medium_term_volatility', $request->comfortable_range);
                break;
            case(5):
                $request->validate([
                    'value_drop_behaviour' => 'required|integer',
                ]);
                $this->saveData('volatility_behaviour', $request->value_drop_behaviour);
                break;
            case(6):
                $request->validate([
                    'comfortable_volatility' => 'required|integer',
                ]);
                $this->saveData('long_term_volatility', $request->comfortable_volatility);
                break;
            case(7):
                $request->validate([
                    'cash_in_behaviour' => 'required|integer',
                ]);
                $this->saveData('time_in_market', $request->cash_in_behaviour);
                break;
        }
        return to_route('client.risk.front', ['client' => $client->io_id]);
    }


    private function saveData($fieldName, $fieldValue): void
    {
        $client = $this->clientRepository->getClient();
        $this->riskProfileRepository->createOrUpdate([
            'client_id' => $client->id,
            'id' => $client->risk_profile?->id,
            $fieldName => $fieldValue
        ]);
    }
}
