<?php

namespace App\Models;

use App\Models\BaseModels\Model;

class RiskOutcome extends Model
{
    protected $guarded = [];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
