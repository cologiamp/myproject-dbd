<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategyRecommendationObjective extends Model
{
    protected $guarded = [];

    public function strategy_report_recommendation():BelongsTo
    {
        return $this->belongsTo(StrategyReportRecommendation::class);
    }
    public function getFormattedTypeAttribute():string
    {
        return config('enums.strategy_report_recommendations.topic')[$this->type]['name'];
    }
    public function getFormattedObjectiveAttribute():string
    {
        return str_replace('XX',$this['objective_custom'],str_replace('£XX',$this['objective_custom'],config('enums.strategy_report_recommendations.topic')[$this->type]['objectives'][$this->objective]));
    }

    public function getFormattedWhatForAttribute():string
    {
        return str_replace('XX',$this['what_for_custom'],config('enums.strategy_report_recommendations.topic')[$this->type]['what_for'][$this->what_for]);
    }
    public function getIconAttribute():string
    {
        return config('constants.cdn_url').config('enums.strategy_report_recommendations.topic')[$this->type]['icon'];
    }
}
