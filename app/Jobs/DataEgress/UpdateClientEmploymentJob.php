<?php

namespace App\Jobs\DataEgress;

use App\Models\Client;
use App\Services\DataEgressService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateClientEmploymentJob implements ShouldQueue
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
    }
}
