<?php
namespace App\Repositories;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\InvestmentRecommendationNotFoundException;
use App\Models\Client;
use App\Models\PensionRecommendation;
use App\Models\PensionRecommendationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PRItemsRepository extends BaseRepository
{
    protected Client $client;
    protected PensionRecommendation $pensionRecommendation;
    protected PensionRecommendationItem $prItem;
    public function __construct(Client $client, PensionRecommendation $pensionRecommendation, PensionRecommendationItem $prItem)
    {
        $this->client = $client;
        $this->pensionRecommendation = $pensionRecommendation;
        $this->prItem = $prItem;
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

    public function setPRItem(PensionRecommendationItem $prItem): void
    {
        $this->prItem = $prItem;
    }

    public function getPRItem() : PensionRecommendationItem
    {
        if($this->prItem){
            return $this->prItem;
        }
        throw new InvestmentRecommendationNotFoundException(); //change this later
    }

    //Create the model
    public function create(Request $request): PensionRecommendationItem
    {
        return $this->prItem->create(
            array_merge($request->safe()->all(),
                []
            ));
    }

    /**
     * This method uses a generecised form request to update the model.
     * Use updateFromValidated if you have already run a Validator on your data.
     * @param Request $request
     * @return void
     */
    public function update(Request $request): void
    {
        //merge "other values" - eg array_merge($request->safe()->only([]),[])
        $this->prItem->update($request->safe());
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
        $this->prItem->update($array);
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->prItem->delete();
    }

    public function createOrUpdateItem(mixed $data): void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        DB::beginTransaction();

        try {
            collect($data)->each(function ($item) {
                if(array_key_exists('id', $item)) {
                    $model = PensionRecommendationItem::where('id', $item['id'])->first();

                    try {
                        ray('update')->orange();
                        Log::info('Update PR item');
                        $model->update($item);
                    } catch (\Exception $e) {
                        throw new \Exception($e);
                    }
                } else {
                    ray('create')->orange();
                    Log::info('Create new item');

                    if (!array_key_exists('pension_recommendation_id', $item) || $item['pension_recommendation_id'] == null) {
                        $item['pension_recommendation_id'] = $this->client->pension_recommendation_id;
                    }
                    ray($this->client);
                    ray($item)->red();
                    PensionRecommendationItem::create($item);
                }
            });

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }
    }
}
