<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Presenters\StrategyReportRecomPresenter;

class StrategyReportRecommendation extends Model
{
    protected $guarded = [];

    public function clients():HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function objectives(): HasMany
    {
        return $this->hasMany(StrategyRecomObjectives::class);
    }

    public function actions(): HasMany
    {
        return $this->hasMany(StrategyRecomActions::class);
    }

    public function loadEnumsForStrategyRecommendationsStep($step)
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
            default => [
            ]
        };
    }


    public function presenter() : StrategyReportRecomPresenter
    {
        return new StrategyReportRecomPresenter($this);
    }

    public function getPrimaryClientAttribute()
    {
        if ($this->clients->count() > 1) {
            return $this->clients()->where('c2_id', '!=', null)->first();
        }

        return $this->clients()->first();
    }
}
