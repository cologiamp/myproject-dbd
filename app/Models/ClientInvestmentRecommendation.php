<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientInvestmentRecommendation extends Model
{
    public function investment_recommendation():BelongsTo
    {
        return $this->belongsTo(InvestmentRecommendation::class);
    }
}
