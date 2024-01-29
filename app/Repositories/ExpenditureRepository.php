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
            dd($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->expenditure->delete();
    }

    public function createOrUpdateExpenditureDetails(mixed $data):void
    {
        dd($data);
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        $syncExpenditures = [];
        collect($data['expenditures'])->each(function ($expenditure) use (&$syncExpenditures) {
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
                        'ends_at' => $expenditure['ends_at']
                    );

                    $model->update($formatExpenditureData);

                } catch (\Exception $e) {
                    DB::rollback();
                    throw new \Exception($e);
                }

                DB::commit();
                
                $syncExpenditures[$model->id] = [
                    'expenditure_id' => $expenditure['expenditure_id']
                ];
            } else {
                //register income
                $ret = $this->registerExpenditure($expenditure);
                // dd($ret);
                $syncExpenditures[$ret['id']] = $ret['value'];
            }

        });

        //do sync on all the income records updated/registered
        $this->client->expenditures()->sync($syncExpenditures);

    }

    public function registerExpenditure(array $expenditure) 
    {
        $expenditureData = array(
            'type' => $expenditure['expenditure_type'],
            'description' => $expenditure['description'],
            'amount' => $expenditure['amount'],
            'frequency' => $expenditure['frequency'],
            'starts_at' => $expenditure['starts_at'],
            'ends_at' => $expenditure['ends_at']
        );
        try {
            $model = $this->expenditure->create($expenditureData);
        } catch (Exception $e) {
            dd($e);
        }

        return [
            'id' => $model['id'],
            'value' => [
                'expenditure_id' => $model['id']
            ]
        ];

    }
}
