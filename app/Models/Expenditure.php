<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Presenters\ExpenditurePresenter;

class Expenditure extends Model
{
    protected $guarded = [];

    public function clients():BelongsToMany
    {
        return $this->belongsToMany(Client::class);
    }

    /**
     * Filter expenditures by type
     */
    public function scopeInConfigSection(Builder $query, string $expenditureType): Builder
    {
        return $query->whereIn('type', collect(config('enums.expenditures.' . $expenditureType))->keys()->all());
    }



    public function getGrossAnnualAmountAttribute(): float|int
    {
        //frequency defaults to "annually"
        return $this->amount * config('enums.incomes.per_year_frequency')[$this->frequency ?? 0];
    }
    //Presenter
    public function presenter() : ExpenditurePresenter
    {
        return new ExpenditurePresenter($this);
    }
}
