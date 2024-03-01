<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PRNewContribution;
use App\Repositories\PRNewContributionRepository;
use Exception;
use Illuminate\Support\Facades\Log;

class PRContributionController extends Controller
{
    protected PRNewContributionRepository $prNewContributionRepository;

    public function __construct(PRNewContributionRepository $prNewContributionRepository)
    {
        $this->prNewContributionRepository = $prNewContributionRepository;
    }

    /**
     * @param PRNewContribution $contribution
     */
    public function delete(PRNewContribution $contribution): string
    {
        $this->prNewContributionRepository->setPRNewContribution($contribution);

        try {
            $this->prNewContributionRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Pension recommendation contribution deleted successfully.'
        ]);
    }
}
