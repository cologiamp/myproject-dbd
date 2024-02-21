<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvestmentBond extends Model
{
    protected $guarded = [];

    public function client(): BelongsTo
    {
        return $this->belongsTo(InvestmentRecommendation::class,'investment_recommendation_id');
    }
}
