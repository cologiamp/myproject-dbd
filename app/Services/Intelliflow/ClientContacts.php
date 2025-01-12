<?php

namespace App\Services\Intelliflow;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class ClientContacts
{
    use ParsesIoClientData;

    public function updateClientContacts(Client $client): bool
    {
        $i = new Io();

        $data = [
            'person' => $this->unparseClientData($client),
            'currentAdviser' => [
                'id' => $client->adviser->io_id,
                'name' => $client->adviser->name
            ]
        ];
        try {
            $i->updateClient($client->io_id, $data);

            //we must check if anything actually changed and if we need to update these details.
            $existingContactDetails = collect($i->getContactDetails($client->io_id)['items']);
            $telephone = $existingContactDetails->where('type', 'Telephone')->first();
            $mobile = $existingContactDetails->where('type', 'Mobile')->first();
            $email = $existingContactDetails->where('type', 'Email')->first();

            if ($telephone != null) {
                if ($client->phone_number != $telephone['value']) {
                    $i->updateContactDetails(
                        clientId: $client->io_id,
                        contactDetailId: $telephone['id'],
                        payload: [
                            'type' => 'Telephone',
                            'value' => $client->phone_number
                        ]
                    );
                }
            }

            if ($mobile != null) {
                if ($client->mobile_number != $mobile['value']) {
                    $i->updateContactDetails(
                        clientId: $client->io_id,
                        contactDetailId: $telephone['id'],
                        payload: [
                            'type' => 'Mobile',
                            'value' => $client->mobile_number
                        ]
                    );
                }
            }

            if ($email != null) {
                if ($client->email_address != $email['value']) {
                    $i->updateContactDetails(
                        clientId: $client->io_id,
                        contactDetailId: $email['id'],
                        payload: [
                            'type' => 'Email',
                            'value' => $client->email_address
                        ]
                    );
                }
            }

        } catch (\Exception $e) {
            Log::critical('Failed to update IO.');
            throw $e;
        }
        return true;
    }
}
