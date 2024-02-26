<?php
namespace App\Repositories;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\InvestmentRecommendationNotFoundException;
use App\Models\Client;
use App\Models\PensionRecommendation;
use App\Models\PRNewContribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PRNewContributionRepository extends BaseRepository
{
    protected Client $client;
    protected PensionRecommendation $pensionRecommendation;
    protected PRNewContribution $prNewContribution;
    public function __construct(Client $client, PensionRecommendation $pensionRecommendation, PRNewContribution $prNewContribution)
    {
        $this->client = $client;
        $this->pensionRecommendation = $pensionRecommendation;
        $this->prNewContribution = $prNewContribution;
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

    public function setPRNewContribution(PRNewContribution $prNewContribution): void
    {
        $this->prNewContribution = $prNewContribution;
    }

    public function getPRNewContribution() : PRNewContribution
    {
        if($this->prNewContribution){
            return $this->prNewContribution;
        }
        throw new InvestmentRecommendationNotFoundException();
    }

    //Create the model
    public function create(Request $request): PensionRecommendation
    {
        return $this->prNewContribution->create(
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
        $this->prNewContribution->update($request->safe());
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
        $this->prNewContribution->update($array);
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->prNewContribution->delete();
    }

    public function createOrUpdateContribution(mixed $data): void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        DB::beginTransaction();

        try {
            collect($data)->each(function ($contribution) {
                if(array_key_exists('id', $contribution)) {
                    $model = PRNewContribution::where('id', $contribution['id'])->first();

                    try {
                        ray('update')->orange();
                        Log::info('Update PR contribution');
                        $model->update($contribution);
                    } catch (\Exception $e) {
                        throw new \Exception($e);
                    }
                } else {
                    Log::info('Create new contribution');

                    if (!array_key_exists('pension_recommendation_id', $contribution)) {
                        $contribution['pension_recommendation_id'] = $this->client->pension_recommendation_id;
                    }

                    PRNewContribution::create($contribution);
                }
            });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }
    }
}
