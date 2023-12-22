<?php
namespace App\Services;


use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class DataIngestService
{
    public function getClientsForAdviser($adviser_io_id): \Illuminate\Support\Collection
    {
        $i = new Io();
        $skip = 0;
        $clients = [];
        while($skip >= 0)
        {
            try{
                $io =  $i->getClients(100,$skip,'currentAdviser.id eq '. $adviser_io_id);
                array_push($clients,$io['items']);
                if(array_key_exists('next_href',$io))
                {
                    $params = explode('=',$io['next_href']);
                    $skip = (int) array_pop($params);
                }
                else{
                    $skip = -1; //exit loop
                }
            }
            catch(\Exception $exception)
            {
                Log::critical('Failed to fetch from IO.');
                throw $exception;
            }
        }
        return collect($clients)->flatten(1);
    }
}
