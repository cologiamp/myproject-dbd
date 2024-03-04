<?php
namespace App\Repositories;

use App\Concerns\ParsesIoClientData;
use App\Models\Asset;
use App\Models\Client;
use App\Models\EmploymentDetail;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class AssetRepository extends BaseRepository
{
    protected Client $client;
    protected Asset $asset;

    public function __construct(Client $client, Asset $asset)
    {
        $this->client = $client;
        $this->asset = $asset;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setAsset(Asset $asset): void
    {
        $this->asset = $asset;
    }

    //Create the model
    public function create(Request $request): Asset
    {
        return $this->asset->create(
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
        $this->asset->update($request->safe());
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
            $this->asset->update($array);
        } catch (Exception $e){
            dd($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->asset->clients()->each(function ($item){
            $item->assets()->detach([$this->asset->id]); //remove all of that asset's client attachments
        });
        $this->asset->delete();
    }

    public function createOrUpdateAssets(mixed $assets):void
    {
        if(!is_array($assets) && $assets::class == Request::class)
        {
            $assets = $assets->safe();
        }
        $assets = $assets['assets'];


        DB::beginTransaction();

        try {
            collect($assets)->each(function ($asset)  {

                if(array_key_exists('percent_ownership',$asset))
                {
                    $percents = $asset['percent_ownership'];
                    unset($asset['percent_ownership']);
                    unset($asset['owner']);
                    $owner = null;
                }
                elseif(array_key_exists('owner',$asset)){
                    $owner = $asset['owner'];
                    unset($asset['percent_ownership']);
                    unset($asset['owner']);
                    $percents = null;
                }
                else{
                    $percents = null;
                    $owner = null;
                }

                if(array_key_exists('id', $asset) && $asset['id'] != null){
                    $model = Asset::where('id', $asset['id'])->first();
                    $model->update($asset);
                } else {
                    $model = Asset::create($asset);
                }
                if($percents != null)
                {
                    $model->clients()->detach();
                    collect($percents)->each(function ($item,$key) use ($model){
                        $client = Client::with('assets')->where('io_id',$key)->first();
                        if(collect($client->assets->pluck('id'))->doesntContain($model->id))
                        {
                            $client->assets()->attach($model->id,['percent_ownership' => $item]);
                        }
                        else{
                            DB::table('asset_client')->where('asset_id',$model->id)->where('client_id',$client->id)->update(['percent_ownership'=> $item]);
                        }
                    });
                }
                elseif($owner != null)
                {
                    $model->clients()->detach();
                    if($owner == 'Both')
                    {
                        $client = $this->client;
                        $client2 = $this->client->client_two;
                        if($client && $client2)
                        {
                            $model->clients()->attach( $client->id,['percent_ownership' => 50]);
                            $model->clients()->attach( $client2->id,['percent_ownership' => 50]);
                        }
                        else{
                            dd('both but not client two');
                        }
                    }
                    else{
                        //case where 100% owned by one client, no percentages
                        $client = Client::with('assets')->where('io_id',$owner)->first();
                        $client->assets()->attach($model->id,['percent_ownership' => 100]);
                    }
                }
                else{
                    $client = $this->client;
                    $client->assets()->attach($model->id,['percent_ownership' => 100]);
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
