<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Expenditure extends Model
{
    public function clients():BelongsToMany
    {
        return $this->belongsToMany(Client::class);
    }
}
