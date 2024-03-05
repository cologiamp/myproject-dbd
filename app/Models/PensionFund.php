<?php

namespace App\Models;

use App\Models\BaseModels\Model;

class PensionFund extends Model
{
    public function defined_contribution_pension(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(DefinedContributionPension::class);
    }
}
