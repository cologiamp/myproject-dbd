<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\StrategyRecomObjectives;
use App\Models\StrategyReportRecommendation;
use App\Repositories\StrategyRecomObjectivesRepository;
use App\Services\FactFindSectionDataService;
use App\Services\StrategyReportRecommendationsDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;

class StrategyObjectivesController extends Controller
{
    protected StrategyRecomObjectivesRepository $strategyRecomObjectivesRepository;

    public function __construct(StrategyRecomObjectivesRepository $strategyRecomObjectivesRepository)
    {
        $this->strategyRecomObjectivesRepository = $strategyRecomObjectivesRepository;
    }

    /**
     * @param StrategyRecomObjectives $strategyRecomObjectives
     */
    public function delete(StrategyRecomObjectives $objective): string
    {
        $this->strategyRecomObjectivesRepository->setStrategyReportRecomObjective($objective);

        try {
            $this->strategyRecomObjectivesRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Strategy objective deleted successfully.'
        ]);
    }
}
