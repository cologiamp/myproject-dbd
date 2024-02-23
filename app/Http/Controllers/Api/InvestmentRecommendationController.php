<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Repositories\InvestmentRecommendationRepository;
use App\Models\InvestmentRecommendation;
use App\Services\InvestmentRecommendationSectionDataService;
use App\Repositories\PensionRecommendationRepository;
use App\Models\PensionRecommendation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;

class InvestmentRecommendationController extends Controller
{
    protected InvestmentRecommendationRepository $investmentRecommendationRepository;
    protected PensionRecommendationRepository $pensionRecommendationRepository;
    public function __construct(
        InvestmentRecommendationRepository $investmentRecommendationRepository,
        PensionRecommendationRepository $pensionRecommendationRepository
    )
    {
        $this->investmentRecommendationRepository = $investmentRecommendationRepository;
        $this->pensionRecommendationRepository = $pensionRecommendationRepository;
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
        $model = null;
        $investmentRecommendation = $this->investmentRecommendationRepository->getInvestmentRecommendation();
        $model = $investmentRecommendation->where('id', $client->investment_recommendation_id)->first();

        if ($request['investment_recommendation_items'] && $request['investment_recommendation_items'] != null) {
            $request['investment_recommendation_items'] = collect($request['investment_recommendation_items'])->flatten(1)->toArray();
        }
        if ($request['pension_recommendation'] && $request['pension_recommendation'] != null) {
            ray('PensionRecommendation')->orange();
            $pensionRecommendation = $this->pensionRecommendationRepository->getPensionRecommendation();
            $model = $pensionRecommendation->where('id', $client->pension_recommendation_id)->first();
        }

        $irsds = App::make(InvestmentRecommendationSectionDataService::class);

        try{
            $irsds->validate($step, $section, $request); //throws exception if validation fails - comes back to Inertia as errorbag
        }
        catch (Exception $e)
        {
            Log::warning($e);
        }
        $irsds->store(
            $model,
            $step,
            $section,
            $irsds->validated($step, $section, $request)
        );

        return json_encode(['model' =>  $irsds->get(InvestmentRecommendation::where('id',$client->investment_recommendation_id)->first(),$step,$section)['model']]);
    }
}
