<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Health extends Model
{
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
