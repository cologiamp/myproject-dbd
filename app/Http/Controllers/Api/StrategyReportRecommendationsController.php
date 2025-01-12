<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\StrategyReportRecommendation;
use App\Services\StrategyReportRecommendationsDataService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class StrategyReportRecommendationsController extends Controller
{
    /**
     * @param Client $client
     * @param $section
     * @param $step
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Client $client, $step, Request $request): string
    {
        $srr = App::make(StrategyReportRecommendationsDataService::class);

        try{
            $srr->validate($step, $request); //throws exception if validation fails - comes back to Inertia as errorbag
        }
        catch (Exception $e)
        {
            Log::warning($e);
        }
        $srr->store(
            $client,
            $step,
            $srr->validated($step, $request)
        );
        $intStep = (int)$step;

        return json_encode(['model' =>  $srr->get(StrategyReportRecommendation::where('id',$client->strategy_report_recommendation_id)->first(),$intStep)['model']]);
    }
}
