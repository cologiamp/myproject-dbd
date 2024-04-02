<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientAddresses
{
    use ParsesIoClientData;

    public function updateAddresses(Client $client): bool
    {
        $i = new Io();

        // Get all dependent details records for the client
        $addresses = $client->addresses;

        $addresses->each(function ($address) use ($i, $client) {
            $data = [
                'residencyStatus' => $address->residency_status,
                'type' => 'Home', // TODO - not captured in AdviserHub
                'residentFrom' => $address->date_from,
                'status' => $address->residency_status,
                'address' => [
                    'line1' => $address->address_line_1,
                    'line2' => $address->address_line_2,
                    'locality' => $address->city,
                    'county' => [
                        'code' => null // TODO 6-letter code (ISO 3166-2:GB) for UK counties. Current county field is free-type
                    ],
                    'postalCode' => $address->postcode,
                    'country' => [
                        'code' => config("enums.client.iso_2_int")[$address->country]
                    ],
                ],
            ];

            try {
                if ($address->io_id === null) {
                    $address->io_id = $i->createAddress($client->io_id, $data);
                    $address->save();
                } else {
                    $i->updateAddress($client->io_id, $address->io_id, $data);
                }

            } catch (\Exception $e) {
                Log::error('Failed to update address details for client: ' . $client->id);
                Log::error($e->getMessage());
                return false;
            }
        });

        return true;
    }
}
