<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use App\Models\Presenters\ClientPresenter;
use App\Models\Presenters\LayoutPresenter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Client extends Model
{
    protected $guarded = [];

    //ENUMS
    public function title():Attribute
    {
        return Attribute::make(
            get: fn($value) => config('enums.client.title')[$value],
            set: fn($value) => array_flip(config('enums.client.title'))[$value],
        );
    }

    public function gender():Attribute
    {
        return Attribute::make(
            get: fn($value) => config('enums.client.gender')[$value],
            set: fn($value) => array_flip(config('enums.client.gender'))[$value]
        );
    }

    public function maritalStatus():Attribute
    {
        return Attribute::make(
            get: fn($value) => config('enums.client.marital_status')[$value],
            set: fn($value) => array_flip(config('enums.client.marital_status'))[$value]
        );
    }

    public function nationality():Attribute
    {
        return Attribute::make(
            get: fn($value) => config('enums.client.nationality')[$value],
            set: fn($value) => array_flip(config('enums.client.nationality'))[$value]
        );
    }

    public function getNameAttribute()
    {
        return $this->title . ' ' . $this->first_name . ' ' . $this->last_name;
    }

    public function advice_case():BelongsTo
    {
        return $this->belongsTo(AdviceCase::class);
    }

    public function assets():BelongsToMany
    {
        return $this->belongsToMany(Asset::class);
    }
    public function liabilities():BelongsToMany
    {
        return $this->belongsToMany(Liability::class);
    }

    public function incomes():BelongsToMany
    {
        return $this->belongsToMany(Income::class);
    }



    public function addresses():BelongsToMany
    {
        return $this->belongsToMany(Address::class);
    }


    public function dependents():HasMany
    {
        return $this->hasMany(Dependent::class);
    }

    public function employment_details():HasMany
    {
        return $this->hasMany(EmploymentDetail::class);
    }

    public function existing_account():HasMany
    {
        return $this->hasMany(ExistingAccount::class);
    }

    public function pension_scheme():HasMany
    {
        return $this->hasMany(PensionScheme::class);
    }


    //Extends (Has One)
    public function current_year_finance():HasOne
    {
        return $this->hasOne(CurrentYearFinance::class);
    }
    public function declaration(): HasOne
    {
        return $this->hasOne(Declaration::class);
    }
    public function knowledge(): HasOne
    {
        return $this->hasOne(Knowledge::class);
    }
    public function retirement(): HasOne
    {
        return $this->hasOne(Retirement::class);
    }


    //Presenter
    public function presenter() : ClientPresenter
    {
        return new ClientPresenter($this);
    }
}
