<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BaseClientRequest;
use App\Http\Requests\ExampleEditRequest;
use App\Models\Address;
use App\Models\Asset;
use App\Models\Client;
use App\Repositories\AssetRepository;
use App\Repositories\ClientRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $clientRepository;
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function deleteAddress( Address $address):bool
    {
        $this->clientRepository->deleteAddress($address);
        return true;
    }

    public function toggleClientComplete(Request $request, Client $client):bool
    {
        $client->complete = !$client->complete;
        $client->save();
        return true;
    }
}
