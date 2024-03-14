<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PRAnnualAllowance;
use App\Repositories\PRAnnualAllowanceRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class PRAllowanceController extends Controller
{
    protected PRAnnualAllowanceRepository $prAnnualAllowanceRepository;

    public function __construct(PRAnnualAllowanceRepository $prAnnualAllowanceRepository)
    {
        $this->prAnnualAllowanceRepository = $prAnnualAllowanceRepository;
    }

    /**
     * @param PRAnnualAllowance $allowance
     */
    public function delete(PRAnnualAllowance $allowance): string
    {
        $this->prAnnualAllowanceRepository->setPRAnnualAllowance($allowance);

        try {
            $this->prAnnualAllowanceRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Pension recommendation allowance deleted successfully.'
        ]);
    }
}
