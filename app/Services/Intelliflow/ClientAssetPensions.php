<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Concerns\SendIoData;
use App\Models\Client;
use App\Models\PensionScheme;
use App\Models\ShareSaveScheme;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientAssetPensions
{
    use ParsesIoClientData;
    use SendIoData;

    public function updateClientAssetPensions(Client $client): bool
    {
        $assets = $client->pension_schemes()->get();

        $assets->each(function ($asset) use ($client) {
            if ($asset->defined_benefit_pension) {
                $this->dbPension($client, $asset);
            }

            if ($asset->defined_contribution_pension) {
                $this->dcPension($client, $asset);
            }
        });

        return true;
    }

    private function dbPension(
        Client $client,
        PensionScheme $asset
    ): bool
    {
        $pensionData = $asset->defined_benefit_pension;

        // Create plan
        $planPayload = [
            "currency" => "GBP",
            "productName" => $asset->employer,
            "planType" => [
                "name" => config("enums.assets.account_types.0") // TODO Mapping not correct
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
            "discriminator" => "PensionDefinedBenefitPlan",
            "prospectivePensionNoLumpSumTaken" => [
                "currency" => "GBP",
                "amount" => (string) $pensionData->prospective_pension_standard
            ],
            "prospectiveLumpSum" => [
                "currency" => "GBP",
                "amount" => (string) $pensionData->prospective_pcls_standard
            ],
            "standardRetirementAge" => $asset->retirement_age,
        ];

        $asset = $this->createOrUpdatePlan($client, $asset, $planPayload);

        // Set up asset
        $assetPayload = [
            'description' => $asset->employer,
            'plan' => [
                'id' => $asset->plan_io_id,
            ],
            'assetType' => config("enums.assets.types.11"), // TODO Is "Investments" correct?
            'owners' => [
                [
                    'id' => $client->io_id,
                ],
            ],
        ];

        $asset = $this->createOrUpdateAsset($client, $asset, $assetPayload);

        return true;
    }

    private function dcPension(
        Client $client,
        PensionScheme $asset
    ): bool
    {
        $pensionData = $asset->defined_contribution_pension;

        $currentValue = $pensionData->value;
        if ($currentValue !== 0) {
            $currentValue /= 100;
        }

        // Create plan
        $planPayload = [
            "currency" => "GBP",
            "productName" => $asset->employer,
            "planType" => [
                "name" => config("enums.assets.account_types.0") // TODO Mapping not correct
            ],
            "productProvider" => [
                "id" => $pensionData->administrator,
            ],
            "policyNumber" => $pensionData->policy_number,
            "startsOn" => $pensionData->policy_start_at ? $asset->policy_start_at : null,
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
            "discriminator" => "PensionContributionDrawdownPlan",
            "crystallisationStatus" => config("enums.assets.pension_crystallised_statuses.{$pensionData->crystallised_status}") ?? null,
            "currentValue" => [
                "valuedOn" => $asset->start_at ? $asset->valuation_at : now(),
                "value" => [
                    'currency' => 'GBP',
                    'amount' => (string)$currentValue,
                ],
            ],
        ];

        $asset = $this->createOrUpdatePlan($client, $asset, $planPayload);

        // Set up personal contributions
        $personalContributionAmount = $pensionData->gross_contribution_absolute;
        if ($personalContributionAmount !== 0) {
            $personalContributionAmount /= 100;
        }

        $personalContributionPayload = [
            "frequency" => config("enums.assets.frequency.{$pensionData->frequency}"),
            "value" => [
                "currency" => "GBP",
                "amount" => (string)$personalContributionAmount
            ],
            "contributionType" => "Regular",
            "contributorType" => "Self"
        ];

        $asset = $this->createOrUpdateContribution($client, $asset, $personalContributionPayload, 'personal_contribution_io_id');

        // Set up employer contributions
        $employerContributionAmount = $pensionData->employer_contribution_absolute;
        if ($employerContributionAmount !== 0) {
            $employerContributionAmount /= 100;
        }

        $employerContributionPayload = [
            "frequency" => config("enums.assets.frequency.{$pensionData->frequency}"),
            "value" => [
                "currency" => "GBP",
                "amount" => (string)$employerContributionAmount
            ],
            "contributionType" => "Regular",
            "contributorType" => "Self"
        ];

        $asset = $this->createOrUpdateContribution($client, $asset, $employerContributionPayload, 'employer_contribution_io_id');

        // Set up asset
        $assetPayload = [
            'description' => $asset->employer,
            'plan' => [
                'id' => $asset->plan_io_id,
            ],
            'assetType' => config("enums.assets.types.11"), // TODO Is "Investments" correct?
            'owners' => [
                [
                    'id' => $client->io_id,
                ],
            ],
        ];

        $assetId = $this->createOrUpdateAsset($client, $asset, $assetPayload);

        return true;
    }
}
