<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StrategyRecomActions;
use App\Repositories\StrategyRecomActionsRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class StrategyActionsController extends Controller
{
    protected StrategyRecomActionsRepository $strategyRecomActionsRepository;

    public function __construct(StrategyRecomActionsRepository $strategyRecomActionsRepository)
    {
        $this->strategyRecomActionsRepository = $strategyRecomActionsRepository;
    }

    /**
     * @param StrategyRecomActionsRepository $strategyRecomActionsRepository
     */
    public function delete(StrategyRecomActions $action): string
    {
        $this->strategyRecomActionsRepository->setStrategyReportRecomAction($action);

        try {
            $this->strategyRecomActionsRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Strategy action deleted successfully.'
        ]);
    }

    public function get($id)
    {
        $action = null;

        try{
            $action = StrategyRecomActions::where('id', $id)->first();
        }
        catch (Exception $e)
        {
            Log::warning($e);
        }
        return json_encode($action);
    }
}
