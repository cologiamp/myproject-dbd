<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use App\Models\Presenters\InvestmentRecommendationPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvestmentRecommendation extends Model
{
    protected $guarded = [];

    public function clients():HasMany
    {
        return $this->hasMany(Client::class);
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
