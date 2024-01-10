<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\BaseModels\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Presenters\ClientPresenter;
use App\Models\Presenters\LayoutPresenter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Client extends Model
{
    protected $guarded = [];

    /**
     * Return the formatted attribute "title" according to the config enum;
     * @return null|string
     */
    public function getFormattedTitleAttribute():null|string
    {
        return $this->title != null ? config('enums.client.title')[$this->title] : null;
    }

    public function date_of_birth():Attribute
    {
        return Attribute::make(
            set: fn($value) => Carbon::parse($value),
        );
    }

    public function getNameAttribute()
    {
        return $this->formatted_title . ' ' . $this->first_name . ' ' . $this->last_name;
    }

    public function advice_case():BelongsTo
    {
        return $this->belongsTo(AdviceCase::class, "case_id");
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


    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_of_birth)->diffInYears(Carbon::now());
    }

    public function getJobTitleAttribute()
    {
        // chore: can we get this from IO
        return "To Be Implemented";
    }

    public function getLastContactAttribute()
    {
        // chore: can we get this from IO
        return "To Be Implemented";
    }

    public function getStatusTextAttribute()
    {
        return $this->advice_case?->status_text ?? "Fact Find";
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
