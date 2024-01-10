<?php

namespace App\Http\Controllers;

use App\Repositories\BaseRepository;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Waw\Io\Io;

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
        if ($request->has("search") || $request->has("select")) {
            $clients = $this->clientRepository->filterIndexQuery($request)->get();
        } else {
            $clients = Auth::user()->clients;
        }

        return Inertia::render('Dashboard',[
            'title' => 'Clients',
            'clients' => $clients->map(fn ($client) => $client->presenter()->formatForClientsIndex()),
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'filters' => $request->only(["search", "select"])
        ]);
    }
}
