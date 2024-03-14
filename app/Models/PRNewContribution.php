<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PRNewContribution extends Model
{
    protected $guarded = [];

    public function pension_recommendation():BelongsTo
    {
        return $this->belongsTo(PensionRecommendation::class);
    }

}
