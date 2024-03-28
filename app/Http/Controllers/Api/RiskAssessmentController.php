<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\RiskOutcome;
use App\Repositories\ClientRepository;
use App\Repositories\RiskOutcomeRepository;
use App\Services\RiskAssessmentSectionDataService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class RiskAssessmentController extends Controller
{
    protected ClientRepository $clientRepository;
    protected RiskOutcomeRepository $riskOutcomeRepository;
    public function __construct(
        ClientRepository $clientRepository,
        RiskOutcomeRepository $riskOutcomeRepository
    )
    {
        $this->clientRepository = $clientRepository;
        $this->riskOutcomeRepository = $riskOutcomeRepository;
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
        $rasds = App::make(RiskAssessmentSectionDataService::class);

        try{
            $rasds->validate($step, $section, $request); //throws exception if validation fails - comes back to Inertia as errorbag
        }
        catch (Exception $e)
        {
            Log::warning($e);
        }
        $rasds->store(
            $client,
            $step,
            $section,
            $rasds->validated($step, $section, $request)
        );

        //change later
        return json_encode(['model' =>  $rasds->get(Client::where('id',$client->id)->first(),$step,$section, null)['model']]);
    }

    /**
     * @param Request $request
     * @param RiskOutcome $outcome
     * @return RedirectResponse
     */
    public function assessOutcome(Request $request, RiskOutcome $outcome): string
    {
        $validated = $request->validate([
            'score' => 'nullable|integer',
            'type' => 'required|string',
            'section' => 'required|string'
        ]);

        $fieldName = $validated['section'] . '_' . $validated['type'];

        if ($validated['type'] === 'risk_profile') {
            $fieldName = 'attitude_to_risk';
        }

        try {
            $outcome->update([$fieldName => $validated['score']]);

            if ($validated['type'] === 'risk_profile') {
                App::make(RiskAssessmentSectionDataService::class)->assessMatrixResult($outcome);
            }
        } catch (Exception $e) {
            Log::warning($e);
        }

        return response()->json([
            'message' => 'Assessment submitted.'
        ]);
    }
}
