<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\InvestmentRecommendation;
use App\Repositories\ClientRepository;
use App\Repositories\InvestmentRecommendationRepository;
use App\Repositories\PensionRecommendationRepository;
use App\Services\InvestmentRecommendationSectionDataService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class InvestmentRecommendationController extends Controller
{
    protected ClientRepository $clientRepository;
    protected InvestmentRecommendationRepository $investmentRecommendationRepository;
    protected PensionRecommendationRepository $pensionRecommendationRepository;
    public function __construct(
        ClientRepository $clientRepository,
        InvestmentRecommendationRepository $investmentRecommendationRepository,
        PensionRecommendationRepository $pensionRecommendationRepository
    )
    {
        $this->clientRepository = $clientRepository;
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
        ray($request)->orange();
        $model = null;
        $investmentRecommendation = $this->investmentRecommendationRepository->getInvestmentRecommendation();
        $model = $investmentRecommendation->where('id', $client->investment_recommendation_id)->first();

        if ($request['investment_recommendation_items'] && $request['investment_recommendation_items'] != null) {
            $request['investment_recommendation_items'] = collect($request['investment_recommendation_items'])->flatten(1)->toArray();
        }
        if (array_key_exists('pension_recommendation', $request->all()) ||
            array_key_exists('prnew_contributions', $request->all()) ||
            array_key_exists('pr_annual_allowances', $request->all()) ||
            array_key_exists('pr_items', $request->all())
        ) {
            $pensionRecommendation = $this->pensionRecommendationRepository->getPensionRecommendation();
            $model = $pensionRecommendation->where('id', $client->pension_recommendation_id)->first();
        }
        if ($request['existing_pension_plans'] && $request['existing_pension_plans'] != null) {
            $model = $client;
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
