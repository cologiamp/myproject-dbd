<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StrategyReportResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
           'date' => Carbon::parse($this->created_at)->diffForHumans(),
           'file' => config('app.cdn') . $this->path,
           'created_by' => $this->adviser->name,
           'is_new' => Carbon::parse($this->created_at)->diffInSeconds(Carbon::now()) < 60 ? true : false
        ];
    }
}
