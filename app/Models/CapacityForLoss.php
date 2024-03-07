<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapacityForLoss extends Model
{
    protected $guarded = [];
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
