<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Expenditure;
use App\Repositories\ExpenditureRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class ExpenditureController extends Controller
{
    protected ExpenditureRepository $expenditureRepository;

    public function __construct(ExpenditureRepository $expenditureRepository)
    {
        $this->expenditureRepository = $expenditureRepository;
    }

    /**
     * @param Expenditure $expenditure
     */
    public function delete(Expenditure $expenditure)
    {
        $this->expenditureRepository->setExpenditure($expenditure);

        try {
            $this->expenditureRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Expenditure deleted successfully.'
        ]);
    }

}
