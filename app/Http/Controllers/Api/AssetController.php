<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Repositories\AssetRepository;
use Illuminate\Http\Request;

class AssetController extends Controller
{
    protected $ar;
    public function __construct(AssetRepository $ar)
    {
        $this->ar = $ar;
    }

    /**
     * Deletes a given asset
     * @param Asset $asset
     * @return true
     */
    public function delete(Asset $asset):bool
    {
        $this->ar->setAsset($asset);
        $this->ar->delete();
        return true;
    }
}
