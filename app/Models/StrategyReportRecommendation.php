<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Presenters\StrategyReportRecommendationPresenter;
use App\Concerns\GetPrimaryClient;

class StrategyReportRecommendation extends Model
{
    protected $guarded = [];

    use GetPrimaryClient;

    public function clients():HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function objectives(): HasMany
    {
        return $this->hasMany(StrategyRecommendationObjective::class);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(StrategyRecommendationAction::class);
    }

    public function loadEnumsForStrategyRecommendationsStep($step): array
    {
        return match ($step){
            1 => [
                'report_versions' => config('enums.strategy_report_recommendations.report_version'),
                'retirement_statuses' => config('enums.strategy_report_recommendations.retirement_status'),
                'objective_types' => config('enums.strategy_report_recommendations.objective_type')
            ],
            2 => [
                'topics' => config('enums.strategy_report_recommendations.topic'),
                'recom_objective_types' => config('enums.strategy_report_recommendations.recom_objective_type')
            ],
            3 => [
              'call_to_action_types' => config('enums.strategy_report_recommendations.call_to_action_types')
            ],
            default => [
            ]
        };
    }


    public function presenter() : StrategyReportRecommendationPresenter
    {
        return new StrategyReportRecommendationPresenter($this);
    }

    public function getPrimaryClientAttribute()
    {
        return $this->getPrimaryClient();
    }

}
