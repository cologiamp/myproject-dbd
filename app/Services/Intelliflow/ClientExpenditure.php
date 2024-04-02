<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientExpenditure
{
    use ParsesIoClientData;

    public function updateClientExpenditure(Client $client): bool
    {
        $i = new Io();

        $expenditures = $client->expenditures;

        $expenditures->each(function($expenditure) use ($i, $client) {
            $category = null;

            // Get expenditure type from enum
            $expenditureGroups = config('enums.expenditures');
            foreach ($expenditureGroups as $expenditureGroup) {
                if (isset($expenditureGroup[$expenditure->type])) {
                    $category = $expenditureGroup[$expenditure->type];
                    break;
                }
            }

            $data = [
                'category' => $category,
                'frequency' => config("enums.incomes.frequency.{$expenditure->frequency}"), // TODO Should there be an expenditure frequency enum?
                'description' => $expenditure->description,
                'net' => [
                    'amount' => (string) $expenditure->amount,
                    'currency' => 'GBP',
                ],
                'startsOn' => $expenditure->starts_at ? $expenditure->starts_at->format('Y-m-d') : null,
                'endsOn' => $expenditure->ends_at ? $expenditure->ends_at->format('Y-m-d') : null,
                'owners' => [
                    [
                        'id' => $client->io_id
                    ],
                ],
            ];

            try {
                if ($expenditure->io_id === null) {
                    $expenditure->io_id = $i->createExpenditure($client->io_id, $data);
                    $expenditure->save();
                } else {
                    $i->updateExpenditure($client->io_id, $expenditure->io_id, $data);
                }
            } catch (\Exception $e) {
                Log::error('Failed to update expenditure details for client: ' . $client->id);
                Log::error($e->getMessage());
                return false;
            }
        });

        return true;
    }
}
