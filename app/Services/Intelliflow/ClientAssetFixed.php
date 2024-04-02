<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Concerns\SendIoData;
use App\Models\Asset;
use App\Models\Client;
use App\Services\DataEgressService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientAssetFixed
{
    use ParsesIoClientData;
    use SendIoData;

    public function updateClientAssetsFixed(Client $client): bool
    {
        $assets = $client->assets()->where('category', 1)->get();

        $assets->each(function($asset) use ($client) {
            $originalValue = (int) $asset->original_value > 0 ? ($asset->original_value / 100) : 0;
            $currentValue = (int) $asset->current_value > 0 ? ($asset->current_value / 100) : 0;

            $data = $this->buildData($client, $asset, $originalValue, $currentValue);
            $asset = $this->createOrUpdateAsset($client, $asset, $data);
        });

        return true;
    }

    private function buildData(
        Client $client,
        Asset $asset,
        float $originalValue,
        float $currentValue,
    ): array
    {
        $data = [
            'description' => $asset->description,
            'assetType' => config("enums.assets.types.{$asset->type}"),
            'originalValue' => [
                'valuedOn' => $asset->start_at ? $asset->start_at : now(),
                'value' => [
                    'currency' => 'GBP',
                    'amount' => (string) $originalValue,
                ],
            ],
            'purchasePrice' => [
                'currency' => 'GBP',
                'amount' => (string) $originalValue,
            ],
            'currentValue' => [
                // TODO No current value date, this probably needs considering
                'valuedOn' => $asset->start_at ? $asset->start_at : now(),
                'value' => [
                    'currency' => 'GBP',
                    'amount' => (string) $currentValue,
                ],
            ],
            'purchasedOn' => $asset->start_at ? $asset->start_at : null,
            'owners' => [
                [
                    'id' => $client->io_id,
                    'percentOwnership' => $asset->pivot->percent_ownership ?? 0,
                ],
            ],
        ];

        return $data;
    }
}
