<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use App\Models\Presenters\InvestmentRecommendationPresenter;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestmentRecommendation extends Model
{
    protected $guarded = [];

    public function clients():HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function investment_bonds():HasMany
    {
        return $this->hasMany(InvestmentBond::class);
    }

    public function getPrimaryClientAttribute()
    {
        if ($this->clients->count() > 1) {
            return $this->clients()->where('c2_id', '!=', null)->first();
        }

        return $this->clients()->first();
    }

    //Presenter
    public function presenter() : InvestmentRecommendationPresenter
    {
        return new InvestmentRecommendationPresenter($this);
    }

    public function loadEnumsForStep($step,$section)
    {
        return match ($step.'.'.$section){
            '1.1' => [
                'fee_basis' => config('enums.investment_recommendation.fee_basis')
            ],
            '1.2' => [
                'report_for' => $this->getOwnersForForm(),
                'report_type' => config('enums.investment_recommendation.report_type'),
                'regular_cash_duration' => config('enums.investment_recommendation.frequency_public')
            ],
            '1.4' => [
                'item_types' => config('enums.investment_recommendation_items.types_public'),
                'owners' => $this->getOwnersForForm(true),
                'descriptions' => config('enums.investment_recommendation_items.descriptions')
            ],
            '2.1' => [
                'fee_basis' => config('enums.pension_recommendation.fee_basis_public'),
                'report_type' => config('enums.pension_recommendation.report_type'),
            ],
            '2.2' => [
                'employment_status' => config('enums.pension_recommendation.employment_status'),
                'workplace_pension_types' => config('enums.pension_recommendation.workplace_pension_type'),
                'pension_review_transfer' => config('enums.pension_recommendation.pension_review_transfer'),
                'retirement_options' => config('enums.pension_recommendation.retirement_option')
            ],
            '2.3' => [
                'policy_types' => config('enums.pension_recommendation.policy_type'),
                'loa_submitted' => config('enums.pension_recommendation.loa_submitted')
            ],
            '2.4' => [
                'types' => config('enums.pension_recommendation.new_contribution_type'),
                'paid_by' => config('enums.pension_recommendation.new_contribution_paid_by'),
                'frequency' => config('enums.pension_recommendation.frequency_public')
            ],
            '2.6' => [
                'types' => config('enums.pension_recommendation.item_type')
            ],
            default => [

            ]
        };
    }

    private function getOwnersForForm($noBoth = false):array
    {
        if($this->primary_client->client_two)
        {
            $arr = [
                $this->primary_client->io_id => $this->primary_client->name,
                $this->primary_client->client_two->io_id => $this->primary_client->client_two->name
            ];
            if(!$noBoth)
            {
                $arr['Both'] = 'Both';
            }
            return $arr;
        }
        return [
            $this->primary_client->io_id => $this->primary_client->name,
        ];
    }
}
