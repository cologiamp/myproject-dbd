<?php

namespace App\Services;


use App\Concerns\FormatsCurrency;
use App\Models\Client;
use App\Models\InvestmentRecommendation;
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
    protected InvestmentRecommendationRepository $investmentRecommendationRepository;

    public function __construct(
        InvestmentRecommendationRepository $investmentRecommendationRepository
) {
        $this->investmentRecommendationRepository = $investmentRecommendationRepository;
    }
    //get the data for a single section of a factfind from a single client
    public static function get($investmentRecommendation, $step, $section): array
    {
        return [
            'enums' => $investmentRecommendation->loadEnumsForStep($step, $section),
            'model' => $investmentRecommendation->presenter()->formatForStep($step, $section), //here we load the data for that part of the form
            'submit_method' => 'put', //this is always put for now
            'submit_url' => '/api/client/' . $investmentRecommendation->io_id . '/investment-recommendation/' . $step . '/' . $section //here we hydrate the autosave URL
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


    //FactFind:// Need to write a function named _{section}{step} for every form
    /**
     * Section: 1
     * Step: 1
     * @param array $validatedData
     * @return void
     */
    private function _11(array $validatedData): void
    {
        //define any explicit mutators that are not handled
//        if (array_key_exists('date_of_birth', $validatedData)) {
//            $validatedData['date_of_birth'] = Carbon::parse($validatedData['date_of_birth']);
//        }
//        if (array_key_exists('country_of_domicile', $validatedData)  && $validatedData['country_of_domicile'] != null) {
//            $validatedData['country_of_domicile'] = array_flip(config('enums.client.iso_2_int'))[$validatedData['country_of_domicile']];
//        }
//        if (array_key_exists('country_of_residence', $validatedData) && $validatedData['country_of_residence'] != null) {
//            $validatedData['country_of_residence'] = array_flip(config('enums.client.iso_2_int'))[$validatedData['country_of_residence']];
//        }
//        //This example only has data from one table. This would be different if not the case. May need multiple repositories.
//        $this->cr->updateFromValidated($validatedData);
    }
}
