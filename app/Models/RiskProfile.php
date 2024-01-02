<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RiskProfile extends Model
{
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function c_f_l_questions():HasMany
    {
        return $this->hasMany(CFLQuestion::class);
    }
}
