<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class LumpSumCapital extends Model
{
    protected $guarded = [];

    protected $casts = [
        'due_at' => 'datetime',
    ];

    /**
     * Clients -> LSC is a many-to-many relation
     * @return BelongsToMany
     */
    public function clients():BelongsToMany
    {
        return $this->belongsToMany(Client::class);
    }
}
