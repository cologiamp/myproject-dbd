<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Repositories\ClientRepository;
//use App\Services\FactFindSectionDataService;
use App\Services\FactFindSectionDataService;
use App\Services\PensionObjectivesDataService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PensionObjectivesController extends Controller
{

    protected ClientRepository $clientRepository;
    public function __construct(ClientRepository $cr)
    {
        $this->clientRepository = $cr;
    }
    public function show(Client $client, Request $request) //pension-objectives?step=1
    {
        $this->clientRepository->setClient($client);

        if($client->retirement == null)
        {
            $this->clientRepository->createRetirement();
        }

        //dd($request->step);

        $step = $request->step ?? 1;
        $tabs = $this->clientRepository->loadPensionObjectivesTabs($step);


        return Inertia::render('PensionObjectives', [
            'title' => 'Pension Objectives',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'step' =>  $step,
            'tabs' => $tabs
        ]);
    }

    /**
     * @param Client $client
     * @param $section
     * @param $step
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Client $client, $step, Request $request): \Illuminate\Http\RedirectResponse
    {
        //dd($request);
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

        return to_route('client.pensionobjectives', ['client' => $client, 'step' => $step]);
    }
}
