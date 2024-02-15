<?php

namespace App\Models;

use App\Models\BaseModels\Model;
use App\Models\Presenters\InvestmentRecommendationPresenter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class InvestmentRecommendation extends Model
{
    protected $guarded = [];

    public function clients():BelongsToMany
    {
        return $this->belongsToMany(Client::class)->withPivot('isa_allowance_used', 'cgt_allowance_used');
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
                'report_for' => $this->getOwnersForForm(true),
                'report_type' => config('enums.investment_recommendation.report_type'),
                'regular_cash_duration' => config('enums.investment_recommendation.frequency_public')
            ],
            default => [

            ]
        };
    }

    private function getOwnersForForm($noBoth = false):array
    {
        if($this->client_two)
        {
            $arr = [
                $this->io_id => $this->name,
                $this->client_two->io_id => $this->client_two->name
            ];
            if(!$noBoth)
            {
                $arr['Both'] = 'Both';
            }
            return $arr;
        }
        return [
            $this->io_id => $this->name,
        ];
    }
}
