<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FactFindController extends Controller
{
    protected ClientRepository $clientRepository;
    public function __construct(ClientRepository $cr)
    {
        $this->clientRepository = $cr;
    }
    public function show($client, Request $request) //fact-find?step=1&section=6
    {
        //$req->step - which tab to use
        //$req->section - which sidebar item to use
        $tabs = $this->clientRepository->loadFactFindTabs($request->step != null ? $request->step : 1, $request->section != null ? $request->section : 1);


        return Inertia::render('FactFind',[
            'title' => 'Fact Find',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'step' =>  $request->step,
            'section' => $request->section,
            'tabs' => $tabs
        ]);
    }
}
