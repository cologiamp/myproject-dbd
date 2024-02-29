<?php

namespace App\Models\Presenters;

class ExpenditurePresenter extends BasePresenter
{
    public function index():array
    {
        return array_merge($this->default(),
            [
                'name' => $this->model->name
            ]
        );
    }

    public function form(): array
    {
        return [
            'expenditure_id' => $this->model->id,
            'expenditure_type' => $this->model->type,
            'description' => $this->model->description,
            'amount' => $this->model->amount,
            'frequency' => $this->model->frequency,
            'starts_at' => $this->model->starts_at,
            'ends_at' => $this->model->ends_at,
            'currently_active' => $this->model->starts_at ? false : true,
            'known_end_date' => $this->model->ends_at ? true : false,
            'belongs_to' => $this->model->clients->count() > 1 ? 'Both' : $this->model->clients->first()->io_id,
            //add belongs to?
        ];
    }
}
