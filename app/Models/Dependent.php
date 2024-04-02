<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dependent extends Model
{
    protected $guarded = [];
    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class)->withPivot('relationship_type');
    }

    protected $casts = [
        'name' => 'string',
        'born_at' => 'datetime',
        'financially_dependent_until' => 'datetime',
        'is_living_with_clients' => 'boolean',
        'financial_dependent' => 'boolean',
    ];
}
