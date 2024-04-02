<?php

namespace App\Services;

use App\Concerns\FormatsCurrency;
use App\Models\InvestmentRecommendation;
use App\Models\PensionRecommendation;
use App\Repositories\ClientRepository;
use App\Repositories\InvestmentRecommendationItemRepository;
use App\Repositories\InvestmentRecommendationRepository;
use App\Repositories\PensionRecommendationRepository;
use App\Repositories\PensionRepository;
use App\Repositories\PRNewContributionRepository;
use App\Repositories\PRAnnualAllowanceRepository;
use App\Repositories\PRItemsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class InvestmentRecommendationSectionDataService
{
    use FormatsCurrency;
    protected ClientRepository $clientRepository;
    protected InvestmentRecommendationRepository $investmentRecommendationRepository;
    protected InvestmentRecommendationItemRepository $investmentRecommendationItemRepository;
    protected PensionRecommendationRepository $pensionRecommendationRepository;
    protected PRNewContributionRepository $prNewContributionRepository;
    protected PRAnnualAllowanceRepository $prAnnualAllowanceRepository;
    protected PRItemsRepository $prItemsRepository;

    public function __construct(
        ClientRepository $clientRepository,
        InvestmentRecommendationRepository $investmentRecommendationRepository,
        InvestmentRecommendationItemRepository $investmentRecommendationItemRepository,
        PensionRecommendationRepository $pensionRecommendationRepository,
        PRNewContributionRepository $prNewContributionRepository,
        PRAnnualAllowanceRepository $prAnnualAllowanceRepository,
        PRItemsRepository $prItemsRepository
) {
        $this->clientRepository = $clientRepository;
        $this->investmentRecommendationRepository = $investmentRecommendationRepository;
        $this->investmentRecommendationItemRepository = $investmentRecommendationItemRepository;
        $this->pensionRecommendationRepository = $pensionRecommendationRepository;
        $this->prNewContributionRepository = $prNewContributionRepository;
        $this->prAnnualAllowanceRepository = $prAnnualAllowanceRepository;
        $this->prItemsRepository = $prItemsRepository;
    }

    //get the data for a single section of a investment recommendation from a single client
    public static function get($investmentRecommendation, $step, $section): array
    {
        return [
            'enums' => $investmentRecommendation->loadEnumsForStep($step, $section),
            'model' => $investmentRecommendation->presenter()->formatForStep($step, $section), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
            'submit_url' => '/api/client/' . $investmentRecommendation->primary_client->io_id . '/recommendations/' . $step . '/' . $section //here we hydrate the autosave URL
        ];
    }

    public function validate(int $step, int $section, Request $request)
    {
        $messages = config('navigation_structures.recommendations.' . $step . '.sections.' . $section . '.messages');
        $rules = config('navigation_structures.recommendations.' . $step . '.sections.' . $section . '.rules');

        return Validator::make($request->all(), $rules, $messages)->validate();
    }

    public function validated(int $step, int $section, Request $request)
    {
        $messages = config('navigation_structures.recommendations.' . $step . '.sections.' . $section . '.messages');
        $rules = config('navigation_structures.recommendations.' . $step . '.sections.' . $section . '.rules');

        return Validator::make($request->all(), $rules, $messages)->validated();
    }

    /**
     * @param $client
     * @param $section
     * @param $step
     * @param array $validatedData
     * @return true
     */
    public function store($model, int $step, int $section, array $validatedData): true
    {
        if ($model instanceof InvestmentRecommendation) {
            $this->investmentRecommendationRepository->setInvestmentRecommendation($model);
            $this->clientRepository->setClient($model->primary_client);
        } else if ($model instanceof PensionRecommendation) {
            $this->pensionRecommendationRepository->setPensionRecommendation($model);
            $this->clientRepository->setClient($model->primary_client);
        } else {
            $this->clientRepository->setClient($model);
        }

        $this->{"_" . $step . $section}($validatedData);
        return true;
    }


    //InvestmentRecommendation:// Need to write a function named _{section}{step} for every form
    /**
     * Section: 1
     * Step: 1
     * @param array $validatedData
     * @return void
     */
    private function _11(array $validatedData): void
    {
        //define any explicit mutators that are not handled
        if ($validatedData['previously_invested_amount'] && $validatedData['previously_invested_amount'] != null) {
            $validatedData['previously_invested_amount'] = $this->currencyStringToInt($validatedData['previously_invested_amount']);
        }

        if ($validatedData['fee_basis'] != 1) {
            $validatedData['fee_basis_discount'] = null;
        }

        if ($validatedData['fee_basis_discount'] && $validatedData['fee_basis_discount'] != null) {
            $validatedData['fee_basis_discount'] = $this->currencyStringToInt($validatedData['fee_basis_discount']);
        }

      $this->investmentRecommendationRepository->updateFromValidated($validatedData);
    }

    /**
     * Section: 1
     * Step: 2
     * @param array $validatedData
     * @return void
     */
    private function _12(array $validatedData): void
    {
        //define any explicit mutators that are not handled
        if (array_key_exists('isa_allowance_used', $validatedData) && $validatedData['isa_allowance_used'] != null) {
            $validatedData['isa_allowance_used'] = $this->currencyStringToInt($validatedData['isa_allowance_used']);
        }
        if (array_key_exists('cgt_allowance_used', $validatedData) && $validatedData['cgt_allowance_used'] != null) {
            $validatedData['cgt_allowance_used'] = $this->currencyStringToInt($validatedData['cgt_allowance_used']);
        }
        if (array_key_exists('net_income_required', $validatedData) && $validatedData['net_income_required'] != null) {
            $validatedData['net_income_required'] = $this->currencyStringToInt($validatedData['net_income_required']);
        }
        if (array_key_exists('regular_cash_required', $validatedData) && $validatedData['regular_cash_required'] != null) {
            $validatedData['regular_cash_required'] = $this->currencyStringToInt($validatedData['regular_cash_required']);
        }

        $this->investmentRecommendationRepository->createOrUpdateIncomeGrowthReport($validatedData);
    }

    /**
     * Section: 1
     * Step: 3
     * @param array $validatedData
     * @return void
     */
    private function _13(array $validatedData): void
    {
        //define any explicit mutators that are not handled
        if (array_key_exists('investment_bonds', $validatedData)) {
            $investmentBonds = collect($validatedData['investment_bonds'])->map(function ($investmentBond) {
                if (array_key_exists('initial_investment', $investmentBond) && $investmentBond['initial_investment'] != null) {
                    $investmentBond['initial_investment'] = $this->currencyStringToInt($investmentBond['initial_investment']);
                }
                if (array_key_exists('surrender_value', $investmentBond) && $investmentBond['surrender_value'] != null) {
                    $investmentBond['surrender_value'] = $this->currencyStringToInt($investmentBond['surrender_value']);
                }
                if (array_key_exists('withdrawals', $investmentBond) && $investmentBond['withdrawals'] != null) {
                    $investmentBond['withdrawals'] = $this->currencyStringToInt($investmentBond['withdrawals']);
                }
                if (array_key_exists('total_gain', $investmentBond) && $investmentBond['total_gain'] != null) {
                    $investmentBond['total_gain'] = $this->currencyStringToInt($investmentBond['total_gain']);
                }
                if (array_key_exists('top_slice', $investmentBond) && $investmentBond['top_slice'] != null) {
                    $investmentBond['top_slice'] = $this->currencyStringToInt($investmentBond['top_slice']);
                }
                return $investmentBond;
            });

            $validatedData['investment_bonds'] = $investmentBonds->toArray();
        }

        $this->investmentRecommendationRepository->createOrUpdateTaxConsequences($validatedData);
    }

    /**
     * Section: 1
     * Step: 4
     * @param array $validatedData
     * @return void
     */
    private function _14(array $validatedData): void
    {
        try {
            if (array_key_exists('investment_recommendation_items', $validatedData)) {
                $items = collect($validatedData['investment_recommendation_items'])->map(function ($item) {
                    if ($item['amount'] && $item['amount'] != null) {
                        $item['amount'] = $this->currencyStringToInt($item['amount']);
                    }

                    return $item;
                });

                $validatedData['investment_recommendation_items'] = $items->toArray();
            }

            $this->investmentRecommendationItemRepository->setClient($this->clientRepository->getClient());
            $this->investmentRecommendationItemRepository->createOrUpdateRecommendationItems($validatedData);
        } catch (Throwable $e) {
            Log::warning($e);
        }
    }

    /**
     * Section: 2
     * Step: 1
     * @param array $validatedData
     * @return void
     */
    private function _21(array $validatedData): void
    {
        //define any explicit mutators that are not handled
        if (array_key_exists('pension_recommendation', $validatedData)) {
            $item = $validatedData['pension_recommendation'];

            if ($item['previously_invested_amount'] && $item['previously_invested_amount'] != null) {
                $item['previously_invested_amount'] = $this->currencyStringToInt($item['previously_invested_amount']);
            }
            if ($item['fee_basis'] != 1) {
                $item['fee_basis_discount'] = null;
            }
            if ($item['fee_basis_discount'] && $item['fee_basis_discount'] != null) {
                $item['fee_basis_discount'] = $this->currencyStringToInt($item['fee_basis_discount']);
            }

            $this->pensionRecommendationRepository->updateFromValidated($item);
        }
    }

    /**
     * Section: 2
     * Step: 2
     * @param array $validatedData
     * @return void
     */
    private function _22(array $validatedData): void
    {
        //define any explicit mutators that are not handled
        if (array_key_exists('pension_recommendation', $validatedData)) {
            $item = $validatedData['pension_recommendation'];

            if ($item['active_pension_member'] == true) {
                $item['active_pension_member_reason_not'] = null;
            }
            if ($item['active_pension_review_for_transfer'] != 3) {
                $item['active_pension_review_transfer_reason'] = null;
            }

            $this->pensionRecommendationRepository->updateFromValidated($item);
        }
    }

    /**
     * Section: 2
     * Step: 3
     * @param array $validatedData
     * @return void
     */
    private function _23(array $validatedData): void
    {
        //define any explicit mutators that are not handled
        collect($validatedData['existing_pension_plans'])->each(function ($plan) {
            $pr = App::make(PensionRepository::class);

            if (array_key_exists('client_id', $plan) && $plan['client_id'] == null) {
                $client = $this->clientRepository->getClient();

                $plan['client_id'] = $client->id;
            }

            if ($plan['active_pension_member'] == true) {
                $plan['active_pension_member_reason_not'] = null;
            }

            if ($plan['policy_type'] == 0) {
                $pr->createOrUpdateDBPensions(collect([$plan]));
            } else {
                $pr->createOrUpdateDCPensions(collect([$plan]));
            }
        });
    }

    /**
     * Section: 2
     * Step: 4
     * @param array $validatedData
     * @return void
     */
    private function _24(array $validatedData): void
    {
        $contributions = collect($validatedData['prnew_contributions'])->map(function ($contribution) {
            if ($contribution['estimated_relevant_earnings'] && $contribution['estimated_relevant_earnings'] != null) {
                $contribution['estimated_relevant_earnings'] = $this->currencyStringToInt($contribution['estimated_relevant_earnings']);
            }
            if ($contribution['estimated_adjusted_income'] && $contribution['estimated_adjusted_income'] != null) {
                $contribution['estimated_adjusted_income'] = $this->currencyStringToInt($contribution['estimated_adjusted_income']);
            }
            if ($contribution['amount_gross'] && $contribution['amount_gross'] != null) {
                $contribution['amount_gross'] = $this->currencyStringToInt($contribution['amount_gross']);
            }

            return $contribution;
        });

        $this->prNewContributionRepository->setClient($this->clientRepository->getClient());
        $this->prNewContributionRepository->createOrUpdateContribution($contributions);
    }

    /**
     * Section: 2
     * Step: 5
     * @param array $validatedData
     * @return void
     */
    private function _25(array $validatedData): void
    {
        $allowances = collect($validatedData['pr_annual_allowances'])->map(function ($allowance) {
            if ($allowance['annual_allowance'] && $allowance['annual_allowance'] != null) {
                $allowance['annual_allowance'] = $this->currencyStringToInt($allowance['annual_allowance']);
            }
            if ($allowance['pension_input'] && $allowance['pension_input'] != null) {
                $allowance['pension_input'] = $this->currencyStringToInt($allowance['pension_input']);
            }
            if ($allowance['unused_allowance'] && $allowance['unused_allowance'] != null) {
                $allowance['unused_allowance'] = $this->currencyStringToInt($allowance['unused_allowance']);
            }

            return $allowance;
        });

        $this->prAnnualAllowanceRepository->setClient($this->clientRepository->getClient());
        $this->prAnnualAllowanceRepository->createOrUpdateAllowance($allowances);

        $prData = array();

        if (array_key_exists('dd_pcls_spend', $validatedData) && $validatedData['dd_pcls_spend'] != null) {
            $validatedData['dd_pcls_spend'] = $this->currencyStringToInt($validatedData['dd_pcls_spend']);
            $prData['dd_pcls_spend'] = $validatedData['dd_pcls_spend'];
        }
        if (array_key_exists('dd_pcls_income', $validatedData) && $validatedData['dd_pcls_income'] != null) {
            $validatedData['dd_pcls_income'] = $this->currencyStringToInt($validatedData['dd_pcls_income']);
            $prData['dd_pcls_income'] = $validatedData['dd_pcls_income'];
        }
        if (array_key_exists('dd_income', $validatedData) && $validatedData['dd_income'] != null) {
            $validatedData['dd_income'] = $this->currencyStringToInt($validatedData['dd_income']);
            $prData['dd_income'] = $validatedData['dd_income'];
        }

        $prData['id'] = $validatedData['pension_recommendation_id'];
        $this->pensionRecommendationRepository->updateFromValidated($prData);
    }

    /**
     * Section: 2
     * Step: 6
     * @param array $validatedData
     * @return void
     */
    private function _26(array $validatedData): void
    {
        $items = collect($validatedData['pr_items'])->map(function ($item) {
            if (array_key_exists('value', $item) && $item['value'] != null) {
                $item['value'] = $this->currencyStringToInt($item['value']);
            }
            if ($item['is_percentage'] == true) {
                $item['value'] = $this->currencyStringToInt($item['percentage']);
            }

            unset($item['percentage']);
            return $item;
        });

        $this->prItemsRepository->setClient($this->clientRepository->getClient());
        $this->prItemsRepository->createOrUpdateItem($items);
    }
}
