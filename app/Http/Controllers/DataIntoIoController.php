<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Services\DataEgressService;
use Illuminate\Http\Request;

class DataIntoIoController extends Controller
{
    /**
     * Handle the incoming request.
     */
    protected DataEgressService $dataEgressService;
    public function __construct(DataEgressService $des)
    {
        $this->dataEgressService = $des;
    }
    public function __invoke(Client $client, Request $request)
    {
       //chore: this needs to be more modular, might involve a queue job etc. For now, just make it quick and dirty to prove it works:
        return $this->clientRepository->submitDataIntoIO();
    }
}
