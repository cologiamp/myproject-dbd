<?php

namespace App\Models\Presenters;

use App\Concerns\FormatsCurrency;

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
                'id' => $this->model->client->knowledge?->id,
                'particular_issues' => (bool) $this->model->client->knowledge?->particular_issues,
                'level_of_knowledge' => $this->model->client->knowledge?->level_of_knowledge,
                'aware_of_market_fluctuations' => (bool) $this->model->client->knowledge?->aware_of_market_fluctuations,
                'comfort_of_fluctuations' => (bool) $this->model->client->knowledge?->comfort_of_fluctuations,
                'active_interest' => (bool) $this->model->client->knowledge?->active_interest,
                'discretionary_experience' => (bool) $this->model->client->knowledge?->discretionary_experience,
                'ever_taken_invest_advice' => (bool) $this->model->client->knowledge?->ever_taken_invest_advice,
                'experience_buying_cash' => $this->model->client->knowledge?->experience_buying_cash,
                'experience_buying_bonds' => $this->model->client->knowledge?->experience_buying_bonds,
                'experience_buying_equities' => $this->model->client->knowledge?->experience_buying_equities,
                'experience_buying_insurance' => $this->model->client->knowledge?->experience_buying_insurance
            ],
            '1.2' => [
                'id' => $this->model->client->capacity_for_loss?->id,
                'investment_length' => $this->model->client->capacity_for_loss?->investment_length,
                'standard_of_living' => $this->model->client->capacity_for_loss?->standard_of_living,
                'emergency_funds' => $this->model->client->capacity_for_loss?->emergency_funds
            ],
            '1.3' => [
                'id' => $this->model->client->risk_profile?->id,
                'comfort_fluctuate_market' => (bool) $this->model->client->risk_profile?->comfort_fluctuate_market,
                'day_to_day_volatility' => $this->model->client->risk_profile?->day_to_day_volatility,
                'short_term_volatility' => $this->model->client->risk_profile?->short_term_volatility,
                'medium_term_volatility' => $this->model->client->risk_profile?->medium_term_volatility,
                'volatility_behaviour' => $this->model->client->risk_profile?->volatility_behaviour,
                'long_term_volatility' => $this->model->client->risk_profile?->long_term_volatility,
                'time_in_market' => $this->model->client->risk_profile?->time_in_market
            ],
            default => [
            ]
        };
    }
}
