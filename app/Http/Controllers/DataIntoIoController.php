<?php

namespace App\Http\Controllers;

use App\Jobs\DataEgress\SendToIoJob;
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
        SendToIoJob::dispatch($client);

        return true;
    }
}
