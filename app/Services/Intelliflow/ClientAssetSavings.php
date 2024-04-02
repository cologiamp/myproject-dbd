<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Concerns\SendIoData;
use App\Models\Asset;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientAssetSavings
{
    use ParsesIoClientData;
    use SendIoData;

    public function updateClientAssetsSavings(Client $client): bool
    {
        $i = new Io();

        $assets = $client->assets()->where('category', 2)->get();

        $assets->each(function ($asset) use ($i, $client) {
            $originalValue = (int)$asset->original_value > 0 ? ($asset->original_value / 100) : 0;
            $currentValue = (int)$asset->current_value > 0 ? ($asset->current_value / 100) : 0;

            // Set up a plan
            $planPayload = $this->buildPlanData($client, $asset);
            $asset = $this->createOrUpdatePlan($client, $asset, $planPayload);

            // Set up a contribution, if necessary
            if ($asset->regular_contributions) {
                $contributionPayload = $this->buildContributionData($client, $asset);
                $asset = $this->createOrUpdateContribution($client, $asset, $contributionPayload);
            }

            $data = $this->buildAssetData($client, $asset, $originalValue, $currentValue);
            $asset = $this->createOrUpdateAsset($client, $asset, $data);
        });

        return true;
    }

    private function buildPlanData(
        Client $client,
        Asset  $asset
    ): array
    {
        $payload = [
            "currency" => "GBP",

            "productName" => $asset->product_name,
            "startsOn" => $asset->start_at ? $asset->start_at : null,
            "endsOn" => $asset->end_at ? $asset->end_at : null,

            "planType" => [
                "name" => config("enums.assets.account_types.{$asset->account_type}")
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

            "productProvider" => [
                "id" => $asset->provider
            ],

            "interestRate" => $asset->interest_rate ?? 0,
            "discriminator" => "CashBankAccountPlan"
        ];

        return $payload;
    }

    private function buildContributionData(
        Client $client,
        Asset  $asset
    ): array
    {
        $contributionAmount = (float)$asset->contribution_amount;
        if ($contributionAmount !== 0) {
            $contributionAmount /= 100;
        }

        $payload = [
            "frequency" => config("enums.assets.frequency.{$asset->frequency}"),
            "value" => [
                "currency" => "GBP",
                "amount" => (string)$contributionAmount
            ],
            "startsOn" => $asset->start_at ? $asset->start_at : null,
            "endsOn" => $asset->end_at ? $asset->end_at : null,
            "contributionType" => "Regular",
            "contributorType" => "Self"
        ];

        return $payload;
    }

    private function buildAssetData(
        Client $client,
        Asset  $asset,
        float  $originalValue,
        float  $currentValue,
    ): array
    {
        $payload = [
            'description' => $asset->product_name,
            'plan' => [
                'id' => $asset->plan_io_id,
            ],
            'assetType' => config("enums.assets.types.0"), // TODO Should this be something other than "Cash"?
            'originalValue' => [
                'valuedOn' => $asset->start_at ? $asset->start_at : now(),
                'value' => [
                    'currency' => 'GBP',
                    'amount' => (string)$originalValue,
                ],
            ],
            'purchasePrice' => [
                'currency' => 'GBP',
                'amount' => (string)$originalValue,
            ],
            'currentValue' => [
                // TODO No current value date, this probably needs considering
                'valuedOn' => $asset->start_at ? $asset->start_at : now(),
                'value' => [
                    'currency' => 'GBP',
                    'amount' => (string)$currentValue,
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

        return $payload;
    }
}
