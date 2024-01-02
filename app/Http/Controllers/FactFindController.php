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
    public function show($client, Request $request)
    {
        return Inertia::render('FactFind',[
            'title' => 'Fact Find',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'step' =>  $request->step,
            'section' => $request->section
        ]);
    }
}
