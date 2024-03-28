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

        return Inertia::render('RiskProfileQuestionnaire');
    }
    public function submitQ1(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'risk_tolerance' => 'required|boolean',
        ]);

        $this->saveData('comfort_fluctuate_market', $request->risk_tolerance);

        return to_route('client.risk.front');
    }

    public function submitQ2(Client $client, Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'volatility' => 'required|integer',
        ]);

        $this->saveData('day_to_day_volatility', $request->volatility, $client);

        return to_route('client.risk.front');
    }

    public function submitQ3(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'amount_invested' => 'required|numeric',
            'concern5percent' => 'required|numeric',
            'concern10percent' => 'required|numeric',
            'concern20percent' => 'required|numeric',
            'concern30percent' => 'required|numeric',
            'concern40percent' => 'required|numeric',
            'concern50percent' => 'required|numeric',
        ]);

        $this->riskProfileRepository->setClient($client);

        $short_term_volatility = [
            '0' => ['value' => $request->concern5percent],
            '1' => ['value' => $request->concern10percent],
            '2' => ['value' => $request->concern20percent],
            '3' => ['value' => $request->concern30percent],
            '4' => ['value' => $request->concern40percent],
            '5' => ['value' => $request->concern50percent]
        ];

        $this->saveData('short_term_volatility', json_encode($short_term_volatility));

        return to_route('client.risk.front');
    }

    public function submitQ4(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'comfortable_range' => 'required|integer',
        ]);

        $this->saveData('medium_term_volatility', $request->comfortable_range);

        return to_route('client.risk.front');
    }

    public function submitQ5(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'value_drop_behaviour' => 'required|integer',
        ]);

        $this->saveData('volatility_behaviour', $request->value_drop_behaviour);

        return to_route('client.risk.front');
    }

    public function submitQ6(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'comfortable_volatility' => 'required|integer',
        ]);

        $this->saveData('long_term_volatility', $request->comfortable_volatility);

        return to_route('client.risk.front');
    }

    public function submitQ7(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'cash_in_behaviour' => 'required|integer',
        ]);

        $this->saveData('time_in_market', $request->cash_in_behaviour);

        return to_route('client.risk.front');
    }

    private function saveData($fieldName, $fieldValue){

        $client = $this->clientRepository->getClient();
        $this->riskProfileRepository->createOrUpdate([
            'client_id' => $client->id,
            'id' => $client->risk_profile?->id,
            $fieldName => $fieldValue
        ]);
    }
}
