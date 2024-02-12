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
                //****Ignacio: Define 1, 2, 3 here and load the right enums for each tab
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
            '1.1' => [
                'titles' => config('enums.client.title'),
                'genders' => config('enums.client.gender'),
                'marital_statuses' => config('enums.client.marital_status'),
                'nationalities' => config('enums.client.nationality') ,
                'country_of_domiciles' => config('enums.client.iso_2'),
                'country_of_residences' => config('enums.client.iso_2')
            ],
            default => [

            ]
        };
    }


    public function presenter() : RetirementPresenter
    {
        return new RetirementPresenter($this);
    }
}
