<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StrategyReport extends Model
{
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
    public function adviser():BelongsTo
    {
        return $this->belongsTo(User::class,'adviser_id');
    }
}
