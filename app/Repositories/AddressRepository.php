<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use App\Models\Address;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AddressRepository extends BaseRepository 
{
    //FactFind://to do - make sure this works for your form
    /**
     * Function to work out the progress % for each section.
     * @param $key
     * @return int
     */
    // public function calculateFactFindElementProgress(int $section):int
    // {
        // $progress = collect(config('navigation_structures.factfind.' . $section . '.sections'))->map(function ($section){
        // if(array_key_exists('fields',$section) && count($section['fields']) > 0)
        // {
        //                 return collect($section['fields'])->flatten()->groupBy(fn($item) => explode('.',$item)[0])->map(function ($value, $key){
        // return match ($key) {
        // 'addresses' => Address::where("client_id", $this->client->id)->select([...$value])->first()->toArray(),
        // //                        '//todo write join query here for other places data ends up'.
        // default => collect([]),
        // };
        // });
        // }
        //             else return collect([]);
        // })->flatten();
        // if ($progress->count() === 0) return 0;
        // return $progress->filter(fn($element) => $element !== null)->count() / $progress->count() * 100;
    // }
}