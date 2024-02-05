<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Liability;
use App\Repositories\LiabilityRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class LiabilityController extends Controller
{
    protected LiabilityRepository $liabilityRepository;

    public function __construct(LiabilityRepository $liabilityRepository)
    {
        $this->liabilityRepository = $liabilityRepository;
    }

    /**
     * @param Liability $liability
     */
    public function delete(Liability $liability): string
    {
        $this->liabilityRepository->setLiability($liability);

        try {
            $this->liabilityRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Liability deleted successfully.'
        ]);
    }
}
