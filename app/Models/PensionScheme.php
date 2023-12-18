<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PensionScheme extends Model
{
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function defined_benefit_pension(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DefinedBenefitPension::class);
    }
    public function defined_contribution_pension(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(DefinedContributionPension::class);
    }

}
