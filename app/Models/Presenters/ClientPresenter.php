<?php

namespace App\Models\Presenters;

use App\Concerns\FormatsCurrency;
use App\Models\Asset;
use phpDocumentor\Reflection\Types\Boolean;
use App\Models\Dependent;
use PHPUnit\Framework\Attributes\Depends;

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
            '3.1' => [
                'fixed_assets' => collect(Asset::with('clients')->whereIn('id',$this->model->assets->where('category',array_flip(config('enums.assets.categories'))['fixed_assets'])->pluck('id'))->get()->map(function ($asset){
                    return [
                        'id' => $asset->id,
                        'owner' => $asset->clients->count() > 1 ? 'Both' : $asset->clients->first()->io_id,
                        'asset_type' => $asset->type,
                        'percent_ownership' => $asset->clients->mapWithKeys(fn($i) => [$i->io_id => $i->pivot->percent_ownership]),
                        'description' => $asset->description,
                        'purchased_at' => $asset->start_at,
                        'original_value' => $asset->original_value != null ? $this->currencyIntToString($asset->original_value) : null,
                        'current_value' =>  $asset->current_value != null ? $this->currencyIntToString($asset->current_value): null,
                        'is_retained' => $asset->is_retained,
                        'retained_value' =>  $asset->retained_value != null ? $this->currencyIntToString($asset->retained_value): null,
                    ];
                }))
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
