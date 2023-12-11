<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expenditure extends Model
{
    public function advice_case():BelongsTo
    {
        return $this->belongsTo(AdviceCase::class);
    }
}
