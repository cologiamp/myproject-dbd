<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExampleEditRequest;
use App\Models\Client;
use App\Repositories\ClientRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ExampleController extends Controller
{
    protected ClientRepository $clientRepository;
    public function __construct(ClientRepository $cr)
    {
        $this->clientRepository = $cr;
    }
    public function edit(Client $client, Request $request): \Inertia\Response
    {
        $this->clientRepository->setClient($client);
        return Inertia::render('ExampleForm',[
            'title' => 'Example Form',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'step' =>  $request->step,
            'section' => $request->section,
            'formData' => $this->clientRepository->getExampleFormOptions()
        ]);
    }

    public function update(Client $client, ExampleEditRequest $request):RedirectResponse
    {
//        dd($request->all());
        return to_route('client.example.edit',['client' => $client->io_id]);
    }
}
