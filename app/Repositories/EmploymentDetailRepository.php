<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Client;
use App\Models\EmploymentDetail;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class EmploymentDetailRepository extends BaseRepository
{
    protected Client $client;
    protected EmploymentDetail $employmentDetail;

    public function __construct(Client $client, EmploymentDetail $employmentDetail)
    {
        $this->client = $client;
        $this->employmentDetail = $employmentDetail;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setEmploymentDetail(EmploymentDetail $employmentDetail): void
    {
        $this->employmentDetail = $employmentDetail;
    }

    //Create the model
    public function create(Request $request): EmploymentDetail
    {
        return $this->employmentDetail->create(
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
        $this->employmentDetail->update($request->safe());
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
            $this->employmentDetail->update($array);
        } catch (Exception $e){
            dd($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->employmentDetail->delete();
    }

    public function createOrUpdateEmploymentDetails(mixed $data):void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        DB::beginTransaction();

        try {
            $this->employmentDetail->whereNotIn('id', collect($data['employment_details'])->pluck('id')->filter()->toArray())->delete();

            collect($data['employment_details'])->each(function ($employment) {
                $employment['client_id'] = $this->client->id;

                if(array_key_exists('id', $employment)){
                    $model = $this->employmentDetail->where('id', $employment['id'])->first();
                    $model->update($employment);
                } else {
                    $this->employmentDetail->create($employment);
                }
            });

        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }

        DB::commit();
    }
}
