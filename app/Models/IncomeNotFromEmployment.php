<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IncomeNotFromEmployment extends Model
{
    public function other_incomes():HasMany
    {
        return $this->hasMany(OtherIncome::class);
    }

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
