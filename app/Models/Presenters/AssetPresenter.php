<?php

namespace App\Models\Presenters;

use App\Concerns\FormatsCurrency;
use App\Models\Asset;
use App\Models\Dependent;
use PHPUnit\Framework\Attributes\Depends;

class AssetPresenter extends BasePresenter
{
    use FormatsCurrency;
    public function formatForFactFind($assetType = 'fixed'): array
    {
        return array_merge($this->default(),$this->fetchFields($assetType));

    }
    public function default(): array
    {
        return [
            'id' => $this->model->id,
            'owner' => $this->model->clients->count() > 1 ? 'Both' : $this->model->clients->first()->io_id,
            'asset_type' => $this->model->type,
            'is_retained' => (bool) $this->model->is_retained,
            'retained_value' =>  $this->model->retained_value != null ? $this->currencyIntToString($this->model->retained_value): null,
        ];
    }

    public function fetchFields($assetType):array
    {
        return match($assetType){
            'fixed' => [
                'percent_ownership' => $this->model->clients->mapWithKeys(fn($i) => [$i->io_id => $i->pivot->percent_ownership]),
                'description' => $this->model->description,
                'purchased_at' => $this->model->start_at,
                'original_value' => $this->model->original_value != null ? $this->currencyIntToString($this->model->original_value) : null,
                'current_value' =>  $this->model->current_value != null ? $this->currencyIntToString($this->model->current_value): null
            ],
            'savings' => [
                'provider' => $this->model->provider != null ? [
                    'label' => \Cache::get('io_provider_list')[$this->model->provider],
                    'value' => $this->model->provider
                ] : null,
                'account_type' => $this->model->account_type,
                'name' => $this->model->product_name,
                'current_balance' => $this->model->current_value != null ? $this->currencyIntToString($this->model->current_value) : null,
                'start_date' =>  $this->model->start_at,
                'end_date' =>  $this->model->end_at,
                'interest_rate' =>  $this->model->interest_rate,
                'regular_contributions' => (bool)  $this->model->regular_contributions,
                'contribution_amount' =>  $this->model->contribution_amount != null ? $this->currencyIntToString($this->model->contribution_amount) : null,

            ],
            default => []
        };

    }

}
