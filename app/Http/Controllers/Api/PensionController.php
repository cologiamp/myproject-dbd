<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\PensionScheme;
use App\Repositories\AssetRepository;
use App\Repositories\PensionRepository;
use Illuminate\Http\Request;

class PensionController extends Controller
{
    protected $pr;
    public function __construct(PensionRepository $pr)
    {
        $this->pr = $pr;
    }

    /**
     * Deletes a given asset
     * @param PensionScheme $pension
     * @return true
     */
    public function delete(PensionScheme $pension):bool
    {
        $this->pr->deletePension($pension);
        return true;
    }
}
