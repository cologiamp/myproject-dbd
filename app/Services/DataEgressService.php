<?php
namespace App\Services;


use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class DataEgressService
{
    use ParsesIoClientData;

    /**
     * @param $io_id
     * @return Collection
     * @throws \Exception
     */
    public function updateClient(Client $client): bool
    {
        $i = new Io();

        $data = [
            'person' => $this->unparseClientData($client),
            'currentAdviser' => [
                'id' => $client->adviser->io_id,
                'name' => $client->adviser->name
            ]
        ];
        try{
           $i->updateClient($client->io_id, $data);

           //we must check if anything actually changed and if we need to update these details.
           $existingContactDetails = collect($i->getContactDetails($client->io_id)['items']);
            $telephone = $existingContactDetails->where('type','Telephone')->first();
            $email = $existingContactDetails->where('type','Email')->first();
            if($telephone != null)
            {
               if($client->phone_number != $telephone['value'])
               {
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
            if($email != null)
            {
                if($client->email_address != $email['value'])
                {
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

        }
        catch(\Exception $e)
        {
            Log::critical('Failed to update IO.');
            throw $e;
        }
        return true;
    }


}
