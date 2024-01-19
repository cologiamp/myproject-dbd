<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientDependent extends Model
{
    protected $guarded = [];
    protected $table = 'client_dependent';

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function dependents():HasMany
    {
        return $this->hasMany(Dependent::class);
    }
}
