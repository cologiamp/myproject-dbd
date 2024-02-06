<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShareSaveScheme extends Model
{
    /**
     * A ShareSaveScheme belongs to one client
     * @return BelongsTo
     */
    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
