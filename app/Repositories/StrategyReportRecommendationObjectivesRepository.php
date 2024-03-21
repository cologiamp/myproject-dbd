<?php
namespace App\Repositories;

use App\Models\StrategyRecommendationObjective;
use App\Models\StrategyReportRecommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ClientNotFoundException;

class StrategyReportRecommendationObjectivesRepository extends BaseRepository
{
    protected StrategyRecommendationObjective $strategyRecommendationObjective;

    public function __construct(StrategyRecommendationObjective $strategyRecommendationObjective)
    {
        $this->strategyRecommendationObjective = $strategyRecommendationObjective;
    }

    public function setStrategyReportRecommendation(StrategyReportRecommendation $strategyReportRecommendation):void
    {
        $this->strategyReportRecommendation = $strategyReportRecommendation;
    }

    public function getStrategyReportRecommendation() : StrategyReportRecommendation
    {
        if($this->strategyReportRecommendation){
            return $this->strategyReportRecommendation;
        }
        throw new ClientNotFoundException();
    }

    public function setStrategyReportRecommendationObjective(StrategyRecommendationObjective $objective):void
    {
        $this->strategyRecommendationObjective = $objective;
    }

    //Create the model from either a HTPT Request or from an array
    public function create(mixed $input): StrategyRecommendationObjective
    {
        if(!is_array($input) && $input::class == Request::class)
        {
            $input = $input->safe()->all();
        }

        return $this->strategyRecommendationObjective->create($input);
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

        $this->strategyRecommendationObjective->update($input);
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->strategyRecommendationObjective->delete();
    }

    public function getMaxOrderId(int $id): int
    {
        $currentMax = $this->strategyRecommendationObjective->where('strategy_report_recommendation_id', $id)->max('order');
        return $currentMax + 1;
    }

    public function getObjectiveById($id): StrategyRecommendationObjective
    {
        return StrategyRecommendationObjective::where('id', $id)->first();
    }

    public function updateObjectivesOrder(array $objectives):void
    {
        collect($objectives)->each(function ($obj){
            unset($obj['client_id']);

            $this->setStrategyReportRecommendationObjective(
                $this->getObjectiveById($obj['id'])
            );

            $this->update($obj);
        });
    }
}
