<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CFLQuestion extends Model
{
    protected $guarded = [];
    public function risk_profile():BelongsTo
    {
        return $this->belongsTo(RiskProfile::class);
    }
}
