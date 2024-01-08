<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExampleEditRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ExampleController extends Controller
{
    protected ClientRepository $clientRepository;
    public function __construct(ClientRepository $cr)
    {
        $this->clientRepository = $cr;
    }

    /**
     * Initialise the correct Client to the ClientRepository and then render the form.
     * @param Client $client
     * @param Request $request
     * @return \Inertia\Response
     */
    public function edit(Client $client, Request $request): \Inertia\Response
    {
        $this->clientRepository->setClient($client);
        $section = $request->section ?? 1;
        Log::info($section);
        return Inertia::render('ExampleForm',[
            'title' => 'Example Form',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'step' =>  $request->step,
            'section' => $section,
            'formData' => $this->clientRepository->getExampleFormOptions(),
            'progress' => $this->clientRepository->calculateFactFindElementProgress($client, $section)
        ]);
    }

    /**
     * Set the Client in the correct repository, before updating and reloading with Inertia.
     * @param Client $client
     * @param ExampleEditRequest $request
     * @return RedirectResponse
     */
    public function update(Client $client, ExampleEditRequest $request):RedirectResponse
    {
        $this->clientRepository->setClient($client);
        $this->clientRepository->update($request);
        return to_route('client.example.edit',[
            'client' => $client->io_id,
            'section' => $request->safe()->only('section')["section"]
        ]);
    }
}
