<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Services\FactFindSectionDataService;
use App\Services\StrategyReportDataService;
use Illuminate\Http\Request;

use DocRaptor;
use App\Concerns\HandlesS3Uploads;
use Illuminate\Http\RedirectResponse;

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

        $docraptor = new DocRaptor\DocApi();
        $docraptor->getConfig()->setUsername(env('DOCRAPTOR_API_KEY'));

        $doc = new DocRaptor\Doc();
        $doc->setTest(true); // test documents are free but watermarked

        $documentContent = view('documents.strategy-report', [])->render();

        $doc->setDocumentContent($documentContent);

        $fileName = "test-strategy-report.pdf";

        $doc->setName($fileName);
        $doc->setDocumentType("pdf");
        $doc->setJavascript(true); // enable JavaScript processing
        $prince_options = new DocRaptor\PrinceOptions(); // pdf-specific options
        $doc->setPrinceOptions($prince_options);
        $prince_options->setMedia("screen"); // use screen styles instead of print styles

        $create_response = $docraptor->createDoc($doc);

        $this->handleFileUpload($create_response);
        //dd("HI");
        //dd($create_response);

        header('Content-Description: File Transfer');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename='.$fileName);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . strlen($create_response));
        if (ob_get_length()) ob_clean();
        flush();
        echo($create_response);
        exit;

        //dd($data);
    }

    private function handleFileUpload(String $request):string
    {
        //dd($request);
        $this->uploadToS3($request);
        /*
        $filetypes = ['profile_pic_data','business_card_data','bio_card_short','bio_card_long'];
        return collect($filetypes)->mapWithKeys(function ($file_type) use ($request){
            if($request->hasFile($file_type))
            {
                return [$file_type =>$this->uploadToS3($request->file($file_type),$file_type)];
            }
            else return [$file_type =>null];
        })->toArray();
        */

    }
}
