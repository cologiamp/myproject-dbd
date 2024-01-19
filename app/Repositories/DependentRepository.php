<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use App\Models\ClientDependent;
use App\Models\Dependent;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class DependentRepository extends BaseRepository 
{
    protected Client $client;
    protected ClientDependent $clientDependent;
    protected Dependent $dependent;

    public function __construct(Client $client, ClientDependent $clientDependent, Dependent $dependent)
    {
        $this->client = $client;
        $this->clientDependent = $clientDependent;
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

    public function setClientDependent(ClientDependent $clientDependent): void
    {
        $this->clientDependent = $clientDependent;
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

    public function createOrUpdateDependentDetails(int $client_id, mixed $data):void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        $clientsDependent = ClientDependent::where('client_id',$client_id)->get();
        ray($clientsDependent);

        if($clientsDependent->count() > 0){
            $clientsDependent->each(function ($cdependent) use ($data) {
                $dependentData = Dependent::where('id',$cdependent->dependent_id)->first();

                DB::beginTransaction();

                try {
                    //register new dependent on [dependents] table
                    $formatData = array(
                        'name' => null,
                        'born_at' => $data['born_at'],
                        'financial_dependent' => $data['financial_dependent'],
                        'is_living_with_clients' => $data['is_living_with_clients']
                    );
        
                    $dependentData->update($formatData);

                    $cformatData = array(
                        'client_id' => $cdependent->client_id,
                        'dependent_id' => $cdependent->dependent_id,
                        'relationship_type' => $data['relationship_type']
                    );

                    $cdependent->update($cformatData);

                } catch (\Exception $e) {
                    DB::rollback();
                    throw new \Exception($e);
                }

                DB::commit();
            });
        }
        else {
            $this->createNewDependentRecord($client_id, $data);
        }
    }

    public function createNewDependentRecord(int $client_id, mixed $data):void
    {
        DB::beginTransaction();

            try {
                //register new dependent on [dependents] table
                $dependentData = array(
                    'born_at' => $data['born_at'],
                    'financial_dependent' => $data['financial_dependent'],
                    'is_living_with_clients' => $data['is_living_with_clients']
                );
    
                $newDependentId = $this->dependent->create($dependentData)->id;
    
                //register new dependent on [client_dependent] table
                $clientDependentData = array(
                    'client_id' => $client_id,
                    'dependent_id' => $newDependentId,
                    'relationship_type' => $data['relationship_type']
                );
                
                $this->clientDependent->create($clientDependentData)->id;

            } catch (\Exception $e) {
                DB::rollback();
                throw new \Exception($e);
            }

            DB::commit();
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