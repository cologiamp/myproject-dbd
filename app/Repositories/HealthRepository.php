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

        $healthInfo = Health::where('client_id',$this->client->id)->first();


        if(!$healthInfo){
            Health::create(array_merge($data,['client_id'=> $this->client->id]));
        }
        else{
            $healthInfo->update($data);
        }
    }


}
