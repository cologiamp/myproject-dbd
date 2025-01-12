<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use App\Models\Presenters\InvestmentRecommendationPresenter;
use App\Observers\PensionRecommendationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\HasMany;


#[ObservedBy([PensionRecommendationObserver::class])]
class PensionRecommendation extends Model
{
    protected $guarded = [];

    public function clients():HasMany
    {
        return $this->hasMany(Client::class);
    }

    //Presenter
    public function presenter() : InvestmentRecommendationPresenter
    {
        return new InvestmentRecommendationPresenter($this);
    }

    public function getPrimaryClientAttribute()
    {
        if ($this->clients->count() > 1) {
            return $this->clients()->where('c2_id', '!=', null)->first();
        }

        return $this->clients()->first();
    }

    public function pr_new_contributions():HasMany
    {
        return $this->hasMany(PRNewContribution::class);
    }

    public function pr_annual_allowances():HasMany
    {
        return $this->hasMany(PRAnnualAllowance::class);
    }
    public function pr_items():HasMany
    {
        return $this->hasMany(PensionRecommendationItem::class);
    }
}
