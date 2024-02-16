<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use App\Models\Dependent;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DependentRepository extends BaseRepository
{
    protected Client $client;
    protected Dependent $dependent;

    public function __construct(Client $client, Dependent $dependent)
    {
        $this->client = $client;
        $this->dependent = $dependent;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setDependent(Dependent $dependent): void
    {
        $this->dependent = $dependent;
    }

    //Create the model
    public function create(Request $request): Dependent
    {
        return $this->dependent->create(
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
        $this->dependent->update($request->safe());
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
            $this->dependent->update($array);
        } catch (Exception $e){
            dd($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->dependent->delete();
    }

    public function createOrUpdateDependentDetails(mixed $data):void
    {

        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        $syncDependents = [];
        collect($data['dependents'])->each(function ($dependent) use ($data, &$syncDependents) {

            if(array_key_exists('dependent_id', $dependent)) {

                $model = Dependent::where('id', $dependent['dependent_id'])->first();
                $model->update([
                    'name' => $dependent['name'],
                    'born_at' => $dependent['born_at'],
                    'financial_dependent' => $dependent['financial_dependent'],
                    'financially_dependent_until' => $dependent['financially_dependent_until'],
                    'is_living_with_clients' => $dependent['is_living_with_clients']
                ]);

                $syncDependents[$model->id] = ['relationship_type' => $dependent['relationship_type']];
            } else {

                $model = Dependent::create([
                    'name' => $dependent['name'],
                    'born_at' => $dependent['born_at'],
                    'financial_dependent' => $dependent['financial_dependent'],
                    'financially_dependent_until' => $dependent['financially_dependent_until'],
                    'is_living_with_clients' => $dependent['is_living_with_clients']
                ]);


                $syncDependents[$model->id] = ['relationship_type' => $dependent['relationship_type']];
            }

        });

        //do sync on all the dependent records updated/registered
        $this->client->dependents()->sync($syncDependents);

    }


}
