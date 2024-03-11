<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvestmentRecommendationItem;
use App\Repositories\InvestmentRecommendationItemRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class InvestmentRecommendationItemController extends Controller
{
    protected InvestmentRecommendationItemRepository $investmentRecommendationItemRepository;

    public function __construct(InvestmentRecommendationItemRepository $investmentRecommendationItemRepository)
    {
        $this->investmentRecommendationItemRepository = $investmentRecommendationItemRepository;
    }

    /**
     * @param InvestmentRecommendationItem $item
     */
    public function delete(InvestmentRecommendationItem $item): string
    {
        $this->investmentRecommendationItemRepository->setInvestmentRecommendationItem($item);

        try {
            $this->investmentRecommendationItemRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Investment recommendation item deleted successfully.'
        ]);
    }
}
