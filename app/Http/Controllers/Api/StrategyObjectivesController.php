<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StrategyRecomObjectives;
use App\Repositories\StrategyRecomObjectivesRepository;
use Exception;
use Illuminate\Support\Facades\Log;

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

    public function get($id)
    {
        $objective = null;

        try{
            $objective = StrategyRecomObjectives::where('id', $id)->first();
        }
        catch (Exception $e)
        {
            Log::warning($e);
        }
        return json_encode($objective);
    }
}
