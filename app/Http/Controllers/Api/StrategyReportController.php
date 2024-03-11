<?php

namespace App\Http\Controllers\Api;

use App\Concerns\InterractsWithDataHub;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\StrategyReportDataService;
use Illuminate\Support\Facades\Cache;

class StrategyReportController extends Controller
{
    use InterractsWithDataHub;
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
