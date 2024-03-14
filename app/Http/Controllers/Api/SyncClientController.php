<?php

namespace App\Http\Controllers\Api;

use App\Concerns\ParsesIoClientData;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
class SyncClientController extends Controller
{
    use ParsesIoClientData;
    protected $clientRepository;
    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    /**
     * @param Client $client
     * @param Request $request
     * @return true
     * @throws AuthenticationException
     */
    public function __invoke(Client $client, Request $request): true
    {
        if(!(\Auth::user() && \Auth::user()->can('access IO data'))){
            throw new AuthenticationException('No user found or no permission to execute this');
        }
        if($client->io_json != null && $client->getDirtyChanges()->count() > 0 && !$request->force)
        {
            throw new \Exception('Do you really want to do this? There are changes to the following fields: ' . implode(', ',$client->getDirtyChanges()->toArray()));
        }
                //They can do this
       $this->fetchAndHandleClient($client);


        return true;
    }
}
