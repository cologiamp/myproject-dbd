<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtherIncome extends Model
{
    public function income_not_from_employment():BelongsTo
    {
        return $this->belongsTo(IncomeNotFromEmployment::class);
    }
}
