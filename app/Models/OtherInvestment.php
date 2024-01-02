<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OtherInvestment extends Model
{
    public function clients():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
