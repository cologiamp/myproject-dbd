<?php

namespace App\Concerns;

use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

trait SendIoData{
    public function createOrUpdateAsset(Client $client, mixed $asset, array $payload): mixed
    {
        $i = new Io();

        try {
            if ($asset->io_id === null) {
                $asset->io_id = $i->createAsset($client->io_id, $payload);
                $asset->save();
            } else {
                $i->updateAsset($client->io_id, $asset->io_id, $payload);
            }
        } catch (\Exception $e) {
            Log::error('Failed to update asset details for client: ' . $client->id);
            Log::error($e->getMessage());
            return false;
        }

        return $asset;
    }

    public function createOrUpdatePlan(Client $client, mixed $asset, array $payload): mixed
    {
        $i = new Io();

        try {
            if ($asset->plan_io_id) {
                $i->updatePlan($client->io_id, $asset->plan_io_id, $payload);
            } else {
                $asset->plan_io_id = $i->createPlan($client->io_id, $payload);
                $asset->save();
            }
        } catch (\Exception $e) {
            Log::error('Failed to update plan details for client: ' . $client->id);
            Log::error($e->getMessage());
            return false;
        }

        return $asset;
    }

    public function createOrUpdateContribution(Client $client, mixed $asset, array $payload, string $contributionIdColumn = 'contribution_io_id'): mixed
    {
        $i = new Io();

        try {
            if ($asset->$contributionIdColumn) {
                $i->updateContribution($client->io_id, $asset->plan_io_id, $asset->$contributionIdColumn, $payload);
            } else {
                $asset->$contributionIdColumn = $i->createContribution($client->io_id, $asset->plan_io_id, $payload);
                $asset->save();
            }
        } catch (\Exception $e) {
            Log::error('Failed to update contribution details for client: ' . $client->id);
            Log::error($e->getMessage());
            return false;
        }

        return $asset;
    }

    public function createOrUpdateIncome()
    {
        // TODO
    }

    public function createOrUpdateExpenditure()
    {
        // TODO
    }
}
