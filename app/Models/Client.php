<?php

namespace App\Models;

use App\Concerns\ParsesIoClientData;
use Carbon\Carbon;
use App\Models\BaseModels\Model;
use Illuminate\Support\Facades\Log;
use App\Models\Presenters\ClientPresenter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
            set: function ($value){
                return Carbon::parse($value);
            }
        );
    }


    public function ioJson():Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, 1),
            set: fn($value) => json_encode($value)
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

    public function health():HasOne
    {
        return $this->hasOne(Health::class);
    }

    public function dependents():BelongsToMany
    {
        return $this->belongsToMany(Dependent::class)->withPivot('relationship_type');
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

    public function adviser(): BelongsTo
    {
        return $this->belongsTo(User::class,'adviser_id');
    }


    //Presenter
    public function presenter() : ClientPresenter
    {
        return new ClientPresenter($this);
    }

    //This is where you load the fact find enums
    //FactFind:// Need to do this for every section/step
    public function loadEnumsForStep($step,$section)
    {
        return match ($step.'.'.$section){
            '1.1' => [
                'titles' => config('enums.client.title'),
                'genders' => config('enums.client.gender'),
                'marital_statuses' => config('enums.client.marital_status'),
                'nationalities' => config('enums.client.nationality') ,
                'country_of_domiciles' => config('enums.client.iso_2'),
                'country_of_residences' => config('enums.client.iso_2')
            ],
            '1.3' => [
                'residency_status' => collect(config('enums.address.residency_status_public')),
                'countries' => config('enums.address.country')
            ],
            '1.4' => [
                'relationship_type' => config('enums.dependent.relationship_type')
            ],
            '1.5' => [
                'employment_status' => config('enums.employment.employment_status')
            ],
            default => [

            ]
        };
    }
    public function getDirtyChanges():Collection
    {
        if($this->io_json)
        {
            $parsed_data = array_merge($this->parseClientData($this->io_json)['client']);

            $diff_data = Client::whereId($this->id)->select( //THE ELEMENTS THAT ARE PULLED FROM IO INITIALLY
                [
                    'title',
                    'first_name',
                    'last_name',
                    'date_of_birth',
                    'gender',
                    'marital_status',
                    'nationality',
                    'salutation',
                    'email_address',
                    'phone_number'
                ]
            )->first()->toArray();


            //this needs to list the fields that are different
            return collect(array_keys(array_diff_assoc($parsed_data,$diff_data)));
        }
        else return new Collection();
    }
}
