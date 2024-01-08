<?php
namespace App\Services;


use Illuminate\Support\Facades\Log;
use Waw\Io\Io;

class FactFindSectionDataService
{
    //get the options for example form. This is designed as an example of how these requests should be processed. (single client)
    public static function get():array
    {
        return [
            'enums' => [
                'titles' => config('enums.client.title')
            ],
            'model' => $this->client->presenter()->formatForExampleForm(),
            'submit_method' => 'put',
            'submit_url' => '/client/' . $this->client->io_id . '/example'
        ];
    }
}
