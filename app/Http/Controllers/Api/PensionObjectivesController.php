<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Retirement;
use App\Repositories\ClientRepository;
use App\Services\FactFindSectionDataService;
use App\Services\PensionObjectivesDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;

class PensionObjectivesController extends Controller
{
    /**
     * @param Client $client
     * @param $section
     * @param $step
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Client $client, $step, Request $request): string
    {
        $pods = App::make(PensionObjectivesDataService::class);

        try{
            $pods->validate($step, $request); //throws exception if validation fails - comes back to Inertia as errorbag
        }
        catch (Exception $e)
        {
            Log::warning($e);
        }
        $pods->store(
            $client,
            $step,
            $pods->validated($step, $request)
        );
        $intStep = (int)$step;
        return json_encode(['model' =>  $pods->get(Retirement::with('client')->where('client_id',$client->id)->first(),$intStep)['model']]);
    }
}
