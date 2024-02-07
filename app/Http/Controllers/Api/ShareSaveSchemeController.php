<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShareSaveScheme;
use App\Repositories\ShareSaveRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ShareSaveSchemeController extends Controller
{
    protected ShareSaveRepository $shareSaveRepository;

    public function __construct(ShareSaveRepository $shareSaveRepository)
    {
        $this->shareSaveRepository = $shareSaveRepository;
    }

    /**
     * @param ShareSaveScheme $scheme
     */
    public function delete(ShareSaveScheme $scheme): string
    {
        $this->shareSaveRepository->setScheme($scheme);

        try {
            $this->shareSaveRepository->delete();
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Share Save Scheme deleted successfully.'
        ]);
    }
}
