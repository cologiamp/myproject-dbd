<?php

namespace App\Services;


use App\Concerns\FormatsCurrency;
use App\Models\Client;
use App\Models\StrategyReportRecommendation;
use App\Repositories\ClientRepository;
use App\Repositories\StrategyReportRecommendationActionsRepository;
use App\Repositories\StrategyReportRecommendationObjectivesRepository;
use App\Repositories\StrategyReportRecommendationRepository;
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
    protected StrategyReportRecommendationRepository $strategyReportRecommendationRepository;
    protected StrategyReportRecommendationObjectivesRepository $strategyRecommendationObjectivesRepository;
    protected StrategyReportRecommendationActionsRepository $strategyRecommendationActionsRepository;
    public function __construct(
        ClientRepository                                 $clientRepository,
        StrategyReportRecommendationRepository           $strategyReportRecommendationRepository,
        StrategyReportRecommendationObjectivesRepository $strategyRecommendationObjectivesRepository,
        StrategyReportRecommendationActionsRepository    $strategyRecommendationActionsRepository)
    {
        $this->clientRepository = $clientRepository;
        $this->strategyReportRecommendationRepository = $strategyReportRecommendationRepository;
        $this->strategyRecommendationObjectivesRepository = $strategyRecommendationObjectivesRepository;
        $this->strategyRecommendationActionsRepository = $strategyRecommendationActionsRepository;
    }

    //get the data for a single section of a strategy report recommendations from a single client
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
        $this->strategyReportRecommendationRepository->setStrategyReportRecommendation(StrategyReportRecommendation::where('id', $client->strategy_report_recommendation_id)->first());
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
            $this->strategyReportRecommendationRepository->update($validatedData);
        }
        catch(Throwable $e){
            Log::warning($e);
            dd($e);
        }

    }

    private function saveTab2(array $validatedData):void
    {
        try {
            $this->strategyRecommendationObjectivesRepository->setStrategyReportRecommendation($this->strategyReportRecommendationRepository->getStrategyReportRecommendation());
            $recommendation = $this->strategyReportRecommendationRepository->getStrategyReportRecommendation();

            $singleObjectiveData = Arr::except($validatedData, ['objectives']);

            if (count($singleObjectiveData) > 0) {
                $singleObjectiveData['is_primary'] = $singleObjectiveData['objective_type'];
                unset($singleObjectiveData['objective_type']);

                if (array_key_exists('id',$singleObjectiveData) && $singleObjectiveData['id'] != null) {
                    $this->strategyRecommendationObjectivesRepository->setStrategyReportRecommendationObjective(
                        $this->strategyRecommendationObjectivesRepository->getObjectiveById($singleObjectiveData['id'])
                    );

                    $this->strategyRecommendationObjectivesRepository->update($singleObjectiveData);

                } else {
                    $singleObjectiveData['strategy_report_recommendation_id'] = $recommendation->id;

                    $orderId = $this->strategyRecommendationObjectivesRepository->getMaxOrderId($recommendation->id);
                    $singleObjectiveData['order'] = $orderId;

                    $this->strategyRecommendationObjectivesRepository->create($singleObjectiveData);
                }
                unset($validatedData['objectives']);
            } else {
                if (array_key_exists('objectives',$validatedData) && count($validatedData['objectives']) > 0) {
                    $this->strategyRecommendationObjectivesRepository->updateObjectivesOrder($validatedData['objectives']);
                }

            }
        } catch (Throwable $e) {
            Log::warning($e);
            dd($e);
        }
    }

    private function saveTab3(array $validatedData):void
    {
        try {
            $this->strategyRecommendationActionsRepository->setStrategyReportRecommendation($this->strategyReportRecommendationRepository->getStrategyReportRecommendation());
            $recommendation = $this->strategyReportRecommendationRepository->getStrategyReportRecommendation();

            $singleActionData = Arr::except($validatedData, ['actions']);

            if (count($singleActionData) > 0) {
                if (array_key_exists('id',$singleActionData) && $singleActionData['id'] != null) {
                    $this->strategyRecommendationActionsRepository->setStrategyReportRecommendationAction(
                        $this->strategyRecommendationActionsRepository->getActionById($singleActionData['id'])
                    );

                    $this->strategyRecommendationActionsRepository->update($singleActionData);

                } else {
                    $singleActionData['strategy_report_recommendation_id'] = $recommendation->id;

                    $orderId = $this->strategyRecommendationActionsRepository->getMaxOrderId($recommendation->id);
                    $singleActionData['order'] = $orderId;

                    $this->strategyRecommendationActionsRepository->create($singleActionData);
                }
                unset($validatedData['actions']);
            } else {
                if (array_key_exists('actions',$validatedData) && count($validatedData['actions']) > 0) {
                    $this->strategyRecommendationActionsRepository->updateActionsOrder($validatedData['actions']);
                }
            }
        } catch (Throwable $e) {
            Log::warning($e);
            dd($e);
        }
    }
}
