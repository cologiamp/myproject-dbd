<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Address extends Model
{
    protected $guarded = [];
    public function clients():BelongsToMany
    {
        return $this->belongsToMany(Client::class)->withPivot('percent_ownership');
    }

    public function formatForStrategyReport():string
    {
        $addr = $this->address_line_1;
        if($this->address_line_2)
        {
            $addr .= ', ' . $this->address_line_2;
        }
        return $addr.', ' . $this->city;
    }
}
