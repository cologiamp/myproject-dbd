<?php

namespace App\Models\Presenters;


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
