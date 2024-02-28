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
            default => [
            ]
        };
    }
}
