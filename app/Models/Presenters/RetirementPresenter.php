<?php

namespace App\Models\Presenters;

use App\Concerns\FormatsCurrency;
use App\Models\Asset;
use App\Models\Dependent;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Depends;

class RetirementPresenter extends BasePresenter
{

    public function formatForPOStep($step):array
    {
        return match ($step) {
            1 => [
                'intended_retirement' => Carbon::parse($this->model->intended_retirement_at)->diffInYears(Carbon::now())
                //Ignacio: Define 1, 2, 3 here and load the right data to "rehydrate" the form from the saved model for all the fields :)
            ],
            default => [

            ]
        };
    }
}
