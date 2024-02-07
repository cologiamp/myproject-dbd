<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LumpSumCapital;
use App\Repositories\LumpSumCapitalRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LumpSumCapitalController extends Controller
{
    protected LumpSumCapitalRepository $lumpSumCapitalRepository;

    public function __construct(LumpSumCapitalRepository $lumpSumCapitalRepository)
    {
        $this->lumpSumCapitalRepository = $lumpSumCapitalRepository;
    }

    /**
     * @param LumpSumCapital $lumpSumCapital
     */
    public function delete(LumpSumCapital $lsc): string
    {
        $this->lumpSumCapitalRepository->setCapital($lsc);

        try {
            $this->lumpSumCapitalRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Capital deleted successfully.'
        ]);
    }
}
