<?php

namespace App\Models\Presenters;

use App\Concerns\FormatsCurrency;

class InvestmentRecommendationPresenter extends BasePresenter
{
    use FormatsCurrency;
    public function index():array
    {
        return array_merge($this->default(),
            [
                'id' => $this->model->id
            ]
        );
    }

    public function formatForExampleForm(): array
    {
        return [
//            'first_name'=> $this->model->first_name,
//            'last_name'=> $this->model->last_name,
//            'title' => $this->model->title
        ];
    }

    //InvestmentRecommendation:// Need to do this for every section/step
    //Chore: this should probably be refactored to take some of the non-client stuff out of the client model.
    public function formatForStep($step,$section): array
    {
        return match ($step . '.' . $section) {
            '1.1' => [
                'id' => $this->model->id,
                'is_ethical_investor' => $this->model->is_ethical_investor,
                'risk_profile' => $this->model->risk_profile,
                'previously_invested_amount' => $this->model->previously_invested_amount != null ? $this->currencyIntToString($this->model->previously_invested_amount) : null,
                'fee_basis' => $this->model->fee_basis,
                'fee_basis_discount' => $this->model->fee_basis_discount != null ? $this->currencyIntToString($this->model->fee_basis_discount) : null
            ],
            '1.2' => [
                'id' => $this->model->id,
                'report_type' => $this->model->report_type,
                'isa_allowance_used' => $this->model->primary_client->isa_allowance_used != null ? $this->currencyIntToString($this->model->primary_client->isa_allowance_used) : null,
                'cgt_allowance_used' => $this->model->primary_client->cgt_allowance_used != null ? $this->currencyIntToString($this->model->primary_client->cgt_allowance_used) : null,
                'net_income_required' => $this->model->net_income_required != null ? $this->currencyIntToString($this->model->net_income_required) : null,
                'regular_cash_required' => $this->model->regular_cash_required != null ? $this->currencyIntToString($this->model->regular_cash_required) : null,
                'regular_cash_duration' => $this->model->regular_cash_duration
            ],
            default => [

            ]
        };
    }

    public function formatForRecommendationsIndex(): array
    {
//        return array_merge($this->default(), [
//            'age'=> $this->model->age,
//            'job_title' => $this->model->job_title,
//            'last_contact'=> $this->model->last_contact,
//            'status_text' => $this->model->status_text
//        ]);
    }
}
