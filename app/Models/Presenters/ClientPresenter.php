<?php

namespace App\Models\Presenters;

use phpDocumentor\Reflection\Types\Boolean;

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
    public function formatForStep($section,$step)
    {
        return match ($section . '.' . $step) {
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
