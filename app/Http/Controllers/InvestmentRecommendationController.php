<?php

namespace App\Http\Controllers;

use App\Models\InvestmentRecommendation;
use App\Repositories\InvestmentRecommendationRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvestmentRecommendationController extends Controller
{
    protected InvestmentRecommendationRepository $investmentRecommendationRepository;

    public function __construct(InvestmentRecommendationRepository $investmentRecommendationRepository)
    {
        $this->investmentRecommendationRepository = $investmentRecommendationRepository;
    }

    public function show(InvestmentRecommendation $investmentRecommendation, Request $request)
    {
        $this->investmentRecommendationRepository->setInvestmentRecommendation($investmentRecommendation);
        $section = $request->section ?? 1;
        $step = $request->step ?? 1;
        $tabs = $this->investmentRecommendationRepository->loadInvestmentRecommendationTabs($step,$section);

        return Inertia::render('FactFind', [
            'title' => 'Fact Find',
//            'breadcrumbs' => $this->investmentRecommendationRepository->loadBreadcrumbs(),
            'step' =>  $step,
            'section' => $section,
            'tabs' => $tabs,
//            'progress' => $this->investmentRecommendationRepository->calculateFactFindElementProgress($step)
        ]);
    }
}
