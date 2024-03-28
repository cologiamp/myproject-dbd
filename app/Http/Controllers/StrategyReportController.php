<?php

namespace App\Http\Controllers;

use App\Http\Resources\StrategyReportResource;
use App\Models\Client;
use App\Models\StrategyReport;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StrategyReportController extends Controller
{
    protected ClientRepository $clientRepository;
    public function __construct(ClientRepository $cr)
    {
        $this->clientRepository = $cr;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Client $client)
    {
        return Inertia::render('StrategyReport',[
            'title' => 'Strategy Report for ' . $client->name,
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'content-title' => 'Welcome to ' . $client->name . "'s strategy report",
            'clientId' => $client->io_id,
            'strategy_reports' => StrategyReportResource::collection(StrategyReport::where('client_id', $client->id)->orderBy('created_at','DESC')->get()),
        ]);
    }
}
