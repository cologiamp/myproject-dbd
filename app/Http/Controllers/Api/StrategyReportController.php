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
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
class StrategyReportController extends Controller
{
    protected $srds;
    public function __construct(StrategyReportDataService $srds)
    {
        $this->srds = $srds;
    }
    public function __invoke(Client $client): RedirectResponse
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

        $fileUploadedPath = $this->handleFileUpload($create_response);
        dd($fileUploadedPath);

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

    private function handleFileUpload(String $file):string
    {
        //$binary_data = base64_decode($file);
        //Storage::disk('s3')->put($s3Path, $binary_data, 'public');

        $path = "/adviser-hub/strategy-report/" . 'new-pdf' . '_' . time() . '.' . 'pdf';
        try {
            //Storage::disk('s3')->put($path, $binary_data, 'public');
            Storage::disk('s3')->putFile($path, new File($file));

        } catch (S3Exception $e) {
            dd($e);
        }
        //Storage::disk('s3')->put($path, $binary_data, 'public');
//        Storage::disk('s3')->put($path, file_get_contents($binary_data));
        return $path;
    }
}
