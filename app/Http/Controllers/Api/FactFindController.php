<?php

namespace App\Http\Controllers\Api;

use App\Concerns\ParsesIoClientData;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Services\FactFindSectionDataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Exception;

class FactFindController extends Controller
{
    use ParsesIoClientData;
    public function solo(Client $client)
    {
        $cr = App::make(ClientRepository::class);
        $cr->setClient($client);
        $cr->updateFromValidated(['declined_relationships' => true,'c2_id' => null,'relation_to_c2' => null]);
        return true;
    }

    public function selectClientTwo(Client $client, $c2id, Request $request)
    {
        $client2 = Client::where('io_id',$c2id)->first();
        if($client2 == null)
        {
            $this->fetchAndHandleClient(null,$c2id);
            $client2 = Client::where('io_id',$c2id)->first();
        }

        $cr = App::make(ClientRepository::class);
        $cr->setClient($client);
        $cr->updateFromValidated(['c2_id' => $client2->id,'relation_to_c2' => array_flip(config('enums.relation_to_c2'))[$request->relationship]]);
    }



    /**
     * @param Client $client
     * @param $section
     * @param $step
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Client $client, $step, $section, Request $request): string
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
        return json_encode(['model' =>  $ffsds->get(Client::where('id',$client->id)->first(),$step,$section)['model']]);
    }
}
