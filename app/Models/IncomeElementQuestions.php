<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncomeElementQuestions extends Model
{
    public function risk_profile():BelongsTo
    {
        return $this->belongsTo(RiskProfile::class);
    }
}
