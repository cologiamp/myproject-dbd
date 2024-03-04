<?php

namespace App\Services;


use App\Concerns\FormatsCurrency;
use App\Models\Client;
use App\Models\StrategyReportRecommendation;
use App\Repositories\ClientRepository;
use App\Repositories\StrategyRecomActionsRepository;
use App\Repositories\StrategyRecomObjectivesRepository;
use App\Repositories\StrategyReportRecomRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class StrategyReportRecommendationsDataService
{
    use FormatsCurrency;
    protected ClientRepository $clientRepository;
    protected StrategyReportRecomRepository $strategyReportRecomRepository;
    protected StrategyRecomObjectivesRepository $strategyRecomObjectivesRepository;
    protected StrategyRecomActionsRepository $strategyRecomActionsRepository;
    public function __construct(
        ClientRepository $clientRepository,
        StrategyReportRecomRepository $strategyReportRecomRepository,
        StrategyRecomObjectivesRepository $strategyRecomObjectivesRepository,
        StrategyRecomActionsRepository $strategyRecomActionsRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->strategyReportRecomRepository = $strategyReportRecomRepository;
        $this->strategyRecomObjectivesRepository = $strategyRecomObjectivesRepository;
        $this->strategyRecomActionsRepository = $strategyRecomActionsRepository;
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

    private function saveTab3(array $validatedData):void
    {
//        dd($validatedData);
        try {
            $this->strategyRecomActionsRepository->setStrategyReportRecom($this->strategyReportRecomRepository->getStrategyReportRecom());
            $recommendation = $this->strategyReportRecomRepository->getStrategyReportRecom();

            $singleActionData = Arr::except($validatedData, ['actions']);
            ray(count($singleActionData) > 0)->purple();
            ray($singleActionData)->purple();

            if (count($singleActionData) > 0) {
                if (array_key_exists('id',$singleActionData) && $singleActionData['id'] != null) {
                    ray('update action')->orange();
                    $this->strategyRecomActionsRepository->setStrategyReportRecomAction(
                        $this->strategyRecomActionsRepository->getActionById($singleActionData['id'])
                    );

                    $this->strategyRecomActionsRepository->update($singleActionData);

                } else {
                    $singleActionData['strategy_report_recommendation_id'] = $recommendation->id;

                    $orderId = $this->strategyRecomActionsRepository->getMaxOrderId($recommendation->id);
                    $singleActionData['order'] = $orderId;

                    ray('create new action')->green();
                    $this->strategyRecomActionsRepository->create($singleActionData);
                }
                unset($validatedData['actions']);
            } else {
                if (array_key_exists('actions',$validatedData) && count($validatedData['actions']) > 0) {
                    $this->strategyRecomActionsRepository->updateActionsOrder($validatedData['actions']);
                }
            }
        } catch (Throwable $e) {
            Log::warning($e);
            dd($e);
        }
    }
}
