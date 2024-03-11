<?php

namespace App\Observers;

use App\Models\PensionRecommendation;

class PensionRecommendationObserver
{
    /**
     * Handle the PensionRecommendation "created" event.
     */
    public function created(PensionRecommendation $pensionRecommendation): void
    {
        //
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
