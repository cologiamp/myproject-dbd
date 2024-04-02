<?php

namespace App\Jobs\DataEgress;

use App\Models\Client;
use App\Services\DataEgressService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendToIoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private DataEgressService $dataEgressService;

    public function __construct(private Client $client)
    {
        $this->dataEgressService = new DataEgressService();
    }

    public function handle(): void
    {
        $this->dataEgressService->updateClientData($this->client);
        $this->dataEgressService->updateClientContacts($this->client);
        $this->dataEgressService->updateEmployment($this->client);
        $this->dataEgressService->updateDependents($this->client);
        $this->dataEgressService->updateAddresses($this->client);
        $this->dataEgressService->updateHealth($this->client);

        $this->dataEgressService->updateClientIncome($this->client);
        $this->dataEgressService->updateClientExpenditure($this->client);

        // Assets
        $this->dataEgressService->updateClientAssetsFixed($this->client);
        $this->dataEgressService->updateClientAssetsSavings($this->client);
        $this->dataEgressService->updateClientAssetsLumpSum($this->client);
        $this->dataEgressService->updateClientAssetsShareSaveSchemes($this->client);
        $this->dataEgressService->updateClientAssetsOtherInvestments($this->client);
        $this->dataEgressService->updateClientAssetsPensions($this->client);
    }
}
