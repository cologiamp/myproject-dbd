<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Concerns\SendIoData;
use App\Models\Asset;
use App\Models\Client;
use App\Models\LumpSumCapital;
use App\Services\DataEgressService;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientAssetLumpSum
{
    use ParsesIoClientData;
    use SendIoData;

    public function updateClientAssetsLumpSum(Client $client): bool
    {
        $assets = $client->lump_sum_capitals()->get();

        $assets->each(function($asset) use ($client) {
            $amount = (int) $asset->amount > 0 ? ($asset->amount / 100) : 0;
            $retainedValue = (int) $asset->retained_value > 0 ? ($asset->retained_value / 100) : 0;

            $data = $this->buildData($client, $asset, $amount, $retainedValue);
            $asset = $this->createOrUpdateAsset($client, $asset, $data);
        });

        return true;
    }

    private function buildData(
        Client $client,
        LumpSumCapital $asset,
        float $amount,
        float $retainedValue
    ): array
    {
        $data = [
            'description' => $asset->description,
            'assetType' => config("enums.assets.types.0"),
            'currentValue' => [
                // TODO Using "due_at" date, is this correct?
                'valuedOn' => $asset->due_at ? $asset->due_at : now(),
                'value' => [
                    'currency' => 'GBP',
                    'amount' => (string) $amount,
                ],
            ],
            'purchasedOn' => $asset->due_at ? $asset->due_at : null,
            'owners' => [
                [
                    'id' => $client->io_id,
                ],
            ],
        ];

        return $data;
    }
}
