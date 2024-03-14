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
    //Todo: GetObjective and GetWhatFor here from ObjectivesTable.vue
    public function getFormattedObjectiveAttribute():string
    {
        //todo
        return '';
    }
    public function getFormattedWhatForAttribute():string
    {
        //todo
        return '';
    }
}
