<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientIncome
{
    use ParsesIoClientData;

    public function updateClientIncome(Client $client): bool
    {
        $i = new Io();

        $incomes = $client->incomes;

        $incomes->each(function($income) use ($i, $client) {
            $data = [
                'category' => config("enums.incomes.income_type.{$income->category}"),
                'frequency' => config("enums.incomes.frequency.{$income->frequency}"),
                'description' => $income->description,
                'gross' => [
                    'amount' => (string) $income->gross_amount,
                    'currency' => 'GBP',
                ],
                'net' => [
                    'amount' => (string) $income->net_amount,
                    'currency' => 'GBP',
                ],
                'startsOn' => $income->starts_at ? $income->starts_at->format('Y-m-d') : null,
                'endsOn' => $income->ends_at ? $income->ends_at->format('Y-m-d') : null,
                'lastUpdated' => $income->updated_at ? $income->updated_at->format('Y-m-d') : null,
            ];

            try {
                if ($income->pivot->io_id === null) {
                    $client->incomes()->updateExistingPivot($client->id, [
                        'io_id' => $i->createIncome($client->io_id, $data)
                    ]);
                    $income->save();
                } else {
                    $i->updateIncome($client->io_id, $income->pivot->io_id, $data);
                }
            } catch (\Exception $e) {
                Log::error('Failed to update income details for client: ' . $client->id);
                Log::error($e->getMessage());
                return false;
            }
        });

        return true;
    }
}
