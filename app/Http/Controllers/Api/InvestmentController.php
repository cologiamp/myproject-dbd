<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OtherInvestment;
use App\Models\ShareSaveScheme;
use App\Repositories\InvestmentRepository;
use App\Repositories\ShareSaveRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvestmentController extends Controller
{
    protected InvestmentRepository $investmentRepository;

    public function __construct(InvestmentRepository $investmentRepository)
    {
        $this->investmentRepository = $investmentRepository;
    }

    /**
     * @param OtherInvestment $investment
     */
    public function delete(OtherInvestment $investment): string
    {
        $this->investmentRepository->setInvestment($investment);

        try {
            $this->investmentRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Investment deleted successfully.'
        ]);
    }
}
