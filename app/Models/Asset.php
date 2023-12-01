<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Asset extends Model
{
    protected $guarded = [];
    public function clients():BelongsToMany
    {
        return $this->belongsToMany(Client::class);
    }
}
