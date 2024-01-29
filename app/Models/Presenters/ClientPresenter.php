<?php

namespace App\Models\Presenters;

use phpDocumentor\Reflection\Types\Boolean;
use App\Models\Dependent;
use PHPUnit\Framework\Attributes\Depends;

class ClientPresenter extends BasePresenter
{
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
    public function formatForStep($step,$section)
    {
        return match ($step . '.' . $section) {
            '1.1' => [
                'first_name' => $this->model->first_name,
                'last_name' => $this->model->last_name,
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
                'poa_granted' => (boolean)$this->model->poa_granted
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
                    'email_address' => $this->model->email_address
            ],
            '1.4' => [
                'dependents' => collect($this->model->dependents->map(function ($dependent){
                    return [
                            'dependent_id' => $dependent->id,
                            'name' => $dependent->name,
                            'relationship_type' => $dependent->pivot->relationship_type,
                            'born_at' => $dependent->born_at,
                            'financial_dependent' => $dependent->financial_dependent,
                            'is_living_with_clients' => $dependent->is_living_with_clients
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
                'incomes' => collect($this->model->incomes->map(function ($income){
                    return [
                        'income_id' => $income->id,
                        'income_type' => $income->category,
                        'gross_amount' => $income->gross_amount,
                        'net_amount' => $income->net_amount,
                        'expenses' => $income->expenses,
                        'frequency' => $income->frequency,
                        'ends_at' => $income->ends_at,
                        'belongs_to' => $income->pivot->client_id,
                        'record_exists' => $income->pivot->client_id ? true : false,
                        'is_primary' => (bool) $income->pivot->is_primary
                    ];
                }))
            ],
            '2.2' => [
                'expenditures' => collect($this->model->expenditures->map(function ($expenditure){
                    return [
                        'expenditure_id' => $expenditure->id,
                        'expenditure_type' => $expenditure->type,
                        'description' => $expenditure->description,
                        'amount' => $expenditure->amount,
                        'frequency' => $expenditure->frequency,
                        'starts_at' => $expenditure->starts_at,
                        'ends_at' => $expenditure->ends_at,
                        'currently_active' => $expenditure->starts_at ? false : true,
                        'known_end_date' => $expenditure->ends_at ? true : false
                    ];
                }))->groupBy('expenditure_type')
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
