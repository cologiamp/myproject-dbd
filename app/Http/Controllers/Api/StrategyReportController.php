<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\FactFindSectionDataService;
use App\Services\StrategyReportDataService;
use Illuminate\Http\Request;

class StrategyReportController extends Controller
{
    protected $srds;
    public function __construct(StrategyReportDataService $srds)
    {
        $this->srds = $srds;
    }
    public function __invoke(Client $client)
    {
        $data = $this->srds->getStrategyReportData($client);
        dd($data);
    }
}
