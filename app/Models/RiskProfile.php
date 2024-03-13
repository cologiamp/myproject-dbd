<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Presenters\RiskPresenter;

class RiskProfile extends Model
{
    protected $guarded = [];

    public const RISK_INVESTMENT_TYPE = 0;
    public const RISK_PENSION_TYPE = 1;
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
                'risk_assessment_cash' => config('enums.risk_assessment.cash'),
                'risk_assessment_bonds' => config('enums.risk_assessment.bonds'),
                'risk_assessment_equities' => config('enums.risk_assessment.equities'),
                'risk_assessment_insurance' => config('enums.risk_assessment.insurance')
            ],
            '1.3' => [
                'risk_assessment_volatility' => config('enums.risk_assessment.short_term_volatility')
            ],
            '2.1' => [
                'risk_assessment_cash' => config('enums.risk_assessment.cash'),
                'risk_assessment_bonds' => config('enums.risk_assessment.bonds'),
                'risk_assessment_equities' => config('enums.risk_assessment.equities'),
                'risk_assessment_insurance' => config('enums.risk_assessment.insurance'),
                'risk_assessment_retirement_options' => config('enums.risk_assessment.retirement_options')
            ],
            '2.3' => [
                'risk_assessment_volatility' => config('enums.risk_assessment.short_term_volatility')
            ],
            default => [

            ]
        };
    }
}
