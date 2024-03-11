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
        ray('making datahub api call')->orange();
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

    protected function getStat(string $stat, bool $force = false):string
    {
        if(!$force)
        {
            return Cache::get("datahub-" . $stat, function () use ($stat){
                return $this->getStatFromDH($stat);
            });
        }
        return $this->getStatFromDH($stat);
    }
    private function getStatFromDH($stat):string|null
    {
        $response = Http::withHeader("Authorization", Cache::get('datahub_access_token', function (){
            return $this->setAccessToken();
        }))
            ->get(config('datahub.url').'api/stats/'. $stat);
        if($response->status() === 200)
        {
            Cache::set('datahub-'.$stat, $response->json()['data']['value'],'86400');
            return $response->json()['data']['value'];
        }
        else return 'N/A';

    }
}
