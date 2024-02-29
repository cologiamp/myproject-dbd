<?php

namespace App\Services;


use App\Concerns\FormatsCurrency;
use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Models\StrategyReportRecommendation;
use App\Repositories\StrategyReportRecomRepository;
use App\Repositories\StrategyRecomObjectivesRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class StrategyReportRecommendationsDataService
{
    use FormatsCurrency;
    protected ClientRepository $clientRepository;
    protected StrategyReportRecomRepository $strategyReportRecomRepository;
    protected StrategyRecomObjectivesRepository $strategyRecomObjectivesRepository;
    public function __construct(
        ClientRepository $clientRepository,
        StrategyReportRecomRepository $strategyReportRecomRepository,
        StrategyRecomObjectivesRepository $strategyRecomObjectivesRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->strategyReportRecomRepository = $strategyReportRecomRepository;
        $this->strategyRecomObjectivesRepository = $strategyRecomObjectivesRepository;
    }

    //get the data for a single section of a factfind from a single client
    public static function get($strategy, $step): array
    {
        return [
            'enums' => $strategy->loadEnumsForStrategyRecommendationsStep($step),
            'model' => $strategy->presenter()->formatForSRRStep($step), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
            'submit_url' => '/api/client/' . $strategy->primary_client->io_id . '/strategy-report-recommendations/' . $step  //here we hydrate the autosave URL
        ];
    }


    public function validate(int $step, Request $request): array
    {
        return Validator::make($request->all(), config('navigation_structures.strategyreportrecommendations.' . $step  . '.rules'))->validate();
    }

    public function validated(int $step, Request $request): array
    {
        return Validator::make($request->all(), config('navigation_structures.strategyreportrecommendations.' . $step .  '.rules'))->validated();
    }

    /**
     * @param $client
     * @param $section
     * @param $step
     * @param array $validateata
     * @return true
     */
    public function store(Client $client, int $step, array $validatedData): true
    {
        $this->clientRepository->setClient($client);
        $this->strategyReportRecomRepository->setStrategyReportRecom(StrategyReportRecommendation::where('id', $client->strategy_report_recommendation_id)->first());
        $this->{"saveTab" . $step}($validatedData);
        return true;
    }

    //These methods will PARSE the data before passing it to the REPOSITORY to save in the database
    private function saveTab1(array $validatedData):void
    {
        if(array_key_exists('next_meeting_date',$validatedData))
        {
            if($validatedData['next_meeting_date'] != null)
            {
                $validatedData['next_meeting_date'] = Carbon::parse($validatedData['next_meeting_date']);
            }

        }

        try {
            $this->strategyReportRecomRepository->update($validatedData);
        }
        catch(Throwable $e){
            Log::warning($e);
            dd($e);
        }

    }

    private function saveTab2(array $validatedData):void
    {
        try {
            $this->strategyRecomObjectivesRepository->setStrategyReportRecom($this->strategyReportRecomRepository->getStrategyReportRecom());
            $recommendation = $this->strategyReportRecomRepository->getStrategyReportRecom();

            if (array_key_exists('id',$validatedData)) {
                $this->strategyRecomObjectivesRepository->update($validatedData);
            } else {
                $validatedData['strategy_report_recommendation_id'] = $recommendation->id;
                $this->strategyRecomObjectivesRepository->create($validatedData);
            }
        }
        catch(Throwable $e){
            Log::warning($e);
            dd($e);
        }
    }
}
