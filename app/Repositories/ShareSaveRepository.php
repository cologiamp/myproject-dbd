<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Asset;
use App\Models\Client;
use App\Models\EmploymentDetail;
use App\Models\OtherInvestment;
use App\Models\ShareSaveScheme;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class  ShareSaveRepository extends BaseRepository
{
    protected ShareSaveScheme $scheme;

    public function __construct(ShareSaveScheme $scheme)
    {
        $this->scheme = $scheme;
    }


    public function setScheme(ShareSaveScheme $scheme): void
    {
        $this->scheme = $scheme;
    }

    //Create the model
    public function create(Request $request): ShareSaveScheme
    {
        return $this->scheme->create(
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
        $this->scheme->update($request->safe());
    }



    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        $this->scheme->delete();
    }

    public function createOrUpdateSchemes(mixed $schemes):void
    {
        if(!is_array($schemes) && $schemes::class == Request::class)
        {
            $investments = $schemes->safe();
        }


        DB::beginTransaction();
        try {
            collect($schemes)->each(function ($scheme)  {
                if(array_key_exists('id', $scheme) && $scheme['id'] != null){
                    $this->scheme = ShareSaveScheme::where('id', $scheme['id'])->first();
                    $this->scheme->update($scheme);
                } else {
                    $this->scheme->create($scheme);
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
