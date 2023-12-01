<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Advice extends Model
{
    protected $guarded = [];
    public function advice_cases():BelongsToMany
    {
        return $this->belongsToMany(AdviceCase::class,'advice_advice_case');
    }
}
