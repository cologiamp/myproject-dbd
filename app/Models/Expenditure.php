<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Presenters\ExpenditurePresenter;

class Expenditure extends Model
{
    protected $guarded = [];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

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

    //Presenter
    public function presenter() : ExpenditurePresenter
    {
        return new ExpenditurePresenter($this);
    }
}
