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
                'recom_objective_types' => config('enums.strategy_report_recommendations.recom_objective_type'),
                'legacy_planning_topic' => config('enums.strategy_report_recommendations.legacy_planning_topic'),
                'tax_efficiency_topic' => config('enums.strategy_report_recommendations.tax_efficiency_topic'),
                'short_term_cash_needs_topic' => config('enums.strategy_report_recommendations.short_term_cash_needs_topic'),
                'income_needs_topic' => config('enums.strategy_report_recommendations.income_needs_topic'),
                'capital_growth_topic' => config('enums.strategy_report_recommendations.capital_growth_topic'),
                'simplify_approach_topic' => config('enums.strategy_report_recommendations.simplify_approach_topic'),
                'flexibility_topic' => config('enums.strategy_report_recommendations.flexibility_topic'),
                'advice_service_topic' => config('enums.strategy_report_recommendations.advice_service_topic'),
                'repayment_liabilities_topic' => config('enums.strategy_report_recommendations.repayment_liabilities_topic'),
                'pension_planning_topic' => config('enums.strategy_report_recommendations.pension_planning_topic')
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
