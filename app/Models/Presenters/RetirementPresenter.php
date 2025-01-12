<?php

namespace App\Models\Presenters;

use App\Concerns\FormatsCurrency;
use App\Models\Asset;
use App\Models\Dependent;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Depends;

class RetirementPresenter extends BasePresenter
{
    use FormatsCurrency;
    public function formatForPOStep($step):array
    {
        return match ($step) {
            1 => [
                'intended_retirement' => Carbon::parse($this->model->intended_retirement_at)->diffInYears(Carbon::now()),
                'intended_benefits_drawn' => Carbon::parse($this->model->intended_benefits_drawn_at)->diffInYears(Carbon::now()),
                'income_option' => $this->model->income_option,
                'notes' => $this->model->notes,
                'lifetime_allowance_protection' => json_decode($this->model->lifetime_allowance_protection),
            ],
            2 => [
                'additional_contributions' => (boolean)$this->model->additional_contributions,
                'in_specie_transfers' => (boolean)$this->model->in_specie_transfers,
                'if_experience_self_select' => $this->model->if_experience_self_select,
                'if_experience_lifestyle' => $this->model->if_experience_lifestyle,
                'if_experience_advisory' => $this->model->if_experience_advisory,
                'if_experience_discretionary' => $this->model->if_experience_discretionary,
                'is_explained' => (boolean)$this->model->is_explained,
                'preferred_option' => $this->model->preferred_option,
                'preferred_explanation' => $this->model->preferred_explanation,
                'wide_range_of_assets' => (boolean)$this->model->wide_range_of_assets,
                'include_exclude_specifics' => $this->model->include_exclude_specifics,
                'require_flexibility' => (boolean)$this->model->require_flexibility,
                'retirement_vs_legacy' => $this->model->retirement_vs_legacy,
                'retirement_vs_legacy_specifics' => $this->model->retirement_vs_legacy_specifics,
                'dependents_suffer' => $this->model->dependents_suffer,
                'iht_concerns' => (boolean)$this->model->iht_concerns,
            ],
            3 => [
                'known_income_required' => (boolean)$this->model->known_income_required,
                'prefer_flexibility' => (boolean)$this->model->prefer_flexibility,
                'what_age_annuity' => $this->model->what_age_annuity,
                'proportion_of_total_funds' => $this->model->proportion_of_total_funds,
                'spouse_income_proportion' => (boolean)$this->model->spouse_income_proportion,
                'spouse_lump_sum_death' => $this->model->spouse_lump_sum_death,
                'maximise_lifetime' => (boolean)$this->model->maximise_lifetime,
                'no_spouse' => (boolean)$this->model->no_spouse,
                'spouse_details' => $this->model->spouse_details,
                'tax_free_lump_sum_preference' => $this->model->tax_free_lump_sum_preference,
                'tax_free_lump_sum_value' => $this->model->tax_free_lump_sum_value != null ? $this->currencyIntToString($this->model->tax_free_lump_sum_value): null,
                'lump_sum_death_benefits' => $this->model->lump_sum_death_benefits ?? null,
            ],
            default => [

            ]
        };
    }
}
