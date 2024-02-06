<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use App\Models\Presenters\AssetPresenter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Asset extends Model
{

    //Existing Accounts + Savings now included here
    protected $guarded = [];
    public function clients():BelongsToMany
    {
        return $this->belongsToMany(Client::class)->withPivot('percent_ownership');
    }
    //Presenter
    public function presenter() : AssetPresenter
    {
        return new AssetPresenter($this);
    }

}
