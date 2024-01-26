<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Income extends Model
{
    protected $guarded = [];
    
    public function clients():BelongsToMany
    {
        return $this->belongsToMany(Client::class)->withPivot('is_primary');;
    }
}
