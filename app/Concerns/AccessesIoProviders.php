<?php

namespace App\Concerns;

use App\Jobs\CacheProviders;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait AccessesIoProviders{

    /**
     * Accesses the cache and returns a list of providers if we have any, if we don't, dispatches the queue job to make sure they're there later
     */
    private function getProviders()
    {
        return collect(Cache::get('io_provider_list',function (){
            CacheProviders::dispatch();
            return [];//need to have a default.
        }))->map(function ($item,$key){
            return [
                'label' => $item,
                'value' => $key
            ];
        });
    }
}
