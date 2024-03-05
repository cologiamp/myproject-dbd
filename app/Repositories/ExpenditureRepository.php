<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use App\Models\Expenditure;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenditureRepository extends BaseRepository
{
    protected Client $client;
    protected Expenditure $expenditure;

    public function __construct(Client $client, Expenditure $expenditure)
    {
        $this->client = $client;
        $this->expenditure = $expenditure;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setExpenditure(Expenditure $expenditure): void
    {
        $this->expenditure = $expenditure;
    }

    //Create the model
    public function create(Request $request): Expenditure
    {
        return $this->expenditure->create(
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
        $this->expenditure->update($request->safe());
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
            $this->expenditure->update($array);
        } catch (Exception $e){
            Log::warning($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //detach expenditure from client_expenditure and delete from model
        $this->expenditure->clients()->each(function ($item) {
            $item->expenditures()->detach([$this->expenditure->id]);
        });

        $this->expenditure->delete();


    }

    public function createOrUpdateExpenditureDetails(mixed $data):void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        collect($data['expenditures'])->each(function ($expenditure) {

            if(array_key_exists('expenditure_id', $expenditure)) {
                $model = Expenditure::where('id', $expenditure['expenditure_id'])->first();

                DB::beginTransaction();

                try {
                    //update expenditure on [expenditures] table
                    $formatExpenditureData = array(
                        'type' => $expenditure['expenditure_type'],
                        'description' => $expenditure['description'],
                        'amount' => $expenditure['amount'],
                        'frequency' => $expenditure['frequency'],
                        'starts_at' => $expenditure['starts_at'],
                        'ends_at' => $expenditure['ends_at'] ?? null
                    );

                    $model->update($formatExpenditureData);
                    $model->clients()->detach();

                } catch (\Exception $e) {
                    DB::rollback();
                    throw new \Exception($e);
                }

                DB::commit();
            } else {
                //register expenditure
                $model = $this->registerExpenditure($expenditure);
            }

            if($expenditure['belongs_to'] == null)
            {
                $expenditure['belongs_to'] = $this->client->io_id;
            }
            if($expenditure['belongs_to'] == 'Both'){
                $client = $this->client->id;
                $client2 = $this->client->client_two->id;
                $model->clients()->attach([$client,$client2]);
            }
            else{
                $client = Client::where('io_id',$expenditure['belongs_to'])->first();
                $model->clients()->attach($client->id);
            }

        });
    }

    public function registerExpenditure(array $expenditure)
    {
        $expenditureData = array(
            'type' => $expenditure['expenditure_type'],
            'description' => $expenditure['description'],
            'amount' => $expenditure['amount'],
            'frequency' => $expenditure['frequency'],
            'starts_at' => $expenditure['starts_at'],
            'ends_at' => $expenditure['ends_at'] ?? null
        );
        try {
            $model = $this->expenditure->create($expenditureData);
        } catch (Exception $e) {
            Log::warning($e);
        }

        return $model;
    }
}
