<?php

namespace App\Models\Presenters;

use App\Concerns\FormatsCurrency;

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
                        'is_primary' => $item->is_primary,
                        'type' => $item->type,
                        'objective' => $item->objective,
                        'objective_custom' => $item->objective_custom,
                        'what_for' => $item->what_for,
                        'what_for_custom' => $item->what_for_custom,
                        'order' => $item->order
                    ];
                }),
            ],
            3 => [
                'actions' => $this->model->actions()->orderBy('order', 'asc')->get()->map(function ($item){
                    return [
                        'id' => $item->id,
                        'client_id' => $item->client_id,
                        'strategy_report_recommendation_id' => $item->strategy_report_recommendation_id,
                        'call_to_action' => $item->call_to_action,
                        'call_to_action_custom' => $item->call_to_action_custom,
                        'order' => $item->order
                    ];
                }),
            ],
            default => [
            ]
        };
    }
}
