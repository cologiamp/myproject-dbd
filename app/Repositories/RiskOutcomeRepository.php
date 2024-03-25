<?php
namespace App\Repositories;

use App\Models\RiskOutcome;
use App\Models\Client;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ClientNotFoundException;

class RiskOutcomeRepository extends BaseRepository
{
    protected Client $client;
    protected RiskOutcome $riskOutcome;

    public function __construct(Client $client, RiskOutcome $riskOutcome)
    {
        $this->client = $client;
        $this->riskOutcome = $riskOutcome;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function getClient() : Client
    {
        if($this->client){
            return $this->client;
        }
        throw new ClientNotFoundException();
    }

    public function setRiskOutcome(RiskOutcome $riskOutcome): void
    {
        $this->riskOutcome = $riskOutcome;
    }

    //Create the model
    public function create(Request $request): RiskOutcome
    {
        return $this->riskOutcome->create(
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
        $this->riskOutcome->update($request->safe());
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
            $this->riskOutcome->update($array);
        } catch (Exception $e){
            dd($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->riskOutcome->delete();
    }

    public function createInitialRiskOutcomeForClient(): RiskOutcome
    {
        $clientIds = collect([$this->client->id]);

        if ($this->client->client_two) {
            $clientIds->push($this->client->client_two->id);
        }

        DB::beginTransaction();

        try {
            $clientIds->each(function ($id) {
                $data = array(
                    'client_id' => $id,
                    'using_calculated_risk_profile_investment' => 1,
                    'using_calculated_risk_profile_pension' => 1
                );

                RiskOutcome::create($data);
            });

            DB::commit();
            return RiskOutcome::where('client_id', $this->client->id)->first();

        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }
    }
}
