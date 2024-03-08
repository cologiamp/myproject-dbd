<?php

namespace App\Models\Presenters;

use App\Concerns\FormatsCurrency;
use App\Models\CapacityForLoss;
use App\Models\Knowledge;
use App\Models\RiskProfile;

class RiskPresenter extends BasePresenter
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

    public function formatForStep($step,$section): array
    {
        return match ($step . '.' . $section) {
            '1.1' => [
                'knowledge' => collect(Knowledge::where('client_id',$this->model->client->id)->where('type', RiskProfile::RISK_INVESTMENT_TYPE)->get()->map(function ($knowledge){
                    return [
                        'id' => $knowledge?->id,
                        'type' => $knowledge?->type,
                        'particular_issues' => (bool) $knowledge?->particular_issues,
                        'level_of_knowledge' => $knowledge?->level_of_knowledge,
                        'aware_of_market_fluctuations' => (bool) $knowledge?->aware_of_market_fluctuations,
                        'comfort_of_fluctuations' => (bool) $knowledge?->comfort_of_fluctuations,
                        'active_interest' => (bool) $knowledge?->active_interest,
                        'discretionary_experience' => (bool) $knowledge?->discretionary_experience,
                        'ever_taken_invest_advice' => (bool) $knowledge?->ever_taken_invest_advice,
                        'experience_buying_cash' => $knowledge?->experience_buying_cash,
                        'experience_buying_bonds' => $knowledge?->experience_buying_bonds,
                        'experience_buying_equities' => $knowledge?->experience_buying_equities,
                        'experience_buying_insurance' => $knowledge?->experience_buying_insurance,
                        'experience_details' => $knowledge?->experience_details
                    ];
                }))->collapse()
            ],
            '1.2' => [
                'capacity_data' => collect(CapacityForLoss::where('client_id',$this->model->client->id)->where('type', RiskProfile::RISK_INVESTMENT_TYPE)->get()->map(function ($capacity){
                    return [
                        'id' => $capacity?->id,
                        'type' => $capacity?->type,
                        'investment_length' => $capacity?->investment_length,
                        'standard_of_living' => $capacity?->standard_of_living,
                        'emergency_funds' => $capacity?->emergency_funds,
                    ];
                }))->collapse()
            ],
            '1.3' => [
                'risk_profile' => collect(RiskProfile::where('client_id',$this->model->client->id)->where('type', RiskProfile::RISK_INVESTMENT_TYPE)->get()->map(function ($risk){
                    return [
                        'id' => $risk->id,
                        'type' => $risk?->type,
                        'comfort_fluctuate_market' => (bool) $risk->comfort_fluctuate_market,
                        'day_to_day_volatility' => $risk->day_to_day_volatility,
                        'short_term_volatility' => $risk->short_term_volatility,
                        'medium_term_volatility' => $risk->medium_term_volatility,
                        'volatility_behaviour' => $risk->volatility_behaviour,
                        'long_term_volatility' => $risk->long_term_volatility,
                        'time_in_market' => $risk->time_in_market,
                    ];
                }))->collapse()
            ],
            '2.1' => [
                'knowledge' => collect(Knowledge::where('client_id',$this->model->client->id)->where('type', RiskProfile::RISK_PENSION_TYPE)->get()->map(function ($knowledge){
                    return [
                        'id' => $knowledge?->id,
                        'type' => $knowledge?->type,
                        'particular_issues' => (bool) $knowledge?->particular_issues,
                        'level_of_knowledge' => $knowledge?->level_of_knowledge,
                        'aware_of_market_fluctuations' => $knowledge?->aware_of_market_fluctuations,
                        'comfort_of_fluctuations' => (bool) $knowledge?->comfort_of_fluctuations,
                        'active_interest' => (bool) $knowledge?->active_interest,
                        'discretionary_experience' => (bool) $knowledge?->discretionary_experience,
                        'ever_taken_invest_advice' => (bool) $knowledge?->ever_taken_invest_advice,
                        'experience_buying_cash' => $knowledge?->experience_buying_cash,
                        'experience_buying_bonds' => $knowledge?->experience_buying_bonds,
                        'experience_buying_equities' => $knowledge?->experience_buying_equities,
                        'experience_buying_insurance' => $knowledge?->experience_buying_insurance,
                        'experience_details' => $knowledge?->experience_details,
                        'execution_only_experience' => (bool) $knowledge?->execution_only_experience, // pension type
                        'experience_of_annuities' => $knowledge?->experience_of_annuities, // pension type
                        'experience_of_income_drawdown' => $knowledge?->experience_of_income_drawdown, // pension type
                        'experience_of_phased_retirement' => $knowledge?->experience_of_phased_retirement, // pension type
                        'spoken_to_pensionwise' => (bool) $knowledge?->spoken_to_pensionwise // pension type
                    ];
                }))->collapse()
            ],
            '2.2' => [
                'capacity_data' => collect(CapacityForLoss::where('client_id',$this->model->client->id)->where('type', RiskProfile::RISK_PENSION_TYPE)->get()->map(function ($capacity){
                    return [
                        'id' => $capacity?->id,
                        'type' => $capacity?->type,
                        'investment_length' => $capacity?->investment_length,
                        'standard_of_living' => $capacity?->standard_of_living,
                        'emergency_funds' => $capacity?->emergency_funds,
                    ];
                }))->collapse()
            ],
            '2.3' => [
                'risk_profile' => collect(RiskProfile::where('client_id',$this->model->client->id)->where('type', RiskProfile::RISK_PENSION_TYPE)->get()->map(function ($risk){
                    return [
                        'id' => $risk->id,
                        'type' => $risk?->type,
                        'comfort_fluctuate_market' => (bool) $risk->comfort_fluctuate_market,
                        'day_to_day_volatility' => $risk->day_to_day_volatility,
                        'short_term_volatility' => $risk->short_term_volatility,
                        'medium_term_volatility' => $risk->medium_term_volatility,
                        'volatility_behaviour' => $risk->volatility_behaviour,
                        'long_term_volatility' => $risk->long_term_volatility,
                        'time_in_market' => $risk->time_in_market,
                    ];
                }))->collapse()
            ],

            default => [
            ]
        };
    }
}
