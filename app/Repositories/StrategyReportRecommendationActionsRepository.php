<?php
namespace App\Repositories;

use App\Exceptions\ClientNotFoundException;
use App\Models\StrategyRecommendationAction;
use App\Models\StrategyReportRecommendation;
use Illuminate\Http\Request;

class StrategyReportRecommendationActionsRepository extends BaseRepository
{
    protected StrategyRecommendationAction $strategyRecommendationAction;

    public function __construct(StrategyRecommendationAction $strategyRecommendationAction)
    {
        $this->strategyRecommendationAction = $strategyRecommendationAction;
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

    public function setStrategyReportRecommendationAction(StrategyRecommendationAction $action):void
    {
        $this->strategyRecommendationAction = $action;
    }

    //Create the model from either a HTTP Request or from an array
    public function create(mixed $input): StrategyRecommendationAction
    {
        if(!is_array($input) && $input::class == Request::class)
        {
            $input = $input->safe()->all();
        }

        return $this->strategyRecommendationAction->create($input);
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

        $this->strategyRecommendationAction->update($input);
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->strategyRecommendationAction->delete();
    }

    public function getMaxOrderId(int $id): int
    {
        $currentMax = $this->strategyRecommendationAction->where('strategy_report_recommendation_id', $id)->max('order');
        return $currentMax + 1;
    }

    public function getActionById($id): StrategyRecommendationAction
    {
        return StrategyRecommendationAction::where('id', $id)->first();
    }

    public function updateActionsOrder(array $actions):void
    {
        collect($actions)->each(function ($action){
            unset($action['client_id']);

            $this->setStrategyReportRecommendationAction(
                $this->getActionById($action['id'])
            );

            $this->update($action);
        });
    }














}
