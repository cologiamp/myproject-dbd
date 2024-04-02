<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OtherInvestment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'maturity_date' => 'date',
    ];

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

}
