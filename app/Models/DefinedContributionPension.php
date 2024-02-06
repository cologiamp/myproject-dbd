<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DefinedContributionPension extends Model
{
    public function pension_scheme():BelongsTo
    {
        return $this->belongsTo(PensionScheme::class);
    }

}
