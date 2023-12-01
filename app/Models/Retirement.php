<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Retirement extends Model
{
    public function clients():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
