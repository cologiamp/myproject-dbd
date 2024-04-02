<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientHealth
{
    use ParsesIoClientData;

    public function updateHealth(Client $client): bool
    {
        $i = new Io();

        $health = $client->health;

        $data = [
            'healthProfile' => [
                'isSmoker' => $health->smoker,
                'smokedInLast12Months' => $health->smoked_in_last_12_months,
                'inGoodHealth' => $health->is_in_good_health,
            ]
        ];

        try {
            $i->updateClient($client->io_id, $data);
        } catch (\Exception $e) {
            Log::error('Failed to update health details for client: ' . $client->id);
            Log::error($e->getMessage());
            return false;
        }

        return true;
    }
}
