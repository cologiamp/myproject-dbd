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
            '1.3' => [
                'id' => $this->model->id,
                'cta_base_costs_availability' => $this->model->cta_base_costs_available == null ? false : true,
                'cta_base_costs_available' => $this->model->cta_base_costs_available,
                'cta_sell_to_cgt_exemption' => (bool)$this->model->cta_sell_to_cgt_exemption,
                'cta_sell_all' => (bool)$this->model->cta_sell_all,
                'cta_sell_set_amount_check' => $this->model->cta_sell_set_amount == null ? false : true,
                'cta_sell_set_amount' => $this->model->cta_sell_set_amount,
                'dta_base_costs_availability' => $this->model->dta_base_costs_available == null ? false : true,
                'dta_base_costs_available' => $this->model->dta_base_costs_available,
                'dta_sell_to_cgt_exemption' => (bool)$this->model->dta_sell_to_cgt_exemption,
                'dta_sell_all' => (bool)$this->model->dta_sell_all,
                'dta_sell_set_amount_check' => $this->model->dta_sell_set_amount == null ? false : true,
                'dta_sell_set_amount' => $this->model->dta_sell_set_amount,
                'isa_transfer_exit_penalty_not_ascertained' => (bool)$this->model->isa_transfer_exit_penalty_not_ascertained,
                'isa_transfer_exit_penalty_ascertained_check' => $this->model->isa_transfer_exit_penalty_ascertained == null ? false : true,
                'isa_transfer_exit_penalty_ascertained' => $this->model->isa_transfer_exit_penalty_ascertained,
                'investment_bonds_managed_funds' => (bool)$this->model->investment_bonds_managed_funds,
                'investment_bonds_with_profits' => (bool)$this->model->investment_bonds_with_profits,
                'chargeable_grain' => null, // To Do: set value if client has investment_bond records
                'investment_bonds_chargeable_gain_not_calculated' => (bool)$this->model->investment_bonds_chargeable_gain_not_calculated,
                'investment_bonds_exit_penalty_not_ascertained' => (bool)$this->model->investment_bonds_exit_penalty_not_ascertained,
                'investment_bonds_exit_penalty_ascertained_check' => $this->model->investment_bonds_exit_penalty_ascertained == null ? false : true,
                'investment_bonds_exit_penalty_ascertained' => $this->model->investment_bonds_exit_penalty_ascertained,
                'investment_bonds' => collect($this->model->investment_bonds->map(function ($investment_bond){
                    return [
                        'id' => $investment_bond->id,
                        'provider' => $investment_bond->provider,
                        'initial_investment' => $investment_bond->initial_investment != null ? $this->currencyIntToString($investment_bond->initial_investment) : null,
                        'surrender_value' => $investment_bond->surrender_value != null ? $this->currencyIntToString($investment_bond->surrender_value) : null,
                        'withdrawals' => $investment_bond->withdrawals != null ? $this->currencyIntToString($investment_bond->withdrawals) : null,
                        'total_gain' => $investment_bond->total_gain != null ? $this->currencyIntToString($investment_bond->total_gain) : null,
                        'complete_years_held' => $investment_bond->complete_years_held,
                        'top_slice' => $investment_bond->top_slice != null ? $this->currencyIntToString($investment_bond->top_slice) : null,
                    ];
                }))
            ],
            '1.4' => [
                'investment_recommendation_items' => collect($this->model->primary_client->investment_recommendation_items()->get()->map(function ($item){
                    return [
                        'id' => $item->id,
                        'type' => $item->type,
                        'source_plan' => $item->source_plan,
                        'description' => $item->description,
                        'stock_type' => $item->stock_type,
                        'number_of_units' => $item->number_of_units,
                        'amount' => $item->amount != null ? $this->currencyIntToString($item->amount) : null
                    ];
                }))->groupBy('type')
            ],
            default => [
            ]
        };
    }
}
