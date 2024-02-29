<?php

namespace App\Models\Presenters;

use App\Concerns\FormatsCurrency;
use Carbon\Carbon;

class StrategyReportRecomPresenter extends BasePresenter
{
    use FormatsCurrency;
    public function formatForSRRStep($step):array
    {
        return match ($step) {
            1 => [
                'id' => $this->model->id,
                'report_version' => $this->model->report_version,
                'retirement_status' => $this->model->retirement_status,
                'objective_type' => $this->model->objective_type,
                'next_meeting_date' => $this->model->next_meeting_date
            ],
            2 => [
                'objectives' => $this->model->objectives()->orderBy('order', 'asc')->get()->map(function ($item){
                    return [
                        'id' => $item->id,
                        'client_id' => $item->client_id,
                        'strategy_report_recommendation_id' => $item->strategy_report_recommendation_id,
                        'is_primary' => $item->is_primary != null ? array_values(config('enums.strategy_report_recommendations.recom_objective_type'))[$item->is_primary] : null,
                        'type' => $item->type != null ? array_values(config('enums.strategy_report_recommendations.topic'))[$item->type] : null,
                        'objective' => $item->objective != null ? $this->getObjective($item->objective) : null,
                        'objective_custom' => $item->objective_custom,
                        'what_for' => $item->what_for != null ? $this->getWhatFor($item->what_for) : null,
                        'what_for_custom' => $item->what_for_custom,
                        'order' => $item->order
                    ];
                }),
            ],
            default => [
            ]
        };
    }

    private function getObjective($id): string
    {
        switch ($id) {
            case 0:
                return array_values(config('enums.strategy_report_recommendations.legacy_planning_topic.objectives'))[$id];
            case 1:
                return array_values(config('enums.strategy_report_recommendations.tax_efficiency_topic.objectives'))[$id];
            case 2:
                return array_values(config('enums.strategy_report_recommendations.short_term_cash_needs_topic.objectives'))[$id];
            case 3:
                return array_values(config('enums.strategy_report_recommendations.income_needs_topic.objectives'))[$id];
            case 4:
                return array_values(config('enums.strategy_report_recommendations.capital_growth_topic.objectives'))[$id];
            case 5:
                return array_values(config('enums.strategy_report_recommendations.simplify_approach_topic.objectives'))[$id];
            case 6:
                return array_values(config('enums.strategy_report_recommendations.flexibility_topic.objectives'))[$id];
            case 7:
                return array_values(config('enums.strategy_report_recommendations.advice_service_topic.objectives'))[$id];
            case 8:
                return array_values(config('enums.strategy_report_recommendations.repayment_liabilities_topic.objectives'))[$id];
            case 9:
                return array_values(config('enums.strategy_report_recommendations.pension_planning_topic.objectives'))[$id];
            default:
                break;
        }
    }

    private function getWhatFor($id): string
    {
        switch ($id) {
            case 0:
                return array_values(config('enums.strategy_report_recommendations.legacy_planning_topic.what_for'))[$id];
            case 1:
                return array_values(config('enums.strategy_report_recommendations.tax_efficiency_topic.what_for'))[$id];
            case 2:
                return array_values(config('enums.strategy_report_recommendations.short_term_cash_needs_topic.what_for'))[$id];
            case 3:
                return array_values(config('enums.strategy_report_recommendations.income_needs_topic.what_for'))[$id];
            case 4:
                return array_values(config('enums.strategy_report_recommendations.capital_growth_topic.what_for'))[$id];
            case 5:
                return array_values(config('enums.strategy_report_recommendations.simplify_approach_topic.what_for'))[$id];
            case 6:
                return array_values(config('enums.strategy_report_recommendations.flexibility_topic.what_for'))[$id];
            case 7:
                return array_values(config('enums.strategy_report_recommendations.advice_service_topic.what_for'))[$id];
            case 8:
                return array_values(config('enums.strategy_report_recommendations.repayment_liabilities_topic.what_for'))[$id];
            case 9:
                return array_values(config('enums.strategy_report_recommendations.pension_planning_topic.what_for'))[$id];
            default:
                break;
        }
    }
}
