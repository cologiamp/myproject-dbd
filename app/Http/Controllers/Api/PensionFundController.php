<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PensionFund;
use App\Models\PensionScheme;
use App\Repositories\PensionRepository;
use Illuminate\Http\Request;

class PensionFundController extends Controller
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
    public function delete(PensionFund $pension_fund):bool
    {
        $this->pr->deletePensionFund($pension_fund);
        return true;
    }
}