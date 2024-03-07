<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RiskController extends Controller
{
    protected ClientRepository $clientRepository;
    public function __construct(ClientRepository $cr)
    {
        $this->clientRepository = $cr;
    }

    public function show(Client $client, Request $request)
    {
        $this->clientRepository->setClient($client);
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
