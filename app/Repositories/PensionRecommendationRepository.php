<?php
namespace App\Repositories;

use App\Exceptions\InvestmentRecommendationNotFoundException;
use App\Models\Client;
use App\Models\PensionRecommendation;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PensionRecommendationRepository extends BaseRepository
{
    protected Client $client;
    protected PensionRecommendation $pensionRecommendation;
    public function __construct(Client $client, PensionRecommendation $pensionRecommendation)
    {
        $this->client = $client;
        $this->pensionRecommendation = $pensionRecommendation;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
    public function setPensionRecommendation(PensionRecommendation $pensionRecommendation): void
    {
        $this->pensionRecommendation = $pensionRecommendation;
    }

    public function getPensionRecommendation() : PensionRecommendation
    {
        if($this->pensionRecommendation){
            return $this->pensionRecommendation;
        }
        throw new InvestmentRecommendationNotFoundException();
    }

    //Create the model
    public function create(Request $request): PensionRecommendation
    {
        return $this->pensionRecommendation->create(
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
        $this->pensionRecommendation->update($request->safe());
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
        $this->pensionRecommendation->update($array);
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->pensionRecommendation->delete();
    }
}
