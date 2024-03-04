<?php

namespace App\Services;


use App\Concerns\FormatsCurrency;
use App\Models\Client;
use App\Repositories\ClientRepository;
use App\Models\StrategyReportRecommendation;
use App\Repositories\StrategyReportRecomRepository;
use App\Repositories\StrategyRecomObjectivesRepository;
use App\Models\StrategyRecomObjectives;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Illuminate\Support\Arr;

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
        ray($validatedData)->purple();
        try {
            $this->strategyRecomObjectivesRepository->setStrategyReportRecom($this->strategyReportRecomRepository->getStrategyReportRecom());
            $recommendation = $this->strategyReportRecomRepository->getStrategyReportRecom();

            $singleObjectiveData = Arr::except($validatedData, ['objectives']);
            ray(count($singleObjectiveData) > 0)->purple();
            ray($singleObjectiveData)->purple();

            if (count($singleObjectiveData) > 0) {
                $singleObjectiveData['is_primary'] = $singleObjectiveData['objective_type'];
                unset($singleObjectiveData['objective_type']);

                ray($singleObjectiveData)->red();

                if (array_key_exists('id',$singleObjectiveData) && $singleObjectiveData['id'] != null) {
                    ray('update objective')->orange();
                    $this->strategyRecomObjectivesRepository->setStrategyReportRecomObjective(
                        $this->strategyRecomObjectivesRepository->getObjectiveById($singleObjectiveData['id'])
                    );

                    $this->strategyRecomObjectivesRepository->update($singleObjectiveData);

                } else {
                    $singleObjectiveData['strategy_report_recommendation_id'] = $recommendation->id;

                    $orderId = $this->strategyRecomObjectivesRepository->getMaxOrderId($recommendation->id);
                    $singleObjectiveData['order'] = $orderId;

                    ray('create new objective')->green();
                    $this->strategyRecomObjectivesRepository->create($singleObjectiveData);
                }
                unset($validatedData['objectives']);
            } else {
                if (array_key_exists('objectives',$validatedData) && count($validatedData['objectives']) > 0) {
                    $this->strategyRecomObjectivesRepository->updateObjectivesOrder($validatedData['objectives']);
                }

            }
        } catch (Throwable $e) {
            Log::warning($e);
            dd($e);
        }
    }
}
