<?php
namespace App\Repositories;

use App\Models\Client;
use App\Models\Knowledge;
use App\Models\RiskProfile;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KnowledgeRepository extends BaseRepository
{
    protected Client $client;
    protected Knowledge $knowledge;

    public function __construct(Client $client, Knowledge $knowledge)
    {
        $this->client = $client;
        $this->knowledge = $knowledge;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }

    public function setKnowledge(Knowledge $knowledge): void
    {
        $this->knowledge = $knowledge;
    }

    //Create the model
    public function create(Request $request): Knowledge
    {
        return $this->knowledge->create(
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
        $this->knowledge->update($request->safe());
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
            $this->knowledge->update($array);
        } catch (Exception $e){
            dd($e);
        }

    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->knowledge->delete();
    }

    private function createDefaultJson(): string
    {
        $experience_buying_cash = [
            '0' => ['value' => null],
            '1' => ['value' => null],
            '2' => ['value' => null]
        ];

        return json_encode($experience_buying_cash);
    }

    private function createDefaultBondJson(): string
    {
        $experience_buying_bonds = [
            '0' => ['value' => null],
            '1' => ['value' => null],
            '2' => ['value' => null],
            '3' => ['value' => null]
        ];

        return json_encode($experience_buying_bonds);
    }

    public function createInitialKnowledgeForClient(): Knowledge
    {
        $types = collect([config('enums.risk_assessment.type')['INVESTMENT_TYPE'], config('enums.risk_assessment.type')['PENSION_TYPE']]);
        $clientIds = collect([$this->client->id]);

        if ($this->client->client_two) {
            $clientIds->push($this->client->client_two->id);
        }

        DB::beginTransaction();

        try {
            $types->each(function ($type) use ($clientIds){
                $clientIds->each(function ($id) use ($type) {
                    $knowledge = array(
                        'client_id' => $id,
                        'type' => $type,
                        'experience_buying_cash' => $this->createDefaultJson(),
                        'experience_buying_bonds' => $this->createDefaultBondJson(),
                        'experience_buying_equities' => $this->createDefaultJson(),
                        'experience_buying_insurance' => $this->createDefaultJson()
                    );

                    Knowledge::create($knowledge);
                });
            });

            DB::commit();
            return Knowledge::where('client_id', $this->client->id)->first();

        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }
    }

    public function createOrUpdate(mixed $knowledge):void
    {
        if(!is_array($knowledge) && $knowledge::class == Request::class)
        {
            $knowledge = $knowledge->safe();
        }

        DB::beginTransaction();

        try {
            $knowledge['client_id'] = $this->client->id;

            if(array_key_exists('id', $knowledge) && $knowledge['id'] != null){
                $model = Knowledge::where('id', $knowledge['id'])->first();

                unset($knowledge['id']);
                $model->update($knowledge);
            } else {
                Knowledge::create($knowledge);
            }

        } catch (\Exception $e) {
            DB::rollback();
            dd($e);
            throw new \Exception($e);
        }

        DB::commit();
    }
}
