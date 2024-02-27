<?php
namespace App\Repositories;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\InvestmentRecommendationNotFoundException;
use App\Models\Client;
use App\Models\PensionRecommendation;
use App\Models\PRAnnualAllowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PRAnnualAllowanceRepository extends BaseRepository
{
    protected Client $client;
    protected PensionRecommendation $pensionRecommendation;
    protected PRAnnualAllowance $prAnnualAllowance;
    public function __construct(Client $client, PensionRecommendation $pensionRecommendation, PRAnnualAllowance $prAnnualAllowance)
    {
        $this->client = $client;
        $this->pensionRecommendation = $pensionRecommendation;
        $this->prAnnualAllowance = $prAnnualAllowance;
    }

    public function getClient() : Client
    {
        if($this->client){
            return $this->client;
        }
        throw new ClientNotFoundException();
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setPRAnnualAllowance(PRAnnualAllowance $prAnnualAllowance): void
    {
        $this->prAnnualAllowance = $prAnnualAllowance;
    }

    public function getPRAnnualAllowance() : PRAnnualAllowance
    {
        if($this->prAnnualAllowance){
            return $this->prAnnualAllowance;
        }
        throw new InvestmentRecommendationNotFoundException(); //change this later
    }

    //Create the model
    public function create(Request $request): PRAnnualAllowance
    {
        return $this->prAnnualAllowance->create(
            array_merge($request->safe()->all(),
                []
            ));
    }

    //Update the given details

    /**
     * This method uses a generecised form request to update the model.
     * Use updateFromValidated if you have already run a Validator on your data.
     * @param Request $request
     * @return void
     */
    public function update(Request $request): void
    {
        //merge "other values" - eg array_merge($request->safe()->only([]),[])
        $this->prAnnualAllowance->update($request->safe());
        //relations here - e.g  $this->model->relation()->sync();
    }

    /**
     * This method uses a pre-validated updated data array rather than a formRequest to update the model.
     * Do not use unless you have validated your data
     * @param array $array
     * @return void
     */
    public function updateFromValidated(array $array):void
    {
        $this->prAnnualAllowance->update($array);
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->prAnnualAllowance->delete();
    }

    public function createOrUpdateAllowance(mixed $data): void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        DB::beginTransaction();

        try {
            collect($data)->each(function ($allowance) {
                if(array_key_exists('id', $allowance)) {
                    $model = PRAnnualAllowance::where('id', $allowance['id'])->first();

                    try {
                        ray('update')->orange();
                        Log::info('Update PR allowance');
                        $model->update($allowance);
                    } catch (\Exception $e) {
                        throw new \Exception($e);
                    }
                } else {
                    Log::info('Create new allowance');

                    if (!array_key_exists('pension_recommendation_id', $allowance)) {

                        $allowance['pension_recommendation_id'] = $this->client->pension_recommendation_id;
                    }
                    ray($this->client);
                    ray($allowance)->red();
                    PRAnnualAllowance::create($allowance);
                }
            });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }
    }
}
