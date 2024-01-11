<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AdviceCase extends Model
{
    protected $guarded = [];

    public function clients():HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function advice():BelongsToMany
    {
        return $this->belongsToMany(Advice::class);
    }

    public function reports():BelongsToMany
    {
        return $this->belongsToMany(Report::class);
    }


    public function expenditure():HasMany
    {
        return $this->hasMany(Expenditure::class);
    }
    public function income_changes():HasMany
    {
        return $this->hasMany(IncomeChange::class);
    }

    public function getStatusTextAttribute()
    {
        // chore: need to add status column to advice_cases table
        return "To Be Implemented";
    }

}
