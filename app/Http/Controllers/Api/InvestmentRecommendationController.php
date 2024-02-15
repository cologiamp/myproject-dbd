<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\InvestmentRecommendation;
use App\Services\InvestmentRecommendationSectionDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;

class InvestmentRecommendationController extends Controller
{
    /**
     * @param InvestmentRecommendation $investmentRecommendation
     * @param $section
     * @param $step
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Client $client, $step, $section, Request $request): string
    {
        $investmentRecommendation = $client->investment_recommendation()->first();
        $irsds = App::make(InvestmentRecommendationSectionDataService::class);

        try{
            $irsds->validate($step, $section, $request); //throws exception if validation fails - comes back to Inertia as errorbag
        }
        catch (Exception $e)
        {
            Log::warning($e);
        }
        $irsds->store(
            $investmentRecommendation,
            $step,
            $section,
            $irsds->validated($step, $section, $request)
        );

        return response()->json(['investmentRecommendation' => $investmentRecommendation, 'step' => $step, 'section' => $section]);
    }
}
