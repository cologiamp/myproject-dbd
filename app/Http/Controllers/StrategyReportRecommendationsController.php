<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Repositories\ClientRepository;
//use App\Services\FactFindSectionDataService;
use App\Services\FactFindSectionDataService;
use App\Services\PensionObjectivesDataService;
use App\Services\StrategyReportRecommendationsDataService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use App\Repositories\StrategyReportRecomRepository;

class StrategyReportRecommendationsController extends Controller
{
    protected ClientRepository $clientRepository;
    protected StrategyReportRecomRepository $strategyReportRecomRepository;
    public function __construct(ClientRepository $cr, StrategyReportRecomRepository $strategyReportRecomRepository)
    {
        $this->clientRepository = $cr;
        $this->strategyReportRecomRepository = $strategyReportRecomRepository;
    }
    public function show(Client $client, Request $request) //pension-objectives?step=1
    {
        $this->clientRepository->setClient($client);

        if($client->strategy_report_recommendation_id == null)
        {
            $newRecord = $this->strategyReportRecomRepository->createStrategyReportRecom();

            $client->update(['strategy_report_recommendation_id' => $newRecord->id]);
            $client->save();

            $this->strategyReportRecomRepository->setStrategyReportRecom($newRecord);
        }

        $step = $request->step ?? 1;
        $tabs = $this->clientRepository->loadStrategyReportRecomTabs($step);
        return Inertia::render('StrategyReportRecommendations', [
            'title' => 'Strategy Report Recommendations',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'step' =>  $step,
            'tabs' => $tabs
        ]);
    }

    /**
     * @param Client $client
     * @param $section
     * @param $step
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Client $client, $step, Request $request): \Illuminate\Http\RedirectResponse
    {
        $pods = App::make(StrategyReportRecommendationsDataService::class);

        try{
            $pods->validate($step, $request); //throws exception if validation fails - comes back to Inertia as errorbag
        }
        catch (Exception $e)
        {
            Log::warning($e);
        }
        $pods->store(
            $client,
            $step,
            $pods->validated($step, $request)
        );

        return to_route('client.strategyreportrecommendations', ['client' => $client, 'step' => $step]);
    }
}
