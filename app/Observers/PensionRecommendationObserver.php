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

    /**
     * Handle the PensionRecommendation "updated" event.
     */
    public function updated(PensionRecommendation $pensionRecommendation): void
    {
        //
    }

    /**
     * Handle the PensionRecommendation "deleted" event.
     */
    public function deleted(PensionRecommendation $pensionRecommendation): void
    {
        //
    }

    /**
     * Handle the PensionRecommendation "restored" event.
     */
    public function restored(PensionRecommendation $pensionRecommendation): void
    {
        //
    }

    /**
     * Handle the PensionRecommendation "force deleted" event.
     */
    public function forceDeleted(PensionRecommendation $pensionRecommendation): void
    {
        //
    }
}
