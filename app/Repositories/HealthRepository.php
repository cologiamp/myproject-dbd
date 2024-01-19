<?php
namespace App\Repositories;

use App\Models\Client;
use App\Models\Health;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class HealthRepository extends BaseRepository 
{
    protected Client $client;
    protected Health $health;

    public function __construct(Client $client, Health $health)
    {
        $this->client = $client;
        $this->health = $health;   
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setHealth(Health $health): void
    {
        $this->health = $health;
    }

    //Create the model
    public function create(Request $request): Health
    {
        return $this->health->create(
            array_merge($request->safe()->all(),
                []
            ));
    }

    //Update the given details
    /**
     * This method uses a generecised from request to update the model.
     * Use updateFromValidate if you have already run a Validator on your data.
     * @param Request $request
     * @return void
     */
    public function update(Request $request): void
    {
        $this->health->update($request->safe());
    }

    /**
     * This method uses a pre-validated updated data array rather than a formRequest to update the model.
     * Do not use unless you have validated your data
     * @param array $array
     * @return void
     */
    public function updateFromValidated(array $array):void
    {
        try {
            $this->health->update($array);
        } catch (Exception $e){
            dd($e);
        }
        
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->health->delete();
    }

    public function createOrUpdateHealthDetails(mixed $data):void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        $healthInfo = Health::where('client_id',$data['client_id'])->first();

        if(!$healthInfo){
            $healthInfo = Health::create($data);
        }
        else{
            $healthInfo->update($data);
        }
    }

    //FactFind://to do - make sure this works for your form
    /**
     * Function to work out the progress % for each section.
     * @param $key
     * @return int
     */
    public function calculateFactFindElementProgress(int $section):int
    {
        $progress = collect(config('navigation_structures.factfind.' . $section . '.sections'))->map(function ($section){
        if(array_key_exists('fields',$section) && count($section['fields']) > 0)
        {
                        return collect($section['fields'])->flatten()->groupBy(fn($item) => explode('.',$item)[0])->map(function ($value, $key){
        return match ($key) {
        'health' => Health::where("client_id", $this->client->id)->select([...$value])->first()->toArray(),
        //                        '//todo write join query here for other places data ends up'.
        default => collect([]),
        };
        });
        }
                    else return collect([]);
        })->flatten();
        if ($progress->count() === 0) return 0;
        return $progress->filter(fn($element) => $element !== null)->count() / $progress->count() * 100;
    }
    
}