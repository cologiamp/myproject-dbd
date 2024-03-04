<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PensionRecommendationItem;
use App\Repositories\PRItemsRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class PRItemsController extends Controller
{
    protected PRItemsRepository $prItemsRepository;

    public function __construct(PRItemsRepository $prItemsRepository)
    {
        $this->prItemsRepository = $prItemsRepository;
    }

    /**
     * @param PensionRecommendationItem $item
     */
    public function delete(PensionRecommendationItem $item): string
    {
        $this->prItemsRepository->setPRItem($item);

        try {
            $this->prItemsRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Pension recommendation item deleted successfully.'
        ]);
    }
}
