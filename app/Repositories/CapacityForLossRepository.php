<?php
namespace App\Repositories;

use App\Models\Client;
use App\Models\CapacityForLoss;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CapacityForLossRepository extends BaseRepository
{
    protected Client $client;
    protected CapacityForLoss $capacityForLoss;

    public function __construct(Client $client, CapacityForLoss $capacityForLoss)
    {
        $this->client = $client;
        $this->capacityForLoss = $capacityForLoss;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setCapacity(CapacityForLoss $capacityForLoss): void
    {
        $this->capacityForLoss = $capacityForLoss;
    }

    //Create the model
    public function create(Request $request): CapacityForLoss
    {
        return $this->capacityForLoss->create(
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
        $this->capacityForLoss->update($request->safe());
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
            $this->capacityForLoss->update($array);
        } catch (Exception $e){
            dd($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->capacityForLoss->delete();
    }

    public function createOrUpdate(mixed $capacity):void
    {
        if(!is_array($capacity) && $capacity::class == Request::class)
        {
            $capacity = $capacity->safe();
        }

        DB::beginTransaction();

        try {
            $capacity['client_id'] = $this->client->id;

            if(array_key_exists('id', $capacity) && $capacity['id'] != null){
                $model = CapacityForLoss::where('id', $capacity['id'])->first();

                unset($capacity['id']);
                $model->update($capacity);
            } else {
                CapacityForLoss::create($capacity);
            }

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            throw new \Exception($e);
        }

        DB::commit();
    }
}
