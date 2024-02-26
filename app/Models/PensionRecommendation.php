<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use App\Models\Presenters\InvestmentRecommendationPresenter;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
