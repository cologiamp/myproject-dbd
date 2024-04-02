<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientDependents
{
    use ParsesIoClientData;

    public function updateDependents(Client $client): bool
    {
        $i = new Io();

        // Get all dependent details records for the client
        $dependents = $client->dependents;

        $dependents->each(function ($dependent) use ($i, $client) {
            $data = [
                'name' => $dependent->name,
                'dateOfBirth' => $dependent->born_at,
                'isLivingWith' => $dependent->is_living_with_clients,
                'isFinanciallyDependant' => $dependent->financial_dependent,
                'financialDependencyEndsOn' => $dependent->financially_dependent_until,
                'relationshipType' => $dependent->pivot->relationship_type,
                'clients' => [
                    [
                        'id' => $client->io_id,
                    ]
                ]
            ];

            try {
                if ($dependent->pivot->io_id === null) {
                    $client->dependents()->updateExistingPivot($dependent->id, [
                        'io_id' => $i->createDependant($client->io_id, $data)
                    ]);
                    $dependent->save();
                } else {
                    $i->updateDependant($client->io_id, $dependent->pivot->io_id, $data);
                }

            } catch (\Exception $e) {
                Log::error('Failed to update dependent details for client: ' . $client->id);
                Log::error($e->getMessage());
                return false;
            }
        });

        return true;
    }
}
