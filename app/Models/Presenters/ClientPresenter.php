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
                'country_of_domicile' => config('enums.client.iso_2_int')[$this->model->country_of_domicile],
                'country_of_residence' => config('enums.client.iso_2_int')[$this->model->country_of_residence],
                'valid_will' => (boolean)$this->model->valid_will,
                'will_up_to_date' => (boolean)$this->model->will_up_to_date,
                'poa_granted' => (boolean)$this->model->poa_granted
            ],
            '1.2' => [
                'client_id' => $this->model->id,
                'is_in_good_health' => $this->model->health->is_in_good_health,
                'health_details' => $this->model->health->health_details,
                'has_life_expectancy_concerns' => $this->model->health->has_life_expectancy_concerns,
                'life_expectancy_details' => $this->model->health->life_expectancy_details,
                'medical_conditions' => $this->model->health->medical_conditions,
                'smoker' => $this->model->health->smoker,
                'smoked_in_last_12_months' => $this->model->health->smoked_in_last_12_months
            ],
            '1.3' => [
                'client_id' => $this->model->id,
                'addresses' => collect($this->model->addresses->map(function ($address){
                    return [
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
                'client_id' => $this->model->id,
                'dependents' => collect($this->model->clientDependents->map(function ($clientDependent){
                    $dependentDetails = Dependent::where('id',$clientDependent->dependent_id)->first();
                    
                    return [
                        'dependent_id' => $dependentDetails['id'],
                        'relationship_type' => $clientDependent['relationship_type'],
                        'born_at' => $dependentDetails->born_at,
                        'financial_dependent' => $dependentDetails->financial_dependent,
                        'is_living_with_clients' => $dependentDetails->is_living_with_clients
                ];}))
            ],
            '1.5' => [
                'employment_status' => $this->model->employment_status,
                'intended_retirement_date' => $this->model->intended_retirement_date,
                'occupation' => $this->model->occupation,
                'employer' => $this->model->employer,
                'start_at' => $this->model->start_at,
                'end_at' => $this->model->end_at
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
