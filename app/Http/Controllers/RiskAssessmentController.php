<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Repositories\CapacityForLossRepository;
use App\Repositories\ClientRepository;
use App\Repositories\RiskProfileRepository;
use App\Repositories\KnowledgeRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RiskAssessmentController extends Controller
{
    protected ClientRepository $clientRepository;
    protected RiskProfileRepository $riskProfileRepository;
    protected KnowledgeRepository $knowledgeRepository;
    protected CapacityForLossRepository $capacityForLossRepository;
    public function __construct(
        ClientRepository $cr,
        RiskProfileRepository $riskProfileRepository,
        KnowledgeRepository $knowledgeRepository,
        CapacityForLossRepository $capacityForLossRepository)
    {
        $this->clientRepository = $cr;
        $this->riskProfileRepository = $riskProfileRepository;
        $this->knowledgeRepository = $knowledgeRepository;
        $this->capacityForLossRepository = $capacityForLossRepository;
    }

    public function show(Client $client, Request $request)
    {
        $this->clientRepository->setClient($client);
        if(!$client->risk_profile) {
            $this->riskProfileRepository->setClient($client);
            $client->risk_profile = $this->riskProfileRepository->createInitialRiskProfileForClient();
        }

        if(!$client->knowledge->first()) {
            $this->knowledgeRepository->setClient($client);
            $client->knowledge = $this->knowledgeRepository->createInitialKnowledgeForClient();
        }

        if(!$client->capacity_for_loss->first()) {
            $this->capacityForLossRepository->setClient($client);
            $client->capacity_for_loss = $this->capacityForLossRepository->createInitialCapacityForClient();
        }

        $section = $request->section ?? 1;
        $step = $request->step ?? 1;

        $tabs = $this->clientRepository->loadRiskTabs($step,$section);

        return Inertia::render('Risk', [
            'title' => 'Risk Profile',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'step' =>  $step,
            'section' => $section,
            'tabs' => $tabs,
            'url' => $request->getBaseUrl().$request->getRequestUri().$request->getQueryString(),
        ]);
    }
}
