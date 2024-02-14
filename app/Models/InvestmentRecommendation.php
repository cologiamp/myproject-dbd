<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use App\Models\Presenters\InvestmentRecommendationPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InvestmentRecommendation extends Model
{
    protected $guarded = [];

    public function clients():BelongsToMany
    {
        return $this->belongsToMany(Client::class);
    }

    //Presenter
    public function presenter() : InvestmentRecommendationPresenter
    {
        return new InvestmentRecommendationPresenter($this);
    }

    public function loadEnumsForStep($step,$section)
    {
        return match ($step.'.'.$section){
            '1.1' => [
                'fee_basis' => config('enums.investment_recommendation.fee_basis')
            ],
            default => [

            ]
        };
    }
}
