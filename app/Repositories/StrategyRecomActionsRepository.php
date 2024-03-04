<?php
namespace App\Repositories;

use App\Exceptions\ClientNotFoundException;
use App\Models\StrategyRecomActions;
use App\Models\StrategyReportRecommendation;
use Illuminate\Http\Request;

class StrategyRecomActionsRepository extends BaseRepository
{
    protected StrategyRecomActions $strategyRecomActions;

    public function __construct(StrategyRecomActions $strategyRecomActions)
    {
        $this->strategyRecomActions = $strategyRecomActions;
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

    public function setStrategyReportRecomAction(StrategyRecomActions $action):void
    {
        $this->strategyRecomActions = $action;
    }

    //Create the model from either a HTPT Request or from an array
    public function create(mixed $input): StrategyRecomActions
    {
        if(!is_array($input) && $input::class == Request::class)
        {
            $input = $input->safe()->all();
        }

        return $this->strategyRecomActions->create($input);
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

        $this->strategyRecomActions->update($input);
    }

    //Delete the resource from the database, doing any cleanup first
    public function delete(): void
    {
        //handle any cleanup required here
        $this->strategyRecomActions->delete();
    }

    public function getMaxOrderId(int $id): int
    {
        $currentMax = $this->strategyRecomActions->where('strategy_report_recommendation_id', $id)->max('order');
        return $currentMax + 1;
    }

    public function getActionById($id): StrategyRecomActions
    {
        return StrategyRecomActions::where('id', $id)->first();
    }

    public function updateActionsOrder(array $actions):void
    {
        collect($actions)->each(function ($action){
            unset($action['client_id']);

            $this->setStrategyReportRecomAction(
                $this->getActionById($action['id'])
            );

            $this->update($action);
        });
    }














}
