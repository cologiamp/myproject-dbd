<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StrategyRecommendationAction;
use App\Repositories\StrategyReportRecommendationActionsRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class StrategyActionsController extends Controller
{
    protected StrategyReportRecommendationActionsRepository $strategyReportRecommendationActionsRepository;

    public function __construct(StrategyReportRecommendationActionsRepository $strategyReportRecommendationActionsRepository)
    {
        $this->strategyReportRecommendationActionsRepository = $strategyReportRecommendationActionsRepository;
    }

    /**
     * @param StrategyReportRecommendationActionsRepository $strategyReportRecommendationActionsRepository
     */
    public function delete(StrategyRecommendationAction $action): string
    {
        $this->strategyReportRecommendationActionsRepository->setStrategyReportRecommendationAction($action);

        try {
            $this->strategyReportRecommendationActionsRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Strategy action deleted successfully.'
        ]);
    }

    /**
     * @param StrategyReportRecommendationActionsRepository $strategyRecomemndationActionsRepository
     */
    public function get(StrategyRecommendationAction $action): string
    {
        return json_encode($action);
    }
}
