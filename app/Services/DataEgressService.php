<?php
namespace App\Services;


use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use App\Services\Intelliflow\ClientAddresses;
use App\Services\Intelliflow\ClientContacts;
use App\Services\Intelliflow\ClientData;
use App\Services\Intelliflow\ClientDependents;
use App\Services\Intelliflow\ClientEmployment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class DataEgressService
{
    use ParsesIoClientData;


    public function updateClientData(Client $client): bool
    {
        return (new ClientData())->updateClientData($client);
    }

    public function updateClientContacts(Client $client): bool
    {
        return (new ClientContacts())->updateClientContacts($client);
    }

    public function updateEmployment(Client $client): bool
    {
        return (new ClientEmployment())->updateEmployment($client);
    }

    public function updateDependents(Client $client): bool
    {
        return (new ClientDependents())->updateDependents($client);
    }

    public function updateAddresses(Client $client): bool
    {
        return (new ClientAddresses())->updateAddresses($client);
    }
}
