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
            'person' => $this->unparseClientData($client)
        ];
        try{
           $i->updateClient($client->io_id, $data);

           //we must check if anything actually changed and if we need to update these details.
           $existingContactDetails = $i->getContactDetails($client->io_id)['items'];

           $cdid = 0; //todo

           $i->updateContactDetails(
               clientId: $client->io_id,
               contactDetailId: $cdid,
               payload: [
                   'type' => 'Telephone',
                   'value' => $client->phone_number
               ]
            );
        }
        catch(\Exception $e)
        {
            Log::critical('Failed to update IO.');
            throw $e;
        }
        return true;
    }


}
