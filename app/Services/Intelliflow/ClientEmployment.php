<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use App\Models\EmploymentDetail;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientEmployment
{
    use ParsesIoClientData;

    public function updateEmployment(Client $client): bool
    {
        $i = new Io();

//        $data = [
//            'employment' => $this->unparseEmploymentData($client),
//        ];

        // Get all employment details records for the client
        $employment = EmploymentDetail::where('client_id', $client->id)->get();

        $employment->each(function ($employment) use ($i, $client) {
            $data = [
                'startsOn' => $employment->start_at,
                'endsOn' => $employment->end_at,
                'occupation' => $employment->occupation,
                'intendedRetirementAge' => $employment->intended_retirement_age,
                'employer' => $employment->employer,
                'employmentStatus' => $employment->employment_status,
            ];

            try {
                if ($employment->io_id === null) {
                    $employment->io_id = $i->createEmployment($client->io_id, $data);
                    $employment->save();
                } else {
                    $i->updateEmployment($client->io_id, $employment->io_id, $data);
                }

            } catch (\Exception $e) {
                Log::error('Failed to update employment details for client: ' . $client->id);
                Log::error($e->getMessage());
                return false;
            }
        });

        return true;
    }
}
