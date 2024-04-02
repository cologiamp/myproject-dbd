<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Income;
use App\Repositories\IncomeRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class IncomeController extends Controller
{
    protected IncomeRepository $incomeRepository;

    public function __construct(IncomeRepository $incomeRepository)
    {
        $this->incomeRepository = $incomeRepository;
    }

    /**
     * @param Income $income
     */
    public function delete(Income $income): string
    {

        $this->incomeRepository->setIncome($income);

        try {
            $this->incomeRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Income deleted successfully.'
        ]);
    }

}
