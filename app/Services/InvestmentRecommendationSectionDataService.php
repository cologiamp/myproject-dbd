<?php

namespace App\Services;


use App\Concerns\FormatsCurrency;
use App\Models\Client;
use App\Models\InvestmentRecommendation;
use App\Repositories\ClientRepository;
use App\Repositories\InvestmentRecommendationRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use Throwable;

class InvestmentRecommendationSectionDataService
{
    use FormatsCurrency;
    protected ClientRepository $clientRepository;
    protected InvestmentRecommendationRepository $investmentRecommendationRepository;

    public function __construct(
        ClientRepository $clientRepository,
        InvestmentRecommendationRepository $investmentRecommendationRepository
) {
        $this->clientRepository = $clientRepository;
        $this->investmentRecommendationRepository = $investmentRecommendationRepository;
    }
    //get the data for a single section of a investment recommendation from a single client
    public static function get($investmentRecommendation, $step, $section, $id): array
    {
        return [
            'enums' => $investmentRecommendation->loadEnumsForStep($step, $section),
            'model' => $investmentRecommendation->presenter()->formatForStep($step, $section), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
//            'submit_url' => '/api/client/' . $investmentRecommendation->primary_client->io_id . '/investment-recommendation/' . $step . '/' . $section
            'submit_url' => '/api/client/' . $id . '/investment-recommendation/' . $step . '/' . $section //here we hydrate the autosave URL
        ];
    }

    public function validate(int $step, int $section, Request $request)
    {
        return Validator::make($request->all(), config('navigation_structures.investmentrecommendation.' . $step . '.sections.' . $section . '.rules'))->validate();
    }

    public function validated(int $step, int $section, Request $request)
    {
        return Validator::make($request->all(), config('navigation_structures.investmentrecommendation.' . $step . '.sections.' . $section . '.rules'))->validated();
    }

    /**
     * @param $client
     * @param $section
     * @param $step
     * @param array $validatedData
     * @return true
     */
    public function store(InvestmentRecommendation $investmentRecommendation, int $step, int $section, array $validatedData): true
    {

        $this->investmentRecommendationRepository->setInvestmentRecommendation($investmentRecommendation);

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

        $this->investmentRecommendationRepository->createOrUpdateInvestmentRecommendation($validatedData);
    }
}
