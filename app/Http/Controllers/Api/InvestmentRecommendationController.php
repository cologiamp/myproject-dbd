<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Repositories\InvestmentRecommendationRepository;
use App\Models\InvestmentRecommendation;
use App\Services\InvestmentRecommendationSectionDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;

class InvestmentRecommendationController extends Controller
{
    protected InvestmentRecommendationRepository $investmentRecommendationRepository;
    public function __construct(InvestmentRecommendationRepository $investmentRecommendationRepository)
    {
        $this->investmentRecommendationRepository = $investmentRecommendationRepository;
    }

    /**
     * @param InvestmentRecommendation $investmentRecommendation
     * @param $section
     * @param $step
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Client $client, $step, $section, Request $request): string
    {
        $investmentRecommendation = $this->investmentRecommendationRepository->getInvestmentRecommendation();
        $investmentRecommendation = $investmentRecommendation->where('id', $client->investment_recommendation_id)->first();

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
