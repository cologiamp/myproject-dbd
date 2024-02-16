<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\InvestmentRecommendation;
use App\Repositories\InvestmentRecommendationRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class InvestmentRecommendationController extends Controller
{
    protected InvestmentRecommendationRepository $investmentRecommendationRepository;

    public function __construct(InvestmentRecommendationRepository $investmentRecommendationRepository)
    {
        $this->investmentRecommendationRepository = $investmentRecommendationRepository;
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

        $section = $request->section ?? 1;
        $step = $request->step ?? 1;
        $tabs = $this->investmentRecommendationRepository->loadInvestmentRecommendationTabs($step,$section);

        return Inertia::render('InvestmentRecommendation', [
            'title' => 'Investment Recommendation',
            'breadcrumbs' => $this->investmentRecommendationRepository->loadBreadcrumbs(),
            'step' =>  $step,
            'section' => $section,
            'tabs' => $tabs,
            'progress' => $this->investmentRecommendationRepository->calculateInvestmentRecommendationElementProgress($step)
        ]);
    }
}
