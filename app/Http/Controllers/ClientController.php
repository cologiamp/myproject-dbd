<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepository;
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
        return Inertia::render('ClientSelect',[
            'title' => 'Clients',
            'clients' => $this->clientRepository->getIndexOptions()['models'],
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs()
        ]);
    }
}
