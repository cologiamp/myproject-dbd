<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientRelationshipController extends Controller
{
    protected ClientRepository $clientRepository;
    public function __construct(ClientRepository $cr)
    {
        $this->clientRepository = $cr;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Client $client): \Inertia\Response
    {
        return Inertia::render('ClientRelationships',[
            'title' => 'Select Fact-Find Relationships',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'content-title' => 'Does this client wish to complete a joint fact-find?',
            'clientId' => $client->io_id,
            'relationships' => json_decode($client->relationships)
        ]);
    }
}
