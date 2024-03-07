<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Presenters\RiskPresenter;

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

    public function getPrimaryClientAttribute()
    {
        if ($this->clients->count() > 1) {
            return $this->clients()->where('c2_id', '!=', null)->first();
        }

        return $this->clients()->first();
    }

    public function presenter() : RiskPresenter
    {
        return new RiskPresenter($this);
    }

    public function loadEnumsForStep($step,$section)
    {
        return match ($step.'.'.$section) {
            '1.1' => [
                'fee_basis' => config('enums.investment_recommendation.fee_basis')
            ],
            default => [

            ]
        };
    }
}
