<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use App\Models\Liability;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LiabilityRepository extends BaseRepository
{
    protected Client $client;
    protected Liability $liability;

    public function __construct(Client $client, Liability $liability)
    {
        $this->client = $client;
        $this->liability = $liability;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setLiability(Liability $liability): void
    {
        $this->liability = $liability;
    }

    //Create the model
    public function create(Request $request): Liability
    {
        return $this->liability->create(
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
        $this->liability->update($request->safe());
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
            $this->liability->update($array);
        } catch (Exception $e){
            Log::warning($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //detach liability from client_liability and delete from model
        $this->liability->clients()->each(function ($item) {
            $item->liabilities()->detach([$this->liability->id]);
        });

        $this->liability->delete();
    }

    public function createOrUpdateLiabilityDetails(mixed $data):void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        collect($data['liabilities'])->each(function ($liability) {

            if(array_key_exists('owner',$liability) && $liability['owner'] != null)
            {
                $owner = $liability['owner'];
                unset($liability['owner']);
            }
            else{
                $owner = $this->client->io_id;
            }


            if(array_key_exists('id', $liability)) {
                $model = Liability::where('id', $liability['id'])->first();

                DB::beginTransaction();

                try {
                    //update liability on [liabilities] table
                    $formatLiabilityData = array(
                        'type' => $liability['type'],
                        'is_repayment' => $liability['repayment'],
                        'amount_outstanding' => $liability['amount_outstanding'],
                        'monthly_repayment' => $liability['monthly_repayment'],
                        'lender' => $liability['lender'],
                        'ends_at' => $liability['ends_at'],
                        'is_to_be_repaid' => $liability['is_to_be_repaid'],
                        'repay_details' => $liability['repay_details'],
                        'interest_rate' => $liability['interest_rate']
                    );

                    $model->update($formatLiabilityData);

                } catch (\Exception $e) {
                    DB::rollback();
                    throw new \Exception($e);
                }

                DB::commit();
            } else {
                //register liability
                $model = $this->registerLiability($liability);
            }

            if($owner != null)
            {
                $model->clients()->detach();
                if($owner == 'Both')
                {
                    $client = $this->client;
                    $client2 = $this->client->client_two;
                    if($client && $client2)
                    {
                        $model->clients()->attach( $client->id);
                        $model->clients()->attach( $client2->id);
                    }
                    else{
                        dd('both but not client two');
                    }
                }
                else{
                    //case where 100% owned by one client, no percentages
                    $client = Client::where('io_id',$owner)->first();
                    $client->liabilities()->attach($model->id);
                }
            }
            else{
                $client = $this->client;
                $client->liabilities()->attach($model->id);
            }
        });

    }

    public function registerLiability(array $liability)
    {
        $liabilityData = array(
            'type' => $liability['type'],
            'is_repayment' => $liability['repayment'],
            'amount_outstanding' => $liability['amount_outstanding'],
            'monthly_repayment' => $liability['monthly_repayment'],
            'lender' => $liability['lender'],
            'ends_at' => $liability['ends_at'],
            'is_to_be_repaid' => $liability['is_to_be_repaid'],
            'repay_details' => $liability['repay_details'],
            'interest_rate' => $liability['interest_rate'],
        );
        try {
            $model = $this->liability->create($liabilityData);
        } catch (Exception $e) {
            Log::warning($e);
        }

        return $model;
    }
}
