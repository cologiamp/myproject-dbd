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
        return Inertia::render('ClientDashboard',[
            'title' => 'Client Dashboard',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'content-title' => 'Welcome to ' . $client->name . "'s dashboard",
            'clientId' => $client->io_id
        ]);
    }
}
