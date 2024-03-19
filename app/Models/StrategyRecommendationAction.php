<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategyRecommendationAction extends Model
{
    protected $guarded = [];

    public function strategy_report_recommendation():BelongsTo
    {
        return $this->belongsTo(StrategyReportRecommendation::class);
    }

    public function getFormattedCtaAttribute(): string
    {
        return str_replace('XXX',$this->call_to_action_custom,config('enums.strategy_report_recommendations.call_to_action_types')[$this->call_to_action]);
    }
}
