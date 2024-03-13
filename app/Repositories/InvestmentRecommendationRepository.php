<?php
namespace App\Repositories;

use App\Exceptions\InvestmentRecommendationNotFoundException;
use App\Models\Client;
use App\Models\InvestmentBond;
use App\Models\InvestmentRecommendation;
use App\Services\InvestmentRecommendationSectionDataService;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InvestmentRecommendationRepository extends BaseRepository
{
    protected Client $client;
    protected InvestmentRecommendation $investmentRecommendation;
    public function __construct(Client $client, InvestmentRecommendation $investmentRecommendation)
    {
        $this->client = $client;
        $this->investmentRecommendation = $investmentRecommendation;
    }

    public function setClient(Client $client): void
    {
        $this->client = $client;
    }
    public function setInvestmentRecommendation(InvestmentRecommendation $investmentRecommendation): void
    {
        $this->investmentRecommendation = $investmentRecommendation;
    }

    public function getInvestmentRecommendation() : InvestmentRecommendation
    {
        if($this->investmentRecommendation){
            return $this->investmentRecommendation;
        }
        throw new InvestmentRecommendationNotFoundException();
    }

    //Create the model
    public function create(Request $request): InvestmentRecommendation
    {
        return $this->investmentRecommendation->create(
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
        $this->investmentRecommendation->update($request->safe());
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
        $this->investmentRecommendation->update($array);
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->investmentRecommendation->delete();
    }

    //Construct the query based on the request parameters to retrieve the right data for the correct domain context.
    //This is where further index scoping/loading can be added based on the request or context.
    public function constructIndexQuery(Request $request): Builder
    {
        return InvestmentRecommendation::
//          with()-> //Eager Load here
        select('investment_recommendations.*');//Limit query here
    }

    /**
     * Load in investmentrecommendation sidebar items dynamically for the tabs
     * @param int - the step that we want to load the sidebar for
     */
    public function loadInvestmentRecommendationSidebarItems($sections, $step, $currentStep, $currentSection): Collection
    {
        return collect($sections)->map(function ($value,$key) use ($currentStep, $currentSection, $step){
            return  [
               'name' => $value,
               'renderable' => Str::studly($value),
               'current' => $key === $currentSection,
               'dynamicData' => InvestmentRecommendationSectionDataService::get($this->investmentRecommendation,$step,$key,),
           ];
        });
    }


    /**
     * Load in the correct data structure for the sidebar tabs of the page we're on
     * @return array
     */
    public function loadInvestmentRecommendationTabs(int $currentStep = 1,int $currentSection = 1):array
    {
        return collect(config('navigation_structures.recommendations'))->map(function ($value,$key) use ($currentSection,$currentStep){
            return [
                'name' => $value['name'],
                'current' =>  $key === $currentStep,
                'sidebaritems' => $this->loadInvestmentRecommendationSidebarItems(collect($value['sections'])->mapWithKeys(function ($value,$key){
                    return [$key => $value['name']];
                }), $key, $currentStep, $currentSection)->toArray()
            ];
        })->toArray();
    }

    public function createOrUpdateIncomeGrowthReport(mixed $data):void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        DB::beginTransaction();

        $investmentRecommendation = InvestmentRecommendation::where('id', $data['id'])->first();

        $formatInvestmentData = array(
            'report_type' => $data['report_type'],
            'net_income_required' => $data['net_income_required'],
            'regular_cash_required' => $data['regular_cash_required'],
            'regular_cash_duration' => $data['regular_cash_duration'],
        );

        try {
            if(!$investmentRecommendation){
                $newInvestmentRecord = $this->registerInvestmentRecommendation($formatInvestmentData);

                // update client investment info on clients table
                $this->updateClientsInvestmentId($data, $newInvestmentRecord);
            }
            else {
                $investmentRecommendation->update($formatInvestmentData);

                // update client investment info on clients table
                $this->updateClientsInvestmentId($data, $investmentRecommendation);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }
    }

    public function createOrUpdateTaxConsequences(mixed $data):void
    {
        if(!is_array($data) && $data::class == Request::class)
        {
            $data = $data->safe();
        }

        DB::beginTransaction();

        $investmentRecommendation = InvestmentRecommendation::where('id', $data['id'])->first();

        $formatInvestmentData = array(
            'cta_base_costs_available' => $data['cta_base_costs_available'],
            'cta_sell_to_cgt_exemption' => $data['cta_sell_to_cgt_exemption'],
            'cta_sell_all' => $data['cta_sell_all'],
            'cta_sell_set_amount' => $data['cta_sell_set_amount'],
            'dta_base_costs_available' => $data['dta_base_costs_available'],
            'dta_sell_to_cgt_exemption' => $data['dta_sell_to_cgt_exemption'],
            'dta_sell_all' => $data['dta_sell_all'],
            'dta_sell_set_amount' => $data['dta_sell_set_amount'],
            'isa_transfer_exit_penalty_not_ascertained' => $data['isa_transfer_exit_penalty_not_ascertained'],
            'isa_transfer_exit_penalty_ascertained' => $data['isa_transfer_exit_penalty_ascertained'] == null ? '' : $data['isa_transfer_exit_penalty_ascertained'],
            'investment_bonds_managed_funds' => $data['investment_bonds_managed_funds'],
            'investment_bonds_with_profits' => $data['investment_bonds_with_profits'],
            'investment_bonds_chargeable_gain_not_calculated' => $data['investment_bonds_chargeable_gain_not_calculated'],
            'investment_bonds_exit_penalty_not_ascertained' => $data['investment_bonds_exit_penalty_not_ascertained'],
            'investment_bonds_exit_penalty_ascertained' => $data['investment_bonds_exit_penalty_ascertained'] == null ? '' : $data['investment_bonds_exit_penalty_ascertained']
        );

        try {
            if(!$investmentRecommendation){
                Log::info('Create new investment recommendation');
                $investmentRecommendation = $this->registerInvestmentRecommendation($formatInvestmentData);
            }
            else {
                Log::info('Update investment recommendation');
                $investmentRecommendation->update($formatInvestmentData);
            }

            // create or update investment_bonds record
            if (array_key_exists('investment_bonds', $data)) {
                $this->createOrUpdateInvestmentBonds($investmentRecommendation, $data['investment_bonds']);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }
    }

    public function registerInvestmentRecommendation(array $data): InvestmentRecommendation
    {
        try {
            return InvestmentRecommendation::create($data);
        } catch (\Exception $e) {
            throw new \Exception($e);
        }
    }

    public function createInitialInvestmentRecommendationForClient(): InvestmentRecommendation
    {
        $formatInvestmentData = array(
            'isa_transfer_exit_penalty_ascertained' => '',
            'isa_transfer_exit_penalty_not_ascertained' => 0,
            'investment_bonds_chargeable_gain_not_calculated' => 0,
            'investment_bonds_exit_penalty_not_ascertained' => 0,
            'investment_bonds_exit_penalty_ascertained' => '',
            'investment_bonds_managed_funds' => 0,
            'investment_bonds_with_profits' => 0,
            'dta_sell_to_cgt_exemption' => 0,
            'cta_sell_to_cgt_exemption' => 0
        );

        DB::beginTransaction();
        try {
            $newInvestmentRecommendation = InvestmentRecommendation::create($formatInvestmentData);
            DB::commit();

            return $newInvestmentRecommendation;
        } catch (\Exception $e) {
            DB::rollback();
            throw new \Exception($e);
        }
    }

    private function updateClientsInvestmentId(mixed $data, InvestmentRecommendation $investmentRecommendation): void
    {
        $clientInvestmentRecommendation = [
            'isa_allowance_used' => $data['isa_allowance_used'],
            'cgt_allowance_used' => $data['cgt_allowance_used']
        ];

        // To Do: add update condition if [report_for] is set as 'Both'
        if (array_key_exists('report_for', $data) && $data['report_for'] != null) {
            $investmentRecommendation->clients()->where('io_id', $data['report_for'])->first()->update($clientInvestmentRecommendation);
        } else {
            $investmentRecommendation->primary_client->update($clientInvestmentRecommendation);
        }
    }
    private function createOrUpdateInvestmentBonds(InvestmentRecommendation $investmentRecommendation, array $investmentBonds): void
    {
        $investmentRecommendation->investment_bonds()->whereNotIn('id', collect($investmentBonds)->pluck('id')->filter()->toArray())->delete();

        collect($investmentBonds)->each(function ($bond) use ($investmentRecommendation) {
            $formatInvestmentBondData = Arr::except($bond, ['id']);
            $formatInvestmentBondData['investment_recommendation_id'] = $investmentRecommendation->id;

            if(array_key_exists('id', $bond)) {
                $model = InvestmentBond::where('id', $bond['id'])->first();

                try {
                    // update investment bond
                    Log::info('Update investment bond');
                    $model->update($formatInvestmentBondData);
                } catch (\Exception $e) {
                    throw new \Exception($e);
                }
            } else {
                // register investment bond
                Log::info('Create new investment bond');
                InvestmentBond::create($formatInvestmentBondData);
            }
        });
    }
}
