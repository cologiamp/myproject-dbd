<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepository;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ClientController extends Controller
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

        //dd($this->clientRepository->loadBreadcrumbs());

        return Inertia::render('ClientSelect',[
            'title' => 'Clients',
            'clients' => $clients->map(fn ($client) => $client->presenter()->formatForClientsIndex()),
            'pagination' => collect($clients->toArray())->except("data"),
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'filters' => $request->only(["search", "select", "page", "perPage"])
        ]);
    }
}
