<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Asset;
use App\Models\Client;
use App\Models\EmploymentDetail;
use App\Models\OtherInvestment;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InvestmentRepository extends BaseRepository
{
    protected Client $client;
    protected OtherInvestment $oi;

    public function __construct(Client $client, OtherInvestment $oi)
    {
        $this->client = $client;
        $this->oi = $oi;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setInvestment(OtherInvestment $oi): void
    {
        $this->$oi = $oi;
    }

    //Create the model
    public function create(Request $request): OtherInvestment
    {
        return $this->oi->create(
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
        $this->oi->update($request->safe());
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
            $this->oi->update($array);
        } catch (Exception $e){
            dd($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        $this->oi->delete();
    }

    public function createOrUpdateInvestments(mixed $investments):void
    {
        if(!is_array($investments) && $investments::class == Request::class)
        {
            $investments = $investments->safe();
        }


        DB::beginTransaction();
        try {
            collect($investments)->each(function ($investment)  {
                if(array_key_exists('id', $investment) && $investment['id'] != null){
                    $this->oi = OtherInvestment::where('id', $investment['id'])->first();
                    $this->oi->update($investment);
                } else {
                    $this->oi->create($investment);
                }
            });

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            throw new \Exception($e);
        }

        DB::commit();
    }
}
