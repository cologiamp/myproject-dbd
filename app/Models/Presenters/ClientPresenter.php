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

    //FactFind:// Need to do this for every section/step
    public function formatForStep($section,$step)
    {
        return match($section.'.'.$step){
            '1.1' => [
                'first_name'=> $this->model->first_name,
                'last_name'=> $this->model->last_name,
                'title' => $this->model->title,
                'date_of_birth' => $this->model->date_of_birth,
                'gender' => $this->model->gender,
            ],
            default => [

            ]
        };
    }
}
