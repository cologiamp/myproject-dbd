<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Models\EmploymentDetail;
use App\Repositories\AssetRepository;
use App\Repositories\EmploymentDetailRepository;
use Illuminate\Http\Request;

class EmploymentDetailController extends Controller
{
    protected $edr;
    public function __construct(EmploymentDetailRepository $edr)
    {
        $this->edr = $edr;
    }

    /**
     * Deletes a given asset
     * @param Asset $asset
     * @return true
     */
    public function delete(EmploymentDetail $employment_detail):bool
    {
        $this->edr->setEmploymentDetail($employment_detail);
        $this->edr->delete();
        return true;
    }
}
