<?php

namespace App\Http\Controllers\Api;

use App\Concerns\InterractsWithDataHub;
use App\Http\Controllers\Controller;
use App\Http\Resources\StrategyReportResource;
use App\Models\Client;
use App\Services\StrategyReportDataService;
use App\Models\StrategyReport;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

use DocRaptor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StrategyReportController extends Controller
{
    use InterractsWithDataHub;
    protected $srds;
    public function __construct(StrategyReportDataService $srds)
    {
        $this->srds = $srds;
    }
    public function generate(Client $client): JsonResponse
    {
        if(!($client->strategy_report_recommendation &&
            $client->strategy_report_recommendation->report_version !== null &&
            $client->strategy_report_recommendation->retirement_status  !== null  &&
            $client->strategy_report_recommendation->objective_type !== null
        ))
        {
            return response()->json(['message' => 'You need to complete a Strategy Report Recommendation first'],422);
        }
        if(count($client->strategy_report_recommendation->objectives->filter(function ($item){
            return $item->is_primary;
        })) == 0)
        {
            return response()->json(['message' => 'No Primary Objective entered'],422);
        }
        if(count($client->strategy_report_recommendation->objectives->filter(function ($item){
                return !$item->is_primary;
            })) == 0)
        {
            return response()->json(['message' => 'No Secondary Objective entered'],422);
        }
        if(count($client->employment_details) == 0)
        {
            return response()->json(['message' => 'You must create an employment'],422);
        }

        //Client has 0 employments - please enter one

        $data = $this->srds->getStrategyReportData($client);
        $docraptor = new DocRaptor\DocApi();
        $docraptor->getConfig()->setUsername(config('docraptor.key'));

        $doc = new DocRaptor\Doc();
        if(config('docraptor.test') === true)
        {
            $doc->setTest(true); // test documents are free but watermarked
        }

        $documentContent = view('documents.strategy-report', ['data' => $data])->render();

        $doc->setDocumentContent($documentContent);

        $fileName = "test-strategy-report.pdf";

        $doc->setName($fileName);
        $doc->setDocumentType("pdf");
        $doc->setJavascript(true); // enable JavaScript processing
        $prince_options = new DocRaptor\PrinceOptions(); // pdf-specific options
        $doc->setPrinceOptions($prince_options);
        $prince_options->setMedia("screen"); // use screen styles instead of print styles

        try{

            $create_response = $docraptor->createDoc($doc);
        }
        catch(\Exception $e)
        {
            dd($e);
        }

        $path = '/adviser-hub/strategy-report/pdfs/' . $client->id . '/' . 'strategy-report-' . Str::slug(Carbon::now()->toDayDateTimeString()) . '.pdf';
        $file = Storage::disk('s3')->put($path,$create_response);
        $sr = new StrategyReport();
        $sr->client_id = $client->id;
        $sr->adviser_id = Auth::user()->id;
        $sr->path = $path;
        $sr->save();

        return response()->json(['strategy_reports' => StrategyReportResource::collection(StrategyReport::where('client_id', $client->id)->orderBy('created_at','DESC')->get())]);

    }

    /**
     * @param StrategyReport $report
     * @return void
     */
    public function delete(StrategyReport $strategy_report): JsonResponse
    {
        $client = $strategy_report->client;
        $strategy_report->delete();
        return response()->json(['strategy_reports' => StrategyReportResource::collection(StrategyReport::where('client_id', $client->id)->orderBy('created_at','DESC')->get())]);
    }
}
