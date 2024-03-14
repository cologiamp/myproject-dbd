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
        if(!($client->strategy_report_recommendation &&
            $client->strategy_report_recommendation->report_version !== null &&
            $client->strategy_report_recommendation->retirement_status  !== null  &&
            $client->strategy_report_recommendation->objective_type !== null
        ))
        {
            return response()->json(['message' => 'You need to complete a Strategy Report Recommendation first'],422);
        }
        $data = $this->srds->getStrategyReportData($client);
        dd($data);
    }
}
