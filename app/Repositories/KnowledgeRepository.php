<?php
namespace App\Repositories;

use App\Models\Client;
use App\Models\Knowledge;
use App\Models\RiskProfile;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
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
        $types = [RiskProfile::RISK_INVESTMENT_TYPE, RiskProfile::RISK_PENSION_TYPE];

        DB::beginTransaction();

        try {
            for ($x = 0; $x < count($types); $x++) {
                $knowledge = array(
                    'client_id' => $this->client->id,
                    'type' => $types[$x],
                    'experience_buying_cash' => $this->createDefaultJson(),
                    'experience_buying_bonds' => $this->createDefaultBondJson(),
                    'experience_buying_equities' => $this->createDefaultJson(),
                    'experience_buying_insurance' => $this->createDefaultJson(),
                );

                $newRecord = Knowledge::create($knowledge);
            }

            DB::commit();

            return $newRecord;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }
    }

    public function createOrUpdate(mixed $knowledge):void
    {
        ray($knowledge)->green();
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
