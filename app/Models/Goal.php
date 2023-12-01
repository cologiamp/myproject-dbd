<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Goal extends Model
{
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
