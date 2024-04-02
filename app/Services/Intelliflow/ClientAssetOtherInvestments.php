<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Concerns\SendIoData;
use App\Models\Client;
use App\Models\OtherInvestment;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientAssetOtherInvestments
{
    use ParsesIoClientData;
    use SendIoData;

    public function updateClientAssetOtherInvestments(Client $client): bool
    {
        $i = new Io;

        $assets = $client->other_investments()->get();

        $assets->each(function ($asset) use ($client, $i) {
            $currentValue = (int)$asset->current_value > 0 ? ($asset->current_value / 100) : 0;

            // Set up a plan
            $planPayload = $this->buildPlanData($client, $asset);
            $planId = $this->createOrUpdatePlan($client, $asset, $planPayload);

            // Set up a contribution
            $contributionPayload = $this->buildContributionData($client, $asset);
            $asset = $this->createOrUpdateContribution($client, $asset, $contributionPayload);

            $data = $this->buildAssetData($client, $asset, $currentValue);
            $asset = $this->createOrUpdateAsset($client, $asset, $data);
        });

        return true;
    }

    private function buildPlanData(
        Client $client,
        OtherInvestment  $asset
    ): array
    {
        $payload = [
            "currency" => "GBP",

            "productName" => $asset->product_name,
            "startsOn" => $asset->start_date ? $asset->start_date : null,
            "endsOn" => $asset->maturity_date ? $asset->maturity_date : null,

            "planType" => [
                "name" => config("enums.assets.account_types.{$asset->contract_type}")
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

            "discriminator" => "InvestmentPlan"
        ];

        return $payload;
    }

    private function buildContributionData(
        Client $client,
        OtherInvestment  $asset
    ): array
    {
        $contributionAmount = (float)$asset->regular_contribution;
        if ($contributionAmount !== 0) {
            $contributionAmount /= 100;
        }

        $payload = [
            "frequency" => config("enums.assets.frequency.{$asset->frequency}"),
            "value" => [
                "currency" => "GBP",
                "amount" => (string)$contributionAmount
            ],
            "startsOn" => $asset->start_date ? $asset->start_date : null,
            "endsOn" => $asset->maturity_date ? $asset->maturity_date : null,
            "contributionType" => "Regular",
            "contributorType" => "Self"
        ];

        return $payload;
    }

    private function buildAssetData(
        Client $client,
        OtherInvestment  $asset,
        float  $currentValue,
    ): array
    {
        $payload = [
            'description' => $asset->product_name,
            'plan' => [
                'id' => $asset->plan_io_id,
            ],
            'assetType' => config("enums.assets.types.11"), // TODO Is "Investments" correct?
            'originalValue' => [
                'valuedOn' => $asset->start_date ? $asset->start_date : now(),
                'value' => [
                    'currency' => 'GBP',
                    'amount' => (string)$currentValue,
                ],
            ],
            'purchasePrice' => [
                'currency' => 'GBP',
                'amount' => (string)$currentValue,
            ],
            'currentValue' => [
                // TODO No current value date, this probably needs considering
                'valuedOn' => $asset->start_date ? $asset->start_date : now(),
                'value' => [
                    'currency' => 'GBP',
                    'amount' => (string)$currentValue,
                ],
            ],
            'purchasedOn' => $asset->start_date ? $asset->start_date : null,
            'owners' => [
                [
                    'id' => $client->io_id,
                ],
            ],
        ];

        return $payload;
    }
}
