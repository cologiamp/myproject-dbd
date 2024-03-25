<?php

namespace App\Observers;

use App\Models\PensionRecommendation;
use App\Concerns\AccessesTaxAllowances;
use App\Models\PRAnnualAllowance;

class PensionRecommendationObserver
{
    use AccessesTaxAllowances;
    /**
     * Handle the PensionRecommendation "created" event.
     */
    public function created(PensionRecommendation $pensionRecommendation): void
    {
        $this->getTaxYears()->map(function($item) use ($pensionRecommendation) {
            $allowance = [
                "pension_recommendation_id" => $pensionRecommendation["id"],
                "tax_year" => $item["tax_year"],
                "annual_allowance" => $item["allowance"],
            ];
            PRAnnualAllowance::create($allowance);
        });
    }
}
