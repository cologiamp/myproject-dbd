<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Exceptions\InvestmentRecommendationNotFoundException;
use App\Models\Client;
use App\Models\InvestmentRecommendationItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvestmentRecommendationItemRepository extends BaseRepository
{
    use ParsesIoClientData;

    protected Client $client;
    protected InvestmentRecommendationItem $investmentRecommendationItem;
    public function __construct(Client $client, InvestmentRecommendationItem $investmentRecommendationItem)
    {
        $this->client = $client;
        $this->investmentRecommendationItem = $investmentRecommendationItem;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
    public function setInvestmentRecommendationItem(InvestmentRecommendationItem $investmentRecommendationItem): void
    {
        $this->investmentRecommendationItem = $investmentRecommendationItem;
    }

    public function getInvestmentRecommendationItem() : InvestmentRecommendationItem
    {
        if($this->investmentRecommendationItem){
            return $this->investmentRecommendationItem;
        }
        throw new InvestmentRecommendationNotFoundException();
    }

    //Create the model
    public function create(Request $request): InvestmentRecommendationItem
    {
        return $this->investmentRecommendationItem->create(
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
        $this->investmentRecommendationItem->update($request->safe());
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
        $this->investmentRecommendationItem->update($array);
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //detach item from client_recommendation_items and delete from model
        $this->investmentRecommendationItem->clients()->each(function ($item) {
            $item->investment_recommendation_items()->detach([$this->investmentRecommendationItem->id]);
        });

        $this->investmentRecommendationItem->delete();
    }

    public function createOrUpdateRecommendationItems(mixed $data):void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        DB::beginTransaction();

        collect($data['investment_recommendation_items'])->each(function ($item) {
            // format item data for registration or update
            $itemData = array(
                'type' => $item['type'],
                'source_plan' => $item['source_plan'],
                'description' => $item['description'],
                'stock_type' => $item['stock_type'],
                'number_of_units' => $item['number_of_units'],
                'amount' => $item['amount']
            );

            if(array_key_exists('id', $item)) {
                $model = InvestmentRecommendationItem::where('id', $item['id'])->first();

                try {
                    // update item on [investment_recommendation_item] table
                    $model->update($itemData);

                } catch (\Exception $e) {
                    DB::rollback();
                    throw new \Exception($e);
                }


            } else {
                // register item
                $model = $this->registerRecommendationItem($itemData);
            }

            // get client info and attach investment item onto client pivot table
            // To Do: process item owner once 2 client record is implemented
            $client = Client::with('investment_recommendation_items')->where('id', $this->client->id)->first();

            if(collect($client->investment_recommendation_items->pluck('id'))->doesntContain($model->id)){
                $this->client->investment_recommendation_items()->attach($model->id);
            }

            DB::commit();
        });
    }

    private function registerRecommendationItem(array $itemData)
    {
        try {
            $model = $this->investmentRecommendationItem->create($itemData);
        } catch (Exception $e) {
            Log::warning($e);
        }

        return $model;
    }
}
