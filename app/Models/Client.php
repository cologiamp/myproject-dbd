<?php

namespace App\Models;

use App\Concerns\ParsesIoClientData;
use App\Models\BaseModels\Model;
use App\Models\Presenters\ClientPresenter;
use App\Models\Presenters\LayoutPresenter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;

class Client extends Model
{
    use ParsesIoClientData;
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


    public function ioJson():Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value),
            set: fn($value) => json_encode($value)
        );
    }

    public function getNameAttribute()
    {
        return $this->formatted_title . ' ' . $this->first_name . ' ' . $this->last_name;
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

    //This is where you load the fact find enums
    //FactFind:// Need to do this for every section/step
    public function loadEnumsForStep($section,$step)
    {
        return match ($section.'.'.$step){
            '1.1' => [
                'titles' => config('enums.client.title')
            ],
            default => [

            ]
        };
    }
    public function getDirtyChanges():Collection
    {
        if($this->io_json)
        {
            $parsed_data = $this->parseClientFields($this->io_json['person']);
            $diff_data = $this->pluck(
                'title',
                'first_name',
                'last_name',
                'date_of_birth',
                'gender',
                'marital_status',
                'nationality',
                'salutation'
            );
            dd($parsed_data,$diff_data);
            //this needs to list the fields that are different

        }
        else return new Collection();

    }
}
