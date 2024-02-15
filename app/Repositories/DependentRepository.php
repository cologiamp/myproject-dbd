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

                DB::beginTransaction();

                try {
                    //update dependent on [dependents] table
                    $formatDependentData = array(
                        'name' => $dependent['name'],
                        'born_at' => $dependent['born_at'],
                        'financial_dependent' => $dependent['financial_dependent'],
                        'financially_dependent_until' => $dependent['financially_dependent_until'],
                        'is_living_with_clients' => $dependent['is_living_with_clients']
                    );
                    $model->update($formatDependentData);

                } catch (\Exception $e) {
                    dd($e);
                    throw new \Exception($e);
                }

                DB::commit();

                $syncDependents[$model->id] = ['relationship_type' => $dependent['relationship_type']];
            } else {
                //register dependent
                $ret = $this->registerDependent($dependent);
                $syncDependents[$ret['id']] = $ret['value'];
            }

        });

        //do sync on all the dependent records updated/registered
        $this->client->dependents()->sync($syncDependents);

    }



    public function registerDependent(array $dependent) {

        $dependentData = array(
            'name' => $dependent['name'],
            'born_at' => $dependent['born_at'],
            'financial_dependent' => $dependent['financial_dependent'],
            'financially_dependent_until' => $dependent['financially_dependent_until'],
            'is_living_with_clients' => $dependent['is_living_with_clients']
        );
        try{
            $model = Dependent::create($dependentData);
        }catch (Exception $e)
        {
            dd($e);
        }



        return [
            'id' => $model['id'],
            'value' => ['relationship_type' => $dependent['relationship_type']]
        ];

    }

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
        // 'dependents' => Dependent::where("client_id", $this->client->id)->select([...$value])->first()->toArray(),
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
