<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Concerns\SendIoData;
use App\Models\Client;
use App\Models\ShareSaveScheme;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientAssetShareSaveSchemes
{
    use ParsesIoClientData;
    use SendIoData;

    public function updateClientAssetsShareSaveSchemes(Client $client): bool
    {
        $i = new Io();

        $assets = $client->share_save_schemes()->get();

        $assets->each(function ($asset) use ($i, $client) {
            $currentValue = (int)$asset->option_price > 0 ? ($asset->option_price / 100) : 0;

            // Set up a plan
            $planPayload = $this->buildPlanData($client, $asset);
            $asset = $this->createOrUpdatePlan($client, $asset, $planPayload);

            // Set up a contribution
            $contributionPayload = $this->buildContributionData($client, $asset);

            if ($asset->contribution_io_id) {
                $i->updateContribution(
                    $client->io_id,
                    $asset->plan_io_id,
                    $asset->contribution_io_id,
                    $contributionPayload
                );
            } else {
                $asset->contribution_io_id = $i->createContribution(
                    $client->io_id,
                    $asset->plan_io_id,
                    $contributionPayload
                );
                $asset->save();
            }

            $data = $this->buildAssetData($client, $asset, $currentValue);
            $asset = $this->createOrUpdateAsset($client, $asset, $data);
        });

        return true;
    }

    private function buildPlanData(
        Client $client,
        ShareSaveScheme  $asset
    ): array
    {
        $payload = [
            "currency" => "GBP",

            "productName" => $asset->name,
            "startsOn" => $asset->start_at ? $asset->start_at : null,
            "endsOn" => $asset->matures_at ? $asset->matures_at : null,

            "planType" => [
                "name" => config("enums.assets.account_types.0")
            ],

            "owners" => [
                [
                    "id" => $client->io_id
                ]
            ],

            "lifecycle" => [
                "id" => 54677 // TODO - Set to "Pre-Existing" - is this correct?
            ],

            "sellingAdviser" => [
                "id" => $client->adviser->io_id
            ],

            "discriminator" => "InvestmentPlan"
        ];

        return $payload;
    }

    private function buildContributionData(
        Client $client,
        ShareSaveScheme  $asset
    ): array
    {
        $contributionAmount = (float)$asset->monthly_saving;
        if ($contributionAmount !== 0) {
            $contributionAmount /= 100;
        }

        $payload = [
            "frequency" => config("enums.assets.frequency.0"), // Monthly
            "value" => [
                "currency" => "GBP",
                "amount" => (string)$contributionAmount
            ],
            "startsOn" => $asset->start_at ? $asset->start_at : null,
            "endsOn" => $asset->matures_at ? $asset->matures_at : null,
            "contributionType" => "Regular",
            "contributorType" => "Self"
        ];

        return $payload;
    }

    private function buildAssetData(
        Client $client,
        ShareSaveScheme  $asset,
        float  $currentValue,
    ): array
    {
        $payload = [
            'description' => $asset->name,
            'plan' => [
                'id' => $asset->plan_io_id,
            ],
            'assetType' => config("enums.assets.types.11"), // TODO Is "Investments" correct?
            'originalValue' => [
                'valuedOn' => $asset->start_at ? $asset->start_at : now(),
                'value' => [
                    'currency' => 'GBP',
                    'amount' => (string)$currentValue,
                ],
            ],
            'purchasePrice' => [
                'currency' => 'GBP',
                'amount' => (string)$currentValue,
            ],
            'purchasedOn' => $asset->start_at ? $asset->start_at : null,
            'owners' => [
                [
                    'id' => $client->io_id,
                ],
            ],
        ];

        return $payload;
    }
}
