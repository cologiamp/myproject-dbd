<?php

namespace App\Models;

use App\Concerns\AccessesIoProviders;
use App\Concerns\ParsesIoClientData;
use App\Jobs\CacheProviders;
use Carbon\Carbon;
use App\Models\BaseModels\Model;
use Illuminate\Support\Facades\Cache;
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
    use ParsesIoClientData, AccessesIoProviders;
    protected $guarded = [];
    protected $appends = ['name_with_c2'];

    protected $casts = [
        'valid_will' => 'boolean',
        'will_up_to_date' => 'boolean',
        'poa_granted' => 'boolean',
    ];

    /**
     * Return the formatted attribute "title" according to the config enum;
     * @return null|string
     */
    public function getFormattedTitleAttribute():null|string
    {
        return $this->title != null ? config('enums.client.title')[$this->title] : null;
    }
    public function getFullNameAttribute(): string
    {
        if($this->middle_name && strlen($this->middle_name) > 0)
        {
            return $this->preferred_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        }
        return $this->preferred_name . ' ' . $this->last_name;
    }

    public function client_two(): BelongsTo
    {
        return $this->belongsTo(Client::class,'c2_id','id');
    }

    //Client Two functions
    public function getDashboardTitleAttribute(): string
    {
        return 'Welcome to ' . $this->getNameWithC2Attribute() . "'s dashboard";
    }
    public function getNameWithC2Attribute():string //->name_with_c2
    {
        return $this->client_two ? $this->name . " & ". $this->client_two->name :  $this->name;
    }

    public function getPreferredNameAttribute():string
    {
        return $this->salutation ?: $this->first_name;
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


    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_of_birth)->diffInYears(Carbon::now());
    }

    public function getJobTitleAttribute()
    {
        // chore: can we get this from IO
        if($this->employment_details->first() != null)
        {
            return $this->employment_details->first()->occupation;
        }
        else{
            return "";
        }
    }

    public function getLastUpdatedAttribute()
    {
        // chore: can we get this from IO
        return $this->updated_at->diffForHumans();
    }

    public function getStatusTextAttribute()
    {
        return config('enums.client.statuses')[$this->status_int];
    }

    public function getStatusIntAttribute(): int
    {
        if($this->complete === 1)
        {
            return 4;
        }
        elseif($this->incomes->count() > 0 || $this->assets->count() > 0 || $this->liabilities->count() > 0 || $this->dependents->count() > 0 || $this->employment_details->count() > 0){
            return 1;
        }
        else{
            return 0;
        }
    }


    public function advice_case():BelongsTo
    {
        return $this->belongsTo(AdviceCase::class, "case_id");
    }



    //Relations

    public function assets():BelongsToMany
    {
        return $this->belongsToMany(Asset::class)->withPivot('percent_ownership');
    }
    public function liabilities():BelongsToMany
    {
        return $this->belongsToMany(Liability::class);
    }

    public function incomes():BelongsToMany
    {
        return $this->belongsToMany(Income::class)->withPivot('is_primary');
    }


    public function addresses():BelongsToMany
    {
        return $this->belongsToMany(Address::class)->withPivot('percent_ownership');
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

    public function other_investments():HasMany
    {
        return $this->hasMany(OtherInvestment::class);
    }

    public function pension_schemes():HasMany
    {
        return $this->hasMany(PensionScheme::class);
    }

    public function strategy_reports():HasMany
    {
        return $this->hasMany(StrategyReport::class);
    }
    /**
     * @return HasMany
     */
    public function share_save_schemes():HasMany
    {
        return $this->hasMany(ShareSaveScheme::class);
    }

    /**
     * @return BelongsToMany
     */
    public function lump_sum_capitals():BelongsToMany
    {
        return $this->belongsToMany(LumpSumCapital::class);
    }

    public function expenditures():BelongsToMany
    {
        return $this->belongsToMany(Expenditure::class);
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
    public function knowledge(): HasMany
    {
        return $this->hasMany(Knowledge::class);
    }
    public function retirement(): HasOne
    {
        return $this->hasOne(Retirement::class);
    }

    public function adviser(): BelongsTo
    {
        return $this->belongsTo(User::class,'adviser_id');
    }

    public function strategy_report_recommendation():BelongsTo
    {
        return $this->belongsTo(StrategyReportRecommendation::class);
    }

    public function investment_recommendation():HasOne
    {
        return $this->hasOne(InvestmentRecommendation::class);
    }

    public function investment_recommendation_items():BelongsToMany
    {
        return $this->belongsToMany(InvestmentRecommendationItem::class);
    }

    public function pension_recommendation():HasOne
    {
        return $this->hasOne(PensionRecommendation::class);
    }

    public function capacity_for_loss(): HasMany
    {
        return $this->hasMany(CapacityForLoss::class);
    }

    public function risk_profile(): HasOne
    {
        return $this->hasOne(RiskProfile::class);
    }

    public function risk_outcome(): HasOne
    {
        return $this->hasOne(RiskOutcome::class);
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
                'countries' => collect(collect(config('enums.address.country')))->mapWithKeys(function ($item, $key){
                    return ['0'.$key => $item];//hack to allow reordering of JS objects with string keys
                }),
                'owners' => $this->getOwnersForForm(),
            ],
            '1.4' => [
                'relationship_type' => config('enums.dependent.relationship_type'),
                'owners' => $this->getOwnersForForm(true),
            ],
            '1.5' => [
                'employment_status' => config('enums.employment.employment_status'),
                'owners' => $this->getOwnersForForm(true),

            ],
            '2.1' => [
                'income_types' => config('enums.incomes.income_type'),
                'frequencies' => collect(config('enums.incomes.frequency_public')),
                'per_year_frequencies' => collect(config('enums.incomes.per_year_frequency')),
                'owners' => $this->getOwnersForForm(),
            ],
            '2.2' => [
                'per_year_frequencies' => collect(config('enums.incomes.per_year_frequency'))
            ],
            '2.3' => [
                'expenditure_types' => config('enums.expenditures.basic_essential_expenditure'),
                'per_year_frequencies' => collect(config('enums.incomes.per_year_frequency')),
                'frequencies' => collect(config('enums.incomes.frequency_public')),
                'owners' => $this->getOwnersForForm(),
            ],
            '2.4' => [
                'expenditure_types' => config('enums.expenditures.basic_quality_of_living_expenditure'),
                'per_year_frequencies' => collect(config('enums.incomes.per_year_frequency')),
                'frequencies' => collect(config('enums.incomes.frequency_public')),
                'owners' => $this->getOwnersForForm(),
            ],
            '2.5' => [
                'expenditure_types' => config('enums.expenditures.non_essential_outgoings_expenditure'),
                'per_year_frequencies' => collect(config('enums.incomes.per_year_frequency')),
                'frequencies' => collect(config('enums.incomes.frequency_public')),
                'owners' => $this->getOwnersForForm(),
            ],
            '2.6' => [
                'expenditure_types' => config('enums.expenditures.liability_expenditure'),
                'per_year_frequencies' => collect(config('enums.incomes.per_year_frequency')),
                'frequencies' => collect(config('enums.incomes.frequency_public')),
                'owners' => $this->getOwnersForForm(),
            ],
            '2.7' => [
                'expenditure_types' => config('enums.expenditures.lump_sum_expenditure'),
                'per_year_frequencies' => collect(config('enums.incomes.per_year_frequency')),
                'frequencies' => collect(config('enums.incomes.frequency_public')),
                'owners' => $this->getOwnersForForm(),
            ],
            '3.1' => [
                'owners' => $this->getOwnersForForm(),
                'asset_types' => config('enums.assets.types_public_no_cash_invest')
            ],
            '3.2' => [
                'owners' => $this->getOwnersForForm(),
                'providers' => array_values($this->getProviders()->take(100)->toArray()), //Note: change here
                'account_types' => config('enums.assets.account_types'),
                'employer_contribution_frequencies' => collect(config('enums.assets.frequency')),
            ],
            '3.3' => [
                'owners' => $this->getOwnersForForm(true),
                'providers' =>  array_values($this->getProviders()->take(100)->toArray()),
                'account_types' => config('enums.assets.investment_account_types'),
                'frequencies' => config('enums.assets.frequency_public'),
            ],
            '3.4' => [
                'owners' => $this->getOwnersForForm(true),
                'pension_statuses' => config('enums.assets.db_pension_statuses'),
                'pension_types' => config('enums.assets.dc_pension_types'),
                'pension_crystallised_statuses' => config('enums.assets.pension_crystallised_statuses'),
                'pension_fund_types' => config('enums.assets.pension_fund_types'),
                'administrators' =>  array_values($this->getProviders()->take(100)->toArray()),
                'frequencies' => collect(config('enums.assets.frequency')),
                'loa_submitted' => config('enums.pension_recommendation.loa_submitted'),
                'chosens' => collect(config('enums.assets.chosen')), // chosen dropdown stuff
            ],
            '3.5' => [
                'owners' => $this->getOwnersForForm(true),
            ],
            '3.6' => [
                'owners' => $this->getOwnersForForm(),
            ],
            '4.1' => [
                'owners' => $this->getOwnersForForm(),
                'types' => config('enums.liabilities.types_public'),
                'repayment_or_interest' => config('enums.liabilities.repayment_or_interest'),
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
                    'middle_name',
                    'last_name',
                    'date_of_birth',
                    'gender',
                    'marital_status',
                    'nationality',
                    'salutation',
                    'email_address',
                    'phone_number',
                    'mobile_number',
                ]
            )->first()->toArray();


            //this needs to list the fields that are different
            return collect(array_keys(array_diff_assoc($parsed_data,$diff_data)));
        }
        else return new Collection();
    }
    private function getOwnersForForm($noBoth = false):array
    {
        if($this->client_two)
        {
            $arr = [
                $this->io_id => $this->name,
                $this->client_two->io_id => $this->client_two->name
            ];
            if(!$noBoth)
            {
                $arr['Both'] = 'Both';
            }
            return $arr;
        }
        return [
            $this->io_id => $this->name,
        ];
    }

    public function getBelongsToEnums():Collection
    {
        return collect([
            $this->id => $this->first_name
        ]);
    }

}
