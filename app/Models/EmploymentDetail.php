<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use App\Models\Presenters\EmploymentPresenter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmploymentDetail extends Model
{
    protected $guarded = [];

    public function client():BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function presenter():EmploymentPresenter
    {
        return new EmploymentPresenter($this);
    }

}
