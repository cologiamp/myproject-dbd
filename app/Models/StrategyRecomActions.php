<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategyRecomActions extends Model
{
    protected $guarded = [];
    protected $table = 'strategy_recommendation_actions';

    public function strategy_report_recommendation():BelongsTo
    {
        return $this->belongsTo(StrategyReportRecommendation::class);
    }
}
