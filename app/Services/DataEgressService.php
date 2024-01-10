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
    public function updateClient($io_id, Client $client): bool
    {
        $i = new Io();

        $data = [
            'person' => $this->unparseClientData($client);
        ];

        try{
           $i->updateClient($io_id, $data);
        }
        catch(\Exception $e)
        {
            Log::critical('Failed to fetch from IO.');
            throw $e;
        }
        return true;
    }

}
