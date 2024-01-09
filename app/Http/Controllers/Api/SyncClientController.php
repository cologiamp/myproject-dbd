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
     * Handle the incoming request.
     */
    public function __invoke(Client $client, Request $request)
    {
        if(!(\Auth::user() && \Auth::user()->can('access IO data'))){
            throw new AuthenticationException('No user found or no permission to execute this');
        }
        if($client->io_json != null && $cdc = $client->getDirtyChanges()->count() > 0 && !$request->force)
        {
            throw new \Exception('Do you really want to do this? There are changes to the following fields: ' . implode($cdc,', '));
        }
        //They can do this
         $dis = App::make(\App\Services\DataIngestService::class);
         //First, store this data, so we can compare against it later
        $data = $dis->getClient($client->io_id);
        $data['addresses'] = $dis->getAddresses($client->io_id)['items'];
        $data['contactdetails'] = $dis->getContactDetails($client->io_id)['items'];
        $this->clientRepository->setClient($client);
        $this->clientRepository->updateFromValidated(['io_json' => $data]);
        //Then, update the client and connected items that we want to do so from the start.
        $data = $this->parseClientData($data);

        $this->clientRepository->updateFromValidated($data['client']);
        collect($data['addresses'])->each(function ($item){
            $this->clientRepository->createOrUpdateAddress($item);
        });
        return true;
    }
}
