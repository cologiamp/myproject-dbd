<?php
namespace App\Repositories;

use App\Models\StrategyRecomObjectives;
use App\Models\StrategyReportRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ClientNotFoundException;

class StrategyRecomObjectivesRepository extends BaseRepository
{
    protected StrategyRecomObjectives $strategyRecomObjectives;

    public function __construct(StrategyRecomObjectives $strategyRecomObjectives)
    {
        $this->strategyRecomObjectives = $strategyRecomObjectives;
    }

    public function setStrategyReportRecom(StrategyReportRecommendation $strategyReportRecom):void
    {
        $this->strategyReportRecom = $strategyReportRecom;
    }

    public function getStrategyReportRecom() : StrategyReportRecommendation
    {
        if($this->strategyReportRecom){
            return $this->strategyReportRecom;
        }
        throw new ClientNotFoundException();
    }

    public function setStrategyReportRecomObjective(StrategyRecomObjectives $objective):void
    {
        $this->strategyRecomObjectives = $objective;
    }

    //Create the model from either a HTPT Request or from an array
    public function create(mixed $input): StrategyRecomObjectives
    {
        if(!is_array($input) && $input::class == Request::class)
        {
            $input = $input->safe()->all();
        }

        return $this->strategyRecomObjectives->create($input);
    }

    //Update the given details
    /**
     * //Create the model from either a HTPT Request or from a pre-validated array
     * @param Request $request
     * @return void
     */
    public function update(mixed $input): void
    {
        if(!is_array($input) && $input::class == Request::class)
        {
            $input = $input->safe();
        }

        $this->strategyRecomObjectives->update($input);
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->strategyRecomObjectives->delete();
    }

    public function getMaxOrderId(int $id): int
    {
        $currentMax = $this->strategyRecomObjectives->where('strategy_report_recommendation_id', $id)->max('order');
        return $currentMax + 1;
    }

    public function getObjectiveById($id): StrategyRecomObjectives
    {
        return StrategyRecomObjectives::where('id', $id)->first();
    }

    public function updateObjectivesOrder(array $objectives):void
    {
        collect($objectives)->each(function ($obj){
            unset($obj['client_id']);

            $this->setStrategyReportRecomObjective(
                $this->getObjectiveById($obj['id'])
            );

            $this->update($obj);
        });
    }














}
