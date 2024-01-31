<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Services\FactFindSectionDataService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class FactFindController extends Controller
{
    protected ClientRepository $clientRepository;
    public function __construct(ClientRepository $cr)
    {
        $this->clientRepository = $cr;
    }
    public function show(Client $client, Request $request) //fact-find?step=1&section=6
    {
        //$req->step - which tab to use
        //$req->section - which sidebar item to use
        $this->clientRepository->setClient($client);
        $section = $request->section ?? 1;
        $step = $request->step ?? 1;

        $tabs = $this->clientRepository->loadFactFindTabs($step,$section);

        return Inertia::render('FactFind', [
            'title' => 'Fact Find',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'step' =>  $step,
            'section' => $section,
            'tabs' => $tabs,
            'progress' => $this->clientRepository->calculateFactFindElementProgress($step)
        ]);
    }

    /**
     * @param Client $client
     * @param $section
     * @param $step
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Client $client, $step, $section, Request $request): \Illuminate\Http\RedirectResponse
    {
        $ffsds = App::make(FactFindSectionDataService::class);
        
        if ($step == 2 && $section >= 2) {
            $request['expenditures'] = collect($request['expenditures'])->filter()->flatten(1)->toArray();
        }

        
        try{
            $ffsds->validate($step, $section, $request); //throws exception if validation fails - comes back to Inertia as errorbag
        }
        catch (Exception $e)
        {
            Log::warning($e);
        }

        $ffsds->store(
            $client,
            $step,
            $section,
            $ffsds->validated($step, $section, $request)
        );

        return to_route('client.factfind', ['client' => $client, 'step' => $step, 'section' => $section]);
    }
}
