<?php

namespace App\Models\Presenters;

use Illuminate\Database\Eloquent\Model;

abstract class BasePresenter
{
    public function __construct(public Model $model){
        //todo: initialise any base presenter functionality
    }

    //Format for display on Form
    public function form():array
    {
        return $this->default();
    }
    //Format for display on Index
    public function index():array
    {
        return $this->default();
    }
    //Format for display
    protected function default(): array
    {
        return $this->model->toArray();
    }

}
