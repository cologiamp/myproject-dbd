<?php

namespace App\Services;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use App\Services\Intelliflow\ClientAddresses;
use App\Services\Intelliflow\ClientAssetFixed;
use App\Services\Intelliflow\ClientAssetLumpSum;
use App\Services\Intelliflow\ClientAssetOtherInvestments;
use App\Services\Intelliflow\ClientAssetPensions;
use App\Services\Intelliflow\ClientAssetSavings;
use App\Services\Intelliflow\ClientAssetShareSaveSchemes;
use App\Services\Intelliflow\ClientContacts;
use App\Services\Intelliflow\ClientData;
use App\Services\Intelliflow\ClientDependents;
use App\Services\Intelliflow\ClientEmployment;
use App\Services\Intelliflow\ClientExpenditure;
use App\Services\Intelliflow\ClientHealth;
use App\Services\Intelliflow\ClientIncome;
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

    public function updateHealth(Client $client): bool
    {
        return (new ClientHealth())->updateHealth($client);
    }

    public function updateClientIncome(Client $client): bool
    {
        return (new ClientIncome())->updateClientIncome($client);
    }

    public function updateClientExpenditure(Client $client): bool
    {
        return (new ClientExpenditure())->updateClientExpenditure($client);
    }

    public function updateClientAssetsFixed(Client $client): bool
    {
        return (new ClientAssetFixed())->updateClientAssetsFixed($client);
    }

    public function updateClientAssetsSavings(Client $client): bool
    {
        return (new ClientAssetSavings())->updateClientAssetsSavings($client);
    }

    public function updateClientAssetsLumpSum(Client $client): bool
    {
        return (new ClientAssetLumpSum())->updateClientAssetsLumpSum($client);
    }

    public function updateClientAssetsOtherInvestments(Client $client): bool
    {
        return (new ClientAssetOtherInvestments())->updateClientAssetOtherInvestments($client);
    }

    public function updateClientAssetsShareSaveSchemes(Client $client): bool
    {
        return (new ClientAssetShareSaveSchemes())->updateClientAssetsShareSaveSchemes($client);
    }

    public function updateClientAssetsPensions(Client $client): bool
    {
        return (new ClientAssetPensions())->updateClientAssetPensions($client);
    }
}
