<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dependent extends Model
{
    protected $guarded = [];
    protected $table = 'dependents';

    public function clientDepent():BelongsTo
    {
        return $this->belongsTo(ClientDependent::class);
    }
}
