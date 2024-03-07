<?php
namespace App\Repositories;

use App\Models\Client;
use App\Models\RiskProfile;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RiskProfileRepository extends BaseRepository
{
    protected Client $client;
    protected RiskProfile $riskProfile;

    public function __construct(Client $client, RiskProfile $riskProfile)
    {
        $this->client = $client;
        $this->riskProfile = $riskProfile;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setKnowledge(RiskProfile $riskProfile): void
    {
        $this->riskProfile = $riskProfile;
    }

    //Create the model
    public function create(Request $request): RiskProfile
    {
        return $this->riskProfile->create(
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
        $this->riskProfile->update($request->safe());
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
            $this->riskProfile->update($array);
        } catch (Exception $e){
            dd($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->riskProfile->delete();
    }

    private function createDefaultJsonValue(): string
    {
        $short_term_volatility = [
            '0' => ['value' => null],
            '1' => ['value' => null],
            '2' => ['value' => null],
            '3' => ['value' => null],
            '4' => ['value' => null],
            '5' => ['value' => null]
        ];

        return json_encode($short_term_volatility);
    }

    public function createInitialRiskProfileForClient(): RiskProfile
    {
        $risk = array(
            'client_id' => $this->client->id,
            'type' => 0,
            'short_term_volatility' => $this->createDefaultJsonValue()
        );

        DB::beginTransaction();
        try {
            $newRecord = RiskProfile::create($risk);
            DB::commit();

            return $newRecord;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }
    }

    public function createOrUpdate(mixed $riskProfile):void
    {
        if(!is_array($riskProfile) && $riskProfile::class == Request::class)
        {
            $riskProfile = $riskProfile->safe();
        }

        DB::beginTransaction();

        try {
            $riskProfile['client_id'] = $this->client->id;

            if(array_key_exists('id', $riskProfile) && $riskProfile['id'] != null){
                $model = RiskProfile::where('id', $riskProfile['id'])->first();

                unset($riskProfile['id']);
                $model->update($riskProfile);
            } else {
                RiskProfile::create($riskProfile);
            }

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            throw new \Exception($e);
        }

        DB::commit();
    }
}
