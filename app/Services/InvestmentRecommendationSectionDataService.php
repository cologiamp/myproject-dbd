<?php

namespace App\Services;

use App\Concerns\FormatsCurrency;
use App\Models\InvestmentRecommendation;
use App\Models\PensionRecommendation;
use App\Repositories\ClientRepository;
use App\Repositories\InvestmentRecommendationItemRepository;
use App\Repositories\InvestmentRecommendationRepository;
use App\Repositories\PensionRecommendationRepository;
use Illuminate\Http\Request;
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

    public function __construct(
        ClientRepository $clientRepository,
        InvestmentRecommendationRepository $investmentRecommendationRepository,
        InvestmentRecommendationItemRepository $investmentRecommendationItemRepository,
        PensionRecommendationRepository $pensionRecommendationRepository,
) {
        $this->clientRepository = $clientRepository;
        $this->investmentRecommendationRepository = $investmentRecommendationRepository;
        $this->investmentRecommendationItemRepository = $investmentRecommendationItemRepository;
        $this->pensionRecommendationRepository = $pensionRecommendationRepository;
    }

    //get the data for a single section of a investment recommendation from a single client
    public static function get($investmentRecommendation, $step, $section): array
    {
        return [
            'enums' => $investmentRecommendation->loadEnumsForStep($step, $section),
            'model' => $investmentRecommendation->presenter()->formatForStep($step, $section), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
            'submit_url' => '/api/client/' . $investmentRecommendation->primary_client->io_id . '/investment-recommendation/' . $step . '/' . $section //here we hydrate the autosave URL
        ];
    }

    public function validate(int $step, int $section, Request $request)
    {
        $messages = config('navigation_structures.investmentrecommendation.' . $step . '.sections.' . $section . '.messages');
        $rules = config('navigation_structures.investmentrecommendation.' . $step . '.sections.' . $section . '.rules');

        return Validator::make($request->all(), $rules, $messages)->validate();
    }

    public function validated(int $step, int $section, Request $request)
    {
        $messages = config('navigation_structures.investmentrecommendation.' . $step . '.sections.' . $section . '.messages');
        $rules = config('navigation_structures.investmentrecommendation.' . $step . '.sections.' . $section . '.rules');

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
            ray('InvestmentRecommendation')->green();
            $this->investmentRecommendationRepository->setInvestmentRecommendation($model);
        } else if ($model instanceof PensionRecommendation) {
            ray('PensionRecommendation')->green();
            $this->pensionRecommendationRepository->setPensionRecommendation($model);
        }

        $this->clientRepository->setClient($model->primary_client);

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
}
