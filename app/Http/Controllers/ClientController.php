<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Waw\Io\Io;

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
        if ($request->has("search") || $request->has("select")) {
            $clients = $this->clientRepository->filterIndexQuery($request)->get();
        } else {
            $clients = auth()->user()->clients;
        }

        return Inertia::render('ClientSelect',[
            'title' => 'Clients',
            'clients' => $clients->map(fn ($client) => $client->presenter()->formatForClientsIndex()),
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'filters' => request()->only(["search", "select"])
        ]);
    }
}
