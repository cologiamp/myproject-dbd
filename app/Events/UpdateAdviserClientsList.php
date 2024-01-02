<?php

namespace App\Events;

use App\Repositories\ClientRepository;
use App\Services\DataIngestService;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\App;

class UpdateAdviserClientsList
{
    protected ClientRepository $clientRepository;
    protected DataIngestService $dataIngestService;

    public function __construct()
    {
        $this->clientRepository = App::make(ClientRepository::class);
        $this->dataIngestService = App::make(DataIngestService::class);
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        if($event->user->io_id && $event->user->hasRole(['adviser','admin']))
        {
            //Chore: dispatch this in a queue job if taking too long
            $this->clientRepository->syncIoForAdviser($event->user->io_id,$this->dataIngestService->getClientsForAdviser($event->user->io_id));
        }
    }
}
