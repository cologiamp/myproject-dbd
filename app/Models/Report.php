<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Report extends Model
{
    public function cases():BelongsToMany
    {
        return $this->belongsToMany(AdviceCase::class,'advice_advice_case');
    }
}
