<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use App\Models\Income;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class IncomeRepository extends BaseRepository
{
    protected Client $client;
    protected Income $income;

    public function __construct(Client $client, Income $income)
    {
        $this->client = $client;
        $this->income = $income;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setIncome(Income $income): void
    {
        $this->income = $income;
    }

    //Create the model
    public function create(Request $request): Income
    {
        return $this->income->create(
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
        $this->income->update($request->safe());
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
            $this->income->update($array);
        } catch (Exception $e){
            dd($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->income->delete();
    }

    public function createOrUpdateIncomeDetails(mixed $data):void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        $syncIncomes = [];
        collect($data['incomes'])->each(function ($income) use (&$syncIncomes) {
            if(array_key_exists('income_id', $income)) {
                $model = Income::where('id', $income['income_id'])->first();

                DB::beginTransaction();

                try {
                    //update income on [incomes] table
                    $formatIncomeData = array(
                        'category' => $income['income_type'],
                        'gross_amount' => $income['gross_amount'],
                        'net_amount' => $income['net_amount'],
                        'expenses' => $income['expenses'],
                        'frequency' => $income['frequency'],
                        'ends_at' => $income['ends_at']
                    );

                    $model->update($formatIncomeData);

                } catch (\Exception $e) {
                    DB::rollback();
                    throw new \Exception($e);
                }

                DB::commit();
                
                $syncIncomes[$model->id] = [
                    'is_primary' => $income['is_primary']
                ];
            } else {
                //register income
                $ret = $this->registerIncome($income);
                $syncIncomes[$ret['id']] = $ret['value'];
            }

        });

        //do sync on all the income records updated/registered
        $this->client->incomes()->sync($syncIncomes);

    }

    public function registerIncome(array $income) {

        $incomeData = array(
            'category' => $income['income_type'],
            'gross_amount' => $income['gross_amount'],
            'net_amount' => $income['net_amount'],
            'expenses' => $income['expenses'],
            'frequency' => $income['frequency'],
            'ends_at' => $income['ends_at']
        );
        try {
            $model = $this->income->create($incomeData);
        } catch (Exception $e) {
            dd($e);
        }

        return [
            'id' => $model['id'],
            'value' => [
                'is_primary' => $income['is_primary']
            ]
        ];

    }
}
