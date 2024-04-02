<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Health extends Model
{
    protected $guarded = [];

    protected $table = 'health';

    protected $casts = [
        'is_in_good_health' => 'boolean',
        'has_life_expectancy_concerns' => 'boolean',
        'smoker' => 'boolean',
        'smoked_in_last_12_months' => 'boolean',
    ];

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
