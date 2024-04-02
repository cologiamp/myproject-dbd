<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientData
{
    use ParsesIoClientData;

    public function updateClientData(Client $client): bool
    {
        $i = new Io();

        $data = [
            'name' => $client->name,
            'created_at' => $client->created_at,

            'currentAdviser' => [
                'id' => $client->adviser->io_id ?? null,
            ],

            'person' => [
                'salutation' => $client->salutation,
                'title' => $client->formatted_title,
                'firstName' => $client->first_name,
                'middleName' => $client->middle_name,
                'lastName' => $client->last_name,
                'dateOfBirth' => $client->date_of_birth,
                'gender' => $client->gender,
                'maritalStatus' => config("enums.client.marital_status.{$client->marital_status}"),
                'niNumber' => $client->ni_number,
                'hasWill' => $client->valid_will ?? false,
                'isWillUptoDate' => !$client->valid_will ? null : true, // IO expects null if not hasWill is false
                'isPowerOfAttorneyGranted' => $client->poa_granted ?? false,
                // TODO If poa_granted, add POA name
            ],
        ];

        if ($client->nationality !== null) { // "British" is 0, explicitly check for value, so it's not incorrectly missed
            // TODO Looks like we need to use a COUNTRY ISO code, is there a map from nationality to country?
            $data['person']['nationalityCountry']['isoCode'] = config("enums.client.nationalityISO.{$client->nationality}");
        }

        if ($client->country_of_residence) {
            // TODO Does not seem to pull through to IO
            $data['territorialProfile']['countryOfResidence']['code'] = config("enums.client.iso_2_int")[$client->country_of_residence];
        }

        if ($client->country_of_domicile) {
            // TODO Does not seem to pull through to IO
            $data['territorialProfile']['countryOfDomicile']['code'] = config("enums.client.iso_2_int")[$client->country_of_domicile];
        }

        try {
            if ($client->io_id === null) {
                // TODO Do we need to create a new client here?
            } else {
                $i->updateClient($client->io_id, $data);
            }

        } catch (\Exception $e) {
            Log::error('Failed to update dependent details for client: ' . $client->id);
            Log::error($e->getMessage());
            return false;
        }

        return true;
    }
}
