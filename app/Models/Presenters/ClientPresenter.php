<?php

namespace App\Models\Presenters;

use App\Concerns\FormatsCurrency;
use App\Models\Asset;
use App\Models\PensionScheme;

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
        return match ($step . '.' . $section) {
            '1.1' => [
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
            '1.2' => [
                'is_in_good_health' => $this->model->health?->is_in_good_health,
                'health_details' => $this->model->health?->health_details,
                'has_life_expectancy_concerns' => $this->model->health?->has_life_expectancy_concerns,
                'life_expectancy_details' => $this->model->health?->life_expectancy_details,
                'medical_conditions' => $this->model->health?->medical_conditions,
                'smoker' => $this->model->health?->smoker,
                'smoked_in_last_12_months' => $this->model->health?->smoked_in_last_12_months
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
                        'country' => $address['country'],
                        'residency_status' => $address['residency_status'],
                        'date_from' => $address['date_from']
                    ];})),
                    'phone_number' => $this->model->phone_number,
                    'mobile_number' => $this->model->mobile_number,
                    'email_address' => $this->model->email_address
            ],
            '1.4' => [
                'dependents' => collect($this->model->dependents->map(function ($dependent){
                    return [
                            'dependent_id' => $dependent->id,
                            'name' => $dependent->name,
                            'relationship_type' => $dependent->pivot->relationship_type,
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
                        'gross_amount' => $income->gross_amount,
                        'net_amount' => $income->net_amount,
                        'expenses' => $income->expenses,
                        'frequency' => $income->frequency,
                        'starts_at' => $income->starts_at,
                        'ends_at' => $income->ends_at,
                        'belongs_to' => $income->pivot->client_id,
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
                        'employer_contribution_absolute' => $item->defined_contribution_pension->employer_contribution_absolute != null ? $this->currencyIntToString($item->defined_contribution_pension->employer_contribution_absolute): null,
                        'valuation_at' => $item->defined_contribution_pension->valuation_at,
                        'value' => $item->defined_contribution_pension->value != null ? $this->currencyIntToString($item->defined_contribution_pension->value): null,
                        'retained_value'=> $item->defined_contribution_pension->retained_value != null ? $this->currencyIntToString($item->defined_contribution_pension->retained_value): null,
                        'is_retained'=> (bool) $item->defined_contribution_pension->is_retained,
                        'crystallised_status'=> $item->defined_contribution_pension->crystallised_status,
                        'crystallised_percentage'=> $item->defined_contribution_pension->crystallised_percentage,
                        'fund_name'=> $item->defined_contribution_pension->fund_name,
                        'fund_type'=> $item->defined_contribution_pension->fund_type,
                        'current_fund_value' => $item->defined_contribution_pension->current_fund_value != null ? $this->currencyIntToString($item->defined_contribution_pension->current_fund_value): null,
                        'current_transfer_value' => $item->defined_contribution_pension->current_transfer_value != null ? $this->currencyIntToString($item->defined_contribution_pension->current_transfer_value): null,

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
