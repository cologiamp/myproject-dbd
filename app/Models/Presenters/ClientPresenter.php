<?php

namespace App\Models\Presenters;

use App\Concerns\FormatsCurrency;
use App\Models\Asset;
use App\Models\PensionScheme;
use Carbon\Carbon;

class ClientPresenter extends BasePresenter
{
    use FormatsCurrency;
    public function index():array
    {
        return array_merge($this->default(),
            [
                'name' => $this->model->name
            ]
        );
    }

    public function formatForExampleForm(): array
    {
        return [
            'first_name'=> $this->model->first_name,
            'last_name'=> $this->model->last_name,
            'title' => $this->model->title
        ];
    }

    //FactFind:// Need to do this for every section/step
    //Chore: this should probably be refactored to take some of the non-client stuff out of the client model.
    public function formatForStep($step,$section)
    {
        if($this->model->client_two)
        {
            $fixed_assets_multiple = $this->model->assets->where('category',array_flip(config('enums.assets.categories'))['fixed_assets'])->pluck('id')->merge($this->model->client_two->assets->where('category',array_flip(config('enums.assets.categories'))['fixed_assets'])->pluck('id'));
            $savings_assets_multiple = $this->model->assets->where('category',array_flip(config('enums.assets.categories'))['savings'])->pluck('id')->merge($this->model->client_two->assets->where('category',array_flip(config('enums.assets.categories'))['savings'])->pluck('id'));
            return match ($step . '.' . $section) {
                '1.1' => [
                    $this->model->io_id => [
                        'first_name' => $this->model->first_name,
                        'middle_name' => $this->model->middle_name,
                        'last_name' => $this->model->last_name,
                        'salutation' => $this->model->salutation,
                        'title' => $this->model->title,
                        'date_of_birth' => $this->model->date_of_birth,
                        'gender' => $this->model->gender,
                        'marital_status' => $this->model->marital_status,
                        'nationality' => $this->model->nationality,
                        'ni_number' => $this->model->ni_number,
                        'country_of_domicile' => $this->model->country_of_domicile != null ? config('enums.client.iso_2_int')[$this->model->country_of_domicile] : null,
                        'country_of_residence' => $this->model->country_of_residence != null ? config('enums.client.iso_2_int')[$this->model->country_of_residence] : null,
                        'valid_will' => (boolean)$this->model->valid_will,
                        'will_up_to_date' => (boolean)$this->model->will_up_to_date,
                        'poa_granted' => (boolean)$this->model->poa_granted,
                        'poa_name' => $this->model->poa_name
                    ],
                    $this->model->client_two->io_id => [
                        'first_name' => $this->model->client_two->first_name,
                        'middle_name' => $this->model->client_two->middle_name,
                        'last_name' => $this->model->client_two->last_name,
                        'salutation' => $this->model->client_two->salutation,
                        'title' => $this->model->client_two->title,
                        'date_of_birth' => $this->model->client_two->date_of_birth,
                        'gender' => $this->model->client_two->gender,
                        'marital_status' => $this->model->client_two->marital_status,
                        'nationality' => $this->model->client_two->nationality,
                        'ni_number' => $this->model->client_two->ni_number,
                        'country_of_domicile' => $this->model->client_two->country_of_domicile != null ? config('enums.client.iso_2_int')[$this->model->client_two->country_of_domicile] : null,
                        'country_of_residence' => $this->model->client_two->country_of_residence != null ? config('enums.client.iso_2_int')[$this->model->client_two->country_of_residence] : null,
                        'valid_will' => (boolean)$this->model->client_two->valid_will,
                        'will_up_to_date' => (boolean)$this->model->client_two->will_up_to_date,
                        'poa_granted' => (boolean)$this->model->client_two->poa_granted,
                        'poa_name' => $this->model->client_two->poa_name
                    ]

                ],
                '1.2' => [
                    $this->model->io_id => [
                        'is_in_good_health' => (bool) $this->model->health?->is_in_good_health,
                        'health_details' => $this->model->health?->health_details,
                        'has_life_expectancy_concerns' => (bool) $this->model->health?->has_life_expectancy_concerns,
                        'life_expectancy_details' => $this->model->health?->life_expectancy_details,
                        'medical_conditions' => $this->model->health?->medical_conditions,
                        'smoker' => $this->model->health?->smoker,
                        'smoked_in_last_12_months' => (bool) $this->model->health?->smoked_in_last_12_months
                    ],
                    $this->model->client_two->io_id => [
                        'is_in_good_health' => (bool) $this->model->client_two->health?->is_in_good_health,
                        'health_details' => $this->model->client_two->health?->health_details,
                        'has_life_expectancy_concerns' => (bool) $this->model->client_two->health?->has_life_expectancy_concerns,
                        'life_expectancy_details' => $this->model->client_two->health?->life_expectancy_details,
                        'medical_conditions' => $this->model->client_two->health?->medical_conditions,
                        'smoker' => $this->model->client_two->health?->smoker,
                        'smoked_in_last_12_months' => (bool) $this->model->client_two->health?->smoked_in_last_12_months
                    ]
                ],
                '1.3' => [
                    'addresses' => collect($this->model->addresses->merge($this->model->client_two->addresses)->sortBy('id')->values()->map(function ($address){
                        return [
                            'address_id' => $address['id'],
                            'address_line_1' => $address['address_line_1'],
                            'address_line_2' => $address['address_line_2'],
                            'city' => $address['city'],
                            'county' => $address['county'],
                            'postcode' => $address['postcode'],
                            'country' => '0'.$address['country'], //hack to allow reordering of JS objects with string keys
                            'residency_status' => $address['residency_status'],
                            'date_from' => $address['date_from'],
                            'owner' => $address->clients->count() > 1 ? 'Both' : $address->clients->first()->io_id,
                            'percent_ownership' => $address->clients->mapWithKeys(fn($i) => [$i->io_id => $i->pivot->percent_ownership]),
                        ];
                    })),
                    $this->model->io_id => [
                        'phone_number' => $this->model->phone_number,
                        'mobile_number' => $this->model->mobile_number,
                        'email_address' => $this->model->email_address
                    ],
                    $this->model->client_two->io_id => [
                        'phone_number' => $this->model->client_two->phone_number,
                        'mobile_number' => $this->model->client_two->mobile_number,
                        'email_address' => $this->model->client_two->email_address
                    ],
                ],
                '1.4' => [
                    'dependents' => collect($this->model->dependents->merge($this->model->client_two->dependents)->sortBy('id')->values()->map(function ($dependent){
                        return [
                            'dependent_id' => $dependent->id,
                            'name' => $dependent->name,
                            'relationships' => [
                                $this->model->io_id => [
                                    'is_related' => $this->model->dependents->contains($dependent->id),
                                    'relationship_type' =>
                                        $this->model->dependents->contains($dependent->id) ?
                                            $dependent->clients()->where('clients.id',$this->model->id)->first()->pivot->relationship_type
                                            : null
                                ],
                                $this->model->client_two->io_id => [
                                    'is_related' => $this->model->client_two->dependents->contains($dependent->id),
                                    'relationship_type' =>
                                        $this->model->client_two->dependents->contains($dependent->id) ?
                                            $dependent->clients()->where('clients.id',$this->model->client_two->id)->first()->pivot->relationship_type
                                            : null
                                ]
                            ],
                            'born_at' => $dependent->born_at,
                            'financial_dependent' => (bool)  $dependent->financial_dependent,
                            'financially_dependent_until' => $dependent->financially_dependent_until,
                            'is_living_with_clients' => (bool) $dependent->is_living_with_clients
                    ];
                }))
                ],
                '1.5' => [
                    'employment_details' => collect($this->model->employment_details->merge($this->model->client_two->employment_details)->sortBy('id')->values()->map(function ($employment){
                        return [
                            'id' => $employment->id,
                            'employee' => $employment->client->io_id,
                            'employment_status' => $employment->employment_status,
                            'intended_retirement_age' => $employment->intended_retirement_age,
                            'occupation' => $employment->occupation,
                            'employer' => $employment->employer,
                            'start_at' => $employment->start_at,
                            'end_at' => $employment->end_at
                        ];
                    }))
                ],
                '2.1' => [
                    'incomes' => collect($this->model->incomes->merge($this->model->client_two->incomes))->sortBy('id')->values()->map(function ($income){
                        return [
                            'income_id' => $income->id,
                            'income_type' => $income->category,
                            'gross_amount' => $this->currencyIntToString($income->gross_amount),
                            'net_amount' => $this->currencyIntToString($income->net_amount),
                            'expenses' => $this->currencyIntToString($income->expenses),
                            'frequency' => $income->frequency,
                            'starts_at' => $income->starts_at,
                            'ends_at' => $income->ends_at,
                            'belongs_to' => $income->clients->count() > 1 ? 'Both' : $income->clients->first()->io_id,
                            'record_exists' => $income->pivot->client_id ? true : false,
                        ];
                    }),
                    'useIncome' => count(collect($this->model->incomes->merge($this->model->client_two->incomes))) > 0 ? true : false,
                    'total' => count($this->model->incomes) > 0 ? null : $this->model->total_income_basic
                ],
                '2.2' => [
                    'client_id' => $this->model->io_id,
                    'expenditures' => collect($this->model->expenditures()->inConfigSection('basic_essential_expenditure')->get()->merge($this->model->client_two->expenditures()->inConfigSection('basic_essential_expenditure')->get())->sortBy('id')->values()->map(function ($expenditure){
                        return $expenditure->presenter()->form();
                    }))->groupBy('expenditure_type')
                ],
                '2.3' => [
                    'client_id' => $this->model->io_id,
                    'expenditures' => collect($this->model->expenditures()->inConfigSection('basic_quality_of_living_expenditure')->get()->merge($this->model->client_two->expenditures()->inConfigSection('basic_quality_of_living_expenditure')->get())->sortBy('id')->values()->map(function ($expenditure){
                        return $expenditure->presenter()->form();
                    }))->groupBy('expenditure_type')
                ],
                '2.4' => [
                    'client_id' => $this->model->io_id,
                    'expenditures' => collect($this->model->expenditures()->inConfigSection('non_essential_outgoings_expenditure')->get()->merge($this->model->client_two->expenditures()->inConfigSection('non_essential_outgoings_expenditure')->get())->sortBy('id')->values()->map(function ($expenditure){
                        return $expenditure->presenter()->form();
                    }))->groupBy('expenditure_type')
                ],
                '2.5' => [
                    'client_id' => $this->model->io_id,
                    'expenditures' => collect($this->model->expenditures()->inConfigSection('liability_expenditure')->get()->merge($this->model->client_two->expenditures()->inConfigSection('liability_expenditure')->get())->sortBy('id')->values()->map(function ($expenditure){
                     return $expenditure->presenter()->form();
                    }))->groupBy('expenditure_type')
                ],
                '2.6' => [
                    'client_id' => $this->model->io_id,
                      'expenditures' => collect($this->model->expenditures()->inConfigSection('lump_sum_expenditure')->get()->merge($this->model->client_two->expenditures()->inConfigSection('lump_sum_expenditure')->get())->sortBy('id')->values()->map(function ($expenditure){
                          return $expenditure->presenter()->form();
                    }))->groupBy('expenditure_type')
                ],
                '3.1' => [
                    'fixed_assets' => collect(Asset::with('clients')->whereIn('id',$fixed_assets_multiple)->get()->map(function ($asset){
                        return $asset->presenter()->formatForFactFind('fixed');
                    }))
                ],
                '3.2' => [
                   'saving_assets' => collect(Asset::with('clients')->whereIn('id',$savings_assets_multiple)->get()->map(function ($asset){
                        return $asset->presenter()->formatForFactFind('savings');
                    }))
                ],
                '3.3' => [
                   'investments' => $this->model->other_investments->merge($this->model->client_two->other_investments)->sortBy('id')->values()->map(function ($investment){
                       return [
                          'id' => $investment->id,
                          'owner' => $investment->client->io_id,
                          'provider' => $investment->provider != null ? [
                              'label' => \Cache::get('io_provider_list')[$investment->provider],
                              'value' => $investment->provider
                          ] : null,
                          'account_type' => $investment->contract_type,
                          'product_name' => $investment->product_name,
                          'is_retained' => (bool) $investment->is_retained,
                          'retained_value' =>  $investment->retained_value != null ? $this->currencyIntToString($investment->retained_value): null,
                          'current_value' => $investment->current_value != null ? $this->currencyIntToString($investment->current_value): null,
                          'regular_contribution' =>  $investment->regular_contribution != null ? $this->currencyIntToString($investment->regular_contribution): null,
                          'frequency' => $investment->frequency,
                          'lump_sum_contribution' =>  $investment->lump_sum_contribution  != null ? $this->currencyIntToString($investment->lump_sum_contribution): null,
                          'start_date' =>  $investment->start_date,
                          'maturity_date' =>  $investment->maturity_date,
                          'valuation_at' =>  $investment->valuation_at,
                      ];
                    })
                ],
                '3.4' => [
                    'dc_pensions' => PensionScheme::with('defined_contribution_pension')->whereHas('defined_contribution_pension')->whereIn('client_id',[$this->model->id,$this->model->client_two->id])->get()->map(function ($item){
                         return [
                            'id' => $item->id,
                            'pt' => 'DC',
                            'owner' => $item->client->io_id,
                            'type' => $item->defined_contribution_pension->type,
                            'employer' => $item->employer,
                            'administrator' =>   $item->defined_contribution_pension->administrator != null ?
                                [
                                    'label' => \Cache::get('io_provider_list')[$item->defined_contribution_pension->administrator],
                                    'value' => $item->defined_contribution_pension->administrator
                                ] : null,
                            'policy_starts_at' => $item->defined_contribution_pension->policy_start_at,
                            'policy_number' => $item->defined_contribution_pension->policy_number,
                            'gross_contribution_percent' => $item->defined_contribution_pension->gross_contribution_percent,
                            'gross_contribution_absolute' => $item->defined_contribution_pension->gross_contribution_absolute != null ? $this->currencyIntToString($item->defined_contribution_pension->gross_contribution_absolute): null,
                            'employee_contribution_frequency' =>$item->defined_contribution_pension->employee_contribution_frequency,
                            'employer_contribution_percent' =>$item->defined_contribution_pension->employer_contribution_percent,
                            'employer_contribution_absolute' => $item->defined_contribution_pension->employer_contribution_absolute != null ? $this->currencyIntToString($item->defined_contribution_pension->employer_contribution_absolute): null,
                            'valuation_at' => $item->defined_contribution_pension->valuation_at,
                            'value' => $item->defined_contribution_pension->value != null ? $this->currencyIntToString($item->defined_contribution_pension->value): null,
                            'retained_value'=> $item->defined_contribution_pension->retained_value != null ? $this->currencyIntToString($item->defined_contribution_pension->retained_value): null,
                            'is_retained'=> (bool) $item->defined_contribution_pension->is_retained,
                            'crystallised_status'=> $item->defined_contribution_pension->crystallised_status,
                            'crystallised_percentage'=> $item->defined_contribution_pension->crystallised_percentage,
                            'funds' => $item->defined_contribution_pension->pension_funds->map(function ($fund){
                               return [
                                   'id'=> $fund->id,
                                   'fund_name'=> $fund->fund_name,
                                   'fund_type'=> $fund->fund_type,
                                   'current_fund_value' => $fund->current_fund_value != null ? $this->currencyIntToString($fund->current_fund_value): null,
                                   'current_transfer_value' => $fund->current_transfer_value != null ? $this->currencyIntToString($fund->current_transfer_value): null,
                               ];
                            }),
                            'employer_contribution_frequency' => $item->defined_contribution_pension->employer_contribution_frequency,
                        ];
                    }),
                    //  defined_benefit_pension = model
                    'db_pensions' => PensionScheme::with('defined_benefit_pension')->whereHas('defined_benefit_pension')->whereIn('client_id',[$this->model->id,$this->model->client_two->id])->get()->map(function ($item){
                        return [
                            'id' => $item->id,
                            'pt' => 'DB',
                            'owner' => $item->client->io_id,
                            'status' => $item->defined_benefit_pension->status,
                            'employer' => $item->employer,
                            'retirement_age' => $item->retirement_age,
                            'prospective_pension_standard' => $item->defined_benefit_pension->prospective_pension_standard != null ? $this->currencyIntToString($item->defined_benefit_pension->prospective_pension_standard): null,
                            'prospective_pension_max' => $item->defined_benefit_pension->prospective_pension_max  != null ? $this->currencyIntToString($item->defined_benefit_pension->prospective_pension_max): null,
                            'prospective_pcls_standard' => $item->defined_benefit_pension->prospective_pcls_standard  != null ? $this->currencyIntToString($item->defined_benefit_pension->prospective_pcls_standard): null,
                            'prospective_pcls_max' => $item->defined_benefit_pension->prospective_pcls_max  != null ? $this->currencyIntToString($item->defined_benefit_pension->prospective_pcls_max): null,
                            'chosen' => $item->defined_benefit_pension->chosen,
                            'notes' => $item->defined_benefit_pension->notes,
                            'cetv' => $item->defined_benefit_pension->cetv  != null ? $this->currencyIntToString($item->defined_benefit_pension->cetv): null,
                            'cetv_ends_at' => $item->defined_benefit_pension->cetv_ends_at
                        ];
                    })
                ],
                '3.5' => [
                    'schemes' => $this->model->share_save_schemes->merge($this->model->client_two->share_save_schemes)->sortBy('id')->values()->map(function ($item){
                        return [
                            'id' => $item->id,
                            'owner' => $item->client->io_id,
                            'name' => $item->name,
                            'option_price' => $item->option_price != null ? $this->currencyIntToString( $item->option_price): null,
                            'monthly_saving' => $item->monthly_saving != null ? $this->currencyIntToString( $item->monthly_saving): null,
                            'number_of_shares' => $item->number_of_shares,
                            'matures_at' => $item->matures_at,
                            'start_at' => $item->start_at,
                        ];
                    })
                ],
                '3.6' => [
                    'capitals' => $this->model->lump_sum_capitals->merge($this->model->client_two->lump_sum_capitals)->sortBy('id')->values()->map(function ($item){
                        return [
                            'id' => $item->id,
                            'owner' => $item->clients->count() > 1 ? 'Both' : $item->clients->first()->io_id,
                            'description' => $item->description,
                            'amount' => $item->amount != null ? $this->currencyIntToString( $item->amount): null,
                            'due_at' => $item->due_at,
                            'is_retained' => (bool) $item->is_retained,
                            'retained_value' =>  $item->retained_value != null ? $this->currencyIntToString($item->retained_value): null,
                        ];
                    })
                ],
                '4.1' => [
                    'liabilities' => $this->model->liabilities->merge($this->model->client_two->liabilities)->sortBy('id')->values()->map(function ($liability){
                        return [
                            'id' => $liability->id,
                            'owner' => $liability->clients->count() > 1 ? 'Both' : $liability->clients->first()->io_id,
                            'type' => $liability->type,
                            'repayment' => $liability->is_repayment,
                            'amount_outstanding' =>  $liability->amount_outstanding != null ? $this->currencyIntToString($liability->amount_outstanding): null,
                            'monthly_repayment' => $liability->monthly_repayment != null ? $this->currencyIntToString($liability->monthly_repayment): null,
                            'lender' => $liability->lender,
                            'ends_at' =>  $liability->ends_at,
                            'interest_rate' =>  $liability->interest_rate,
                            'is_to_be_repaid' => (bool) $liability->is_to_be_repaid,
                            'repay_details' => $liability->repay_details
                        ];
                    })
                ],
                default => [

                ]
            };
        }
        else{
            return match ($step . '.' . $section) {
                '1.1' => [
                    $this->model->io_id => [
                        'first_name' => $this->model->first_name,
                        'middle_name' => $this->model->middle_name,
                        'last_name' => $this->model->last_name,
                        'salutation' => $this->model->salutation,
                        'title' => $this->model->title,
                        'date_of_birth' => $this->model->date_of_birth,
                        'gender' => $this->model->gender,
                        'marital_status' => $this->model->marital_status,
                        'nationality' => $this->model->nationality,
                        'ni_number' => $this->model->ni_number,
                        'country_of_domicile' => $this->model->country_of_domicile != null ? config('enums.client.iso_2_int')[$this->model->country_of_domicile] : null,
                        'country_of_residence' => $this->model->country_of_residence != null ? config('enums.client.iso_2_int')[$this->model->country_of_residence] : null,
                        'valid_will' => (boolean)$this->model->valid_will,
                        'will_up_to_date' => (boolean)$this->model->will_up_to_date,
                        'poa_granted' => (boolean)$this->model->poa_granted,
                        'poa_name' => $this->model->poa_name
                    ],
                ],
                '1.2' => [
                    $this->model->io_id => [
                        'is_in_good_health' => (bool) $this->model->health?->is_in_good_health,
                        'health_details' => $this->model->health?->health_details,
                        'has_life_expectancy_concerns' => (bool) $this->model->health?->has_life_expectancy_concerns,
                        'life_expectancy_details' => $this->model->health?->life_expectancy_details,
                        'medical_conditions' => $this->model->health?->medical_conditions,
                        'smoker' => $this->model->health?->smoker,
                        'smoked_in_last_12_months' => (bool) $this->model->health?->smoked_in_last_12_months
                    ]
                ],
                '1.3' => [
                    'addresses' => collect($this->model->addresses->map(function ($address){
                        return [
                            'address_id' => $address['id'],
                            'address_line_1' => $address['address_line_1'],
                            'address_line_2' => $address['address_line_2'],
                            'city' => $address['city'],
                            'county' => $address['county'],
                            'postcode' => $address['postcode'],
                            'country' => '0'.$address['country'], //hack to allow reordering of JS objects with string keys
                            'residency_status' => $address['residency_status'],
                            'date_from' => $address['date_from'],
                            'owner' =>  $address->clients->first()->io_id ?? $this->model->io_id,
                            'percent_ownership' => $address->clients->first()->pivot->percent_ownership ?? 100
                        ];})),
                    $this->model->io_id => [
                        'phone_number' => $this->model->phone_number,
                        'mobile_number' => $this->model->mobile_number,
                        'email_address' => $this->model->email_address
                    ]
                ],
                '1.4' => [
                    'dependents' => collect($this->model->dependents->map(function ($dependent){
                        return [
                            'dependent_id' => $dependent->id,
                            'name' => $dependent->name,
                            'relationships' => [
                                $this->model->io_id => [
                                    'is_related' => $this->model->dependents->contains($dependent->id),
                                    'relationship_type' =>
                                        $this->model->dependents->contains($dependent->id) ?
                                            $dependent->clients()->where('clients.id',$this->model->id)->first()->pivot->relationship_type
                                            : null
                                ],
                            ],
                            'born_at' => $dependent->born_at,
                            'financial_dependent' => (bool)  $dependent->financial_dependent,
                            'financially_dependent_until' => $dependent->financially_dependent_until,
                            'is_living_with_clients' => (bool) $dependent->is_living_with_clients
                        ];
                    }))
                ],
                '1.5' => [
                    'employment_details' => collect($this->model->employment_details->map(function ($employment){
                        return [
                            'id' => $employment->id,
                            'employee' => $employment->client->io_id,
                            'employment_status' => $employment->employment_status,
                            'intended_retirement_age' => $employment->intended_retirement_age,
                            'occupation' => $employment->occupation,
                            'employer' => $employment->employer,
                            'start_at' => $employment->start_at,
                            'end_at' => $employment->end_at
                        ];
                    }))
                ],
                '2.1' => [
                    'incomes' => collect($this->model->incomes)->map(function ($income){
                        return [
                            'income_id' => $income->id,
                            'income_type' => $income->category,
                            'gross_amount' => $this->currencyIntToString($income->gross_amount),
                            'net_amount' => $this->currencyIntToString($income->net_amount),
                            'expenses' => $this->currencyIntToString($income->expenses),
                            'frequency' => $income->frequency,
                            'starts_at' => $income->starts_at,
                            'ends_at' => $income->ends_at,
                            'belongs_to' => $income->clients->first()->io_id,
                            'record_exists' => $income->pivot->client_id ? true : false,
                        ];
                    }),
                    'useIncome' => count($this->model->incomes) > 0 ? true : false,
                    'total' => count($this->model->incomes) > 0 ? null : $this->model->total_income_basic
                ],
                '2.2' => [
                    'client_id' => $this->model->io_id,
                    'expenditures' => collect($this->model->expenditures()->inConfigSection('basic_essential_expenditure')->get()->map(function ($expenditure){
                        return $expenditure->presenter()->form();
                    }))->groupBy('expenditure_type')
                ],
                '2.3' => [
                    'client_id' => $this->model->io_id,
                    'expenditures' => collect($this->model->expenditures()->inConfigSection('basic_quality_of_living_expenditure')->get()->map(function ($expenditure){
                        return $expenditure->presenter()->form();
                    }))->groupBy('expenditure_type')
                ],
                '2.4' => [
                    'client_id' => $this->model->io_id,
                    'expenditures' => collect($this->model->expenditures()->inConfigSection('non_essential_outgoings_expenditure')->get()->map(function ($expenditure){
                        return $expenditure->presenter()->form();
                    }))->groupBy('expenditure_type')
                ],
                '2.5' => [
                    'client_id' => $this->model->io_id,
                    'expenditures' => collect($this->model->expenditures()->inConfigSection('liability_expenditure')->get()->map(function ($expenditure){
                        return $expenditure->presenter()->form();
                    }))->groupBy('expenditure_type')
                ],
                '2.6' => [
                    'client_id' => $this->model->io_id,
                    'expenditures' => collect($this->model->expenditures()->inConfigSection('lump_sum_expenditure')->get()->map(function ($expenditure){
                        return $expenditure->presenter()->form();
                    }))->groupBy('expenditure_type')
                ],
                '3.1' => [
                    'fixed_assets' => collect(Asset::with('clients')->whereIn('id',$this->model->assets->where('category',array_flip(config('enums.assets.categories'))['fixed_assets'])->pluck('id'))->get()->map(function ($asset){
                        return $asset->presenter()->formatForFactFind('fixed');
                    }))
                ],
                '3.2' => [
                    'saving_assets' => collect(Asset::with('clients')->whereIn('id',$this->model->assets->where('category',array_flip(config('enums.assets.categories'))['savings'])->pluck('id'))->get()->map(function ($asset){
                        return $asset->presenter()->formatForFactFind('savings');
                    }))
                ],
                '3.3' => [
                    'investments' => $this->model->other_investments->map(function ($investment){
                        return [
                            'id' => $investment->id,
                            'owner' => $investment->client->io_id,
                            'provider' => $investment->provider != null ? [
                                'label' => \Cache::get('io_provider_list')[$investment->provider],
                                'value' => $investment->provider
                            ] : null,
                            'account_type' => $investment->contract_type,
                            'product_name' => $investment->product_name,
                            'is_retained' => (bool) $investment->is_retained,
                            'retained_value' =>  $investment->retained_value != null ? $this->currencyIntToString($investment->retained_value): null,
                            'current_value' => $investment->current_value != null ? $this->currencyIntToString($investment->current_value): null,
                            'regular_contribution' =>  $investment->regular_contribution != null ? $this->currencyIntToString($investment->regular_contribution): null,
                            'frequency' => $investment->frequency,
                            'lump_sum_contribution' =>  $investment->lump_sum_contribution  != null ? $this->currencyIntToString($investment->lump_sum_contribution): null,
                            'start_date' =>  $investment->start_date,
                            'maturity_date' =>  $investment->maturity_date,
                            'valuation_at' =>  $investment->valuation_at,
                        ];
                    })
                ],
                '3.4' => [
                    'dc_pensions' => PensionScheme::with('defined_contribution_pension')->whereHas('defined_contribution_pension')->where('client_id',$this->model->id)->get()->map(function ($item){
                        return [
                            'id' => $item->id,
                            'pt' => 'DC',
                            'owner' => $item->client->io_id,
                            'type' => $item->defined_contribution_pension->type,
                            'employer' => $item->employer,
                            'administrator' =>   $item->defined_contribution_pension->administrator != null ?
                                [
                                    'label' => \Cache::get('io_provider_list')[$item->defined_contribution_pension->administrator],
                                    'value' => $item->defined_contribution_pension->administrator
                                ] : null,
                            'policy_starts_at' => $item->defined_contribution_pension->policy_start_at,
                            'policy_number' => $item->defined_contribution_pension->policy_number,
                            'gross_contribution_percent' => $item->defined_contribution_pension->gross_contribution_percent,
                            'gross_contribution_absolute' => $item->defined_contribution_pension->gross_contribution_absolute != null ? $this->currencyIntToString($item->defined_contribution_pension->gross_contribution_absolute): null,
                            'employer_contribution_percent' =>$item->defined_contribution_pension->employer_contribution_percent,
                            'employee_contribution_frequency' =>$item->defined_contribution_pension->employee_contribution_frequency,
                            'employer_contribution_absolute' => $item->defined_contribution_pension->employer_contribution_absolute != null ? $this->currencyIntToString($item->defined_contribution_pension->employer_contribution_absolute): null,
                            'valuation_at' => $item->defined_contribution_pension->valuation_at,
                            'value' => $item->defined_contribution_pension->value != null ? $this->currencyIntToString($item->defined_contribution_pension->value): null,
                            'retained_value'=> $item->defined_contribution_pension->retained_value != null ? $this->currencyIntToString($item->defined_contribution_pension->retained_value): null,
                            'is_retained'=> (bool) $item->defined_contribution_pension->is_retained,
                            'crystallised_status'=> $item->defined_contribution_pension->crystallised_status,
                            'crystallised_percentage'=> $item->defined_contribution_pension->crystallised_percentage,
                            'funds' => $item->defined_contribution_pension->pension_funds->map(function ($fund){
                                return [
                                    'id'=> $fund->id,
                                    'fund_name'=> $fund->fund_name,
                                    'fund_type'=> $fund->fund_type,
                                    'current_fund_value' => $fund->current_fund_value != null ? $this->currencyIntToString($fund->current_fund_value): null,
                                    'current_transfer_value' => $fund->current_transfer_value != null ? $this->currencyIntToString($fund->current_transfer_value): null,
                                ];
                            }),
                            'employer_contribution_frequency' => $item->defined_contribution_pension->employer_contribution_frequency,
                        ];
                    }),
                    'db_pensions' => PensionScheme::with('defined_benefit_pension')->whereHas('defined_benefit_pension')->where('client_id',$this->model->id)->get()->map(function ($item){
                        return [
                            'id' => $item->id,
                            'pt' => 'DB',
                            'owner' => $item->client->io_id,
                            'status' => $item->defined_benefit_pension->status,
                            'employer' => $item->employer,
                            'retirement_age' => $item->retirement_age,
                            'prospective_pension_standard' => $item->defined_benefit_pension->prospective_pension_standard != null ? $this->currencyIntToString($item->defined_benefit_pension->prospective_pension_standard): null,
                            'prospective_pension_max' => $item->defined_benefit_pension->prospective_pension_max  != null ? $this->currencyIntToString($item->defined_benefit_pension->prospective_pension_max): null,
                            'prospective_pcls_standard' => $item->defined_benefit_pension->prospective_pcls_standard  != null ? $this->currencyIntToString($item->defined_benefit_pension->prospective_pcls_standard): null,
                            'prospective_pcls_max' => $item->defined_benefit_pension->prospective_pcls_max  != null ? $this->currencyIntToString($item->defined_benefit_pension->prospective_pcls_max): null,
                            'chosen' => $item->defined_benefit_pension->chosen,
                            'notes' => $item->defined_benefit_pension->notes,
                            'cetv' => $item->defined_benefit_pension->cetv  != null ? $this->currencyIntToString($item->defined_benefit_pension->cetv): null,
                            'cetv_ends_at' => $item->defined_benefit_pension->cetv_ends_at
                        ];
                    })
                ],
                '3.5' => [
                    'schemes' => $this->model->share_save_schemes->map(function ($item){
                        return [
                            'id' => $item->id,
                            'owner' => $item->client->io_id,
                            'name' => $item->name,
                            'option_price' => $item->option_price != null ? $this->currencyIntToString( $item->option_price): null,
                            'monthly_saving' => $item->monthly_saving != null ? $this->currencyIntToString( $item->monthly_saving): null,
                            'number_of_shares' => $item->number_of_shares,
                            'matures_at' => $item->matures_at,
                        ];
                    })
                ],
                '3.6' => [
                    'capitals' => $this->model->lump_sum_capitals->map(function ($item){
                        return [
                            'id' => $item->id,
                            'owner' => $item->clients->count() > 1 ? 'Both' : $item->clients->first()->io_id,
                            'description' => $item->description,
                            'amount' => $item->amount != null ? $this->currencyIntToString( $item->amount): null,
                            'due_at' => $item->due_at,
                            'is_retained' => (bool) $item->is_retained,
                            'retained_value' =>  $item->retained_value != null ? $this->currencyIntToString($item->retained_value): null,
                        ];
                    })
                ],
                '4.1' => [
                    'liabilities' => $this->model->liabilities->map(function ($liability){
                        return [
                            'id' => $liability->id,
                            'owner' => $liability->clients->count() > 1 ? 'Both' : $liability->clients->first()->io_id,
                            'type' => $liability->type,
                            'repayment' => $liability->is_repayment,
                            'amount_outstanding' =>  $liability->amount_outstanding != null ? $this->currencyIntToString($liability->amount_outstanding): null,
                            'monthly_repayment' => $liability->monthly_repayment != null ? $this->currencyIntToString($liability->monthly_repayment): null,
                            'lender' => $liability->lender,
                            'ends_at' =>  $liability->ends_at,
                            'interest_rate' =>  $liability->interest_rate,
                            'is_to_be_repaid' => (bool) $liability->is_to_be_repaid,
                            'repay_details' => $liability->repay_details
                        ];
                    })
                ],
                default => [

                ]
            };
        }

    }
    public function formatForPersonalDetails(): array
    {
        return [
            'name' => $this->model->full_name,
            'age' => Carbon::parse($this->model->date_of_birth)->diffInYears(Carbon::now()),
            'date_of_birth' => Carbon::parse($this->model->date_of_birth)->format('jS F Y')
        ];
    }

    public function formatForClientsIndex(): array
    {
        return array_merge($this->default(), [
          'age'=> $this->model->age,
          'job_title' => $this->model->job_title,
          'last_contact'=> $this->model->last_contact,
          'status_text' => $this->model->status_text
        ]);
    }
}
