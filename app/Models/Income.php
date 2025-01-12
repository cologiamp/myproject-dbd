<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Income extends Model
{
    protected $guarded = [];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
    ];

    public function clients():BelongsToMany
    {
        return $this->belongsToMany(Client::class)->withPivot('is_primary');
    }
    public function getGrossAnnualAmountAttribute()
    {
        return $this->gross_amount * config('enums.incomes.per_year_frequency')[$this->frequency];
    }
}
