<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CapacityForLoss extends Model
{
    protected $guarded = [];
    protected $table = 'capacity_for_losses';

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
