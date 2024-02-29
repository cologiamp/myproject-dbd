<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Presenters\StrategyReportRecommendationsPresenter;

class StrategyRecomObjectives extends Model
{
    protected $guarded = [];
    protected $table = 'strategy_recommendation_objectives';

    public function strategy_report_recommendation():BelongsTo
    {
        return $this->belongsTo(StrategyReportRecommendation::class);
    }
}
