<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected ClientRepository $clientRepository;

    public function __construct(ClientRepository $cr)
    {
        $this->clientRepository = $cr;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $clients = $this->clientRepository->filterIndexQuery($request);

        return Inertia::render('Dashboard',[
            'title' => 'Clients',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'clients' => $clients->map(fn ($client) => $client->presenter()->formatForClientsIndex()),
            'pagination' => collect($clients->toArray())->except("data"),
            'filters' => $request->only(["search", "select", "page", "perPage"])
        ]);
    }
}
