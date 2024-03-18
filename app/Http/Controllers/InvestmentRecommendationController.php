<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\InvestmentRecommendation;
use App\Repositories\InvestmentRecommendationRepository;
use App\Repositories\PensionRecommendationRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvestmentRecommendationController extends Controller
{
    protected InvestmentRecommendationRepository $investmentRecommendationRepository;
    protected PensionRecommendationRepository $pensionRecommendationRepository;

    public function __construct(InvestmentRecommendationRepository $investmentRecommendationRepository, PensionRecommendationRepository $pensionRecommendationRepository)
    {
        $this->investmentRecommendationRepository = $investmentRecommendationRepository;
        $this->pensionRecommendationRepository = $pensionRecommendationRepository;
    }

    public function show(Client $client, Request $request)
    {
        $this->investmentRecommendationRepository->setClient($client);

        if(!$client->investment_recommendation_id) {
            $newRecord = $this->investmentRecommendationRepository->createInitialInvestmentRecommendationForClient();

            $client->update(['investment_recommendation_id' => $newRecord->id]);
            $client->save();

            $this->investmentRecommendationRepository->setInvestmentRecommendation($newRecord);
        } else {
            $investmentRecommendation = InvestmentRecommendation::where('id',$client->investment_recommendation_id)->first();
            $this->investmentRecommendationRepository->setInvestmentRecommendation($investmentRecommendation);
        }

        if(!$client->pension_recommendation_id) {
            $newPension = $this->pensionRecommendationRepository->createInitialPensionRecommendationForClient();

            $client->update(['pension_recommendation_id' => $newPension->id]);
            $client->save();
        }

        $section = $request->section ?? 1;
        $step = $request->step ?? 1;
        $tabs = $this->investmentRecommendationRepository->loadInvestmentRecommendationTabs($step,$section);

        return Inertia::render('Recommendations', [
            'title' => 'Recommendations',
            'breadcrumbs' => $this->investmentRecommendationRepository->loadBreadcrumbs(),
            'step' =>  $step,
            'section' => $section,
            'tabs' => $tabs
        ]);
    }
}
