<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use App\Models\Presenters\RetirementPresenter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retirement extends Model
{
    protected $guarded = [];

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }


    public function loadEnumsForPOStep($step)
    {
        return match ($step){
            1 => [
                'income_options' => config('enums.pension_objectives.income_option'),
                'lifetime_allowance_protection' => config('enums.pension_objectives.lifetime_allowance_protection'),
            ],
            2 => [
                'income_options' => config('enums.pension_objectives.income_option'),
                'titles' => config('enums.client.title'),
                'if_experience_self_select' => config('enums.pension_objectives.if_experience_self_select'),
                'if_experience_lifestyle' => config('enums.pension_objectives.if_experience_lifestyle'),
                'if_experience_advisory' => config('enums.pension_objectives.if_experience_advisory'),
                'if_experience_discretionary' => config('enums.pension_objectives.if_experience_discretionary'),
                'preferred_option' => config('enums.pension_objectives.preferred_option'),
                'retirement_vs_legacy' => config('enums.pension_objectives.retirement_vs_legacy'),
            ],
            3 => [
                'proportion_of_total_funds' => config('enums.pension_objectives.proportion_of_total_funds'),
                'spouse_lump_sum_death' => config('enums.pension_objectives.spouse_lump_sum_death'),
                'tax_free_lump_sum_preference' => config('enums.pension_objectives.tax_free_lump_sum_preference'),
                'lump_sum_death_benefits' => config('enums.pension_objectives.lump_sum_death_benefits'),
            ],
            default => [
                'fish' => 'cake'
            ]
        };
    }


    public function presenter() : RetirementPresenter
    {
        return new RetirementPresenter($this);
    }
}
