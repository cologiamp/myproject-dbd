<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientDashboardController extends Controller
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
        if($client->relationships != null && $client->relationships != 'null' && $client->declined_relationships != true && $client->c2_id == null)
        {
            return to_route('client.relationships',[
                'client' => $client->io_id
            ]);
        }
        return Inertia::render('ClientDashboard',[
            'title' => 'Client Dashboard',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'content-title' => $client->dashboard_title,
            'clientId' => $client->io_id,
            'clientComplete' => $client->complete,
            'clientStatus' => $client->status_int,
        ]);
    }
}
