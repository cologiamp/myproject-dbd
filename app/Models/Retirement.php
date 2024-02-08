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
            '1' => [
                //Ignacio: Define 1, 2, 3 here and load the right enums for each tab
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
