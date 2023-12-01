<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class IncomeChange extends Model
{
    public function case():BelongsTo
    {
        return $this->belongsTo(AdviceCase::class);
    }
}
