<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StrategyRecommendationObjective;
use App\Repositories\StrategyReportRecommendationObjectivesRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class StrategyObjectivesController extends Controller
{
    protected StrategyReportRecommendationObjectivesRepository $strategyReportRecommendationObjectivesRepository;

    public function __construct(StrategyReportRecommendationObjectivesRepository $strategyReportRecommendationObjectivesRepository)
    {
        $this->strategyReportRecommendationObjectivesRepository = $strategyReportRecommendationObjectivesRepository;
    }

    /**
     * @param StrategyRecommendationObjective $strategyRecommendationObjective
     */
    public function delete(StrategyRecommendationObjective $objective): string
    {
        $this->strategyReportRecommendationObjectivesRepository->setStrategyReportRecommendationObjective($objective);

        try {
            $this->strategyReportRecommendationObjectivesRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Strategy objective deleted successfully.'
        ]);
    }

    /**
     * @param StrategyRecommendationObjective $strategyRecommendationObjective
     */
    public function get(StrategyRecommendationObjective $objective): string
    {
        return json_encode($objective);
    }
}
