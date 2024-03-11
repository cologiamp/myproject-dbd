<?php

namespace App\Concerns;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

trait InterractsWithDataHub{

    /**
     * retrieves an access token for the advisers API
     */
    private function setAccessToken(): string
    {
        $response = Http::asForm()->post(config('datahub.url') . 'oauth/token', [
            'grant_type' => 'client_credentials',
            'client_id' => config('datahub.client_id'),
            'client_secret' => config('datahub.client_secret'),
            'scope' => '',
        ]);

        $json = $response->json();
        if(array_key_exists("access_token", $json)){
            Cache::set('datahub_access_token',$json['access_token'],'3600');
            return $json['access_token'];
        }
        else{
            throw new Exception('no access token found',422);
        }

    }
}
