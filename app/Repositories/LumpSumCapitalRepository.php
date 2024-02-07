<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Asset;
use App\Models\Client;
use App\Models\EmploymentDetail;
use App\Models\OtherInvestment;
use App\Models\LumpSumCapital;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class  LumpSumCapitalRepository extends BaseRepository
{
    protected LumpSumCapital $capital;

    public function __construct(LumpSumCapital $capital)
    {
        $this->capital = $capital;
    }


    public function setCapital(LumpSumCapital $capital): void
    {
        $this->capital = $capital;
    }

    //Create the model
    public function create(Request $request): LumpSumCapital
    {
        return $this->capital->create(
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
        $this->capital->update($request->safe());
    }



    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        $this->capital->clients()->each(function ($item) {
            $item->lump_sum_capitals()->detach([$this->capital->id]);
        });
        $this->capital->delete();
    }

    public function createOrUpdateCapitals(mixed $capitals):void
    {
        if(!is_array($capitals) && $capitals::class == Request::class)
        {
            $capitals = $capitals->safe();
        }


        DB::beginTransaction();
        try {
            collect($capitals)->each(function ($capital)  {
                if(array_key_exists('owner',$capital) && $capital['owner'] != null)
                {
                    $owner = $capital['owner'];
                    unset($capital['owner']);
                }
                else{
                    $owner = null;
                }

                if(array_key_exists('id', $capital) && $capital['id'] != null){
                    $this->capital = LumpSumCapital::where('id', $capital['id'])->first();
                    $this->capital->update($capital);
                    $model = $this->capital;
                } else {
                    $model = $this->capital->create($capital);
                }

                if($owner)
                {
                    $model = $model->fresh();
                    if($owner == 'Both')
                    {
                        dd('need to handle the case where both clients own a lump sum');
                    }
                    else{
                        $client = Client::with('lump_sum_capitals')->where('io_id',$owner)->first();
                        if(collect($client->lump_sum_capitals->pluck('id'))->doesntContain($model->id))
                        {
                            $client->lump_sum_capitals()->attach($model->id);
                        }
                    }
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
