<?php

namespace App\Concerns;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

trait AccessesTaxAllowances{
    use InterractsWithDataHub;
    /**
     * Accesses the cache and returns a list of providers if we have any, if we don't, dispatches the queue job to make sure they're there later
     */
    private function getTaxYears()
    {
        Cache::forget('tax_year_allowances');
        return collect(Cache::get('tax_year_allowances',function (){
            $token = Cache::get("datahub_access_token", function () {
                return $this->setAccessToken();
            });
            $response = Http::withHeader("Authorization", $token)->get(config('datahub.url') . "api/tax-years");
            $tax_year_allowances =  collect($response->json()['data'])->mapWithKeys(function ($item){
                return [$item['tax_year'] => $item['allowance']];
            });
            Cache::forever('tax_year_allowances',$tax_year_allowances);
            return $tax_year_allowances;
        }))->map(function ($key,$item){
            return [
                'tax_year' => $item,
                'allowance' => $key
            ];
        });
    }
}
