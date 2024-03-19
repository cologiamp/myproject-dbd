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
    public function __invoke(Client $client, Request $request): bool
    {
        //chore: this needs to be more modular, might involve a queue job etc.

        $this->dataEgressService->updateEmployment($client);

//        $this->dataEgressService->updateClient($client); // returns true at the moment

        return true;
    }
}
