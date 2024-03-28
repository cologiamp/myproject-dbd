<?php

use App\Http\Controllers\Api\IncomeController;
use App\Http\Controllers\Api\LumpSumCapitalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\SearchProviderController;
use App\Http\Controllers\Api\PensionController;
use App\Http\Controllers\Api\LiabilityController;
use App\Http\Controllers\Api\ExpenditureController;
use App\Http\Controllers\Api\ShareSaveSchemeController;
use App\Http\Controllers\Api\FactFindController;
use App\Http\Controllers\Api\InvestmentRecommendationController;
use App\Http\Controllers\Api\PensionObjectivesController;
use App\Http\Controllers\Api\InvestmentController;
use App\Http\Controllers\Api\StrategyReportRecommendationsController;
use App\Http\Controllers\APi\StrategyObjectivesController;
use App\Http\Controllers\Api\StrategyActionsController;
use App\Http\Controllers\Api\PensionFundController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\InvestmentRecommendationItemController;
use App\Http\Controllers\Api\PRContributionController;
use App\Http\Controllers\Api\PRAllowanceController;
use App\Http\Controllers\Api\PRItemsController;
use App\Http\Controllers\Api\RiskAssessmentController;
use App\Http\Controllers\RiskProfileQuestionnaireController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/providers/search',SearchProviderController::class);
Route::put('/client/{client:io_id}/fact-find/{section}/{step}',[FactFindController::class,'update']);
Route::post('/client/{client:io_id}/fact-find-solo',[FactFindController::class,'solo']);
Route::post('/client/{client:io_id}/fact-find-together/{c2id}',[FactFindController::class,'selectClientTwo']);
Route::put('/client/{client:io_id}/pension-objectives/{step}',[PensionObjectivesController::class,'update'])->name('pensionobjectives.update');

Route::put('/client/{client:io_id}/strategy-report-recommendations/{step}',[StrategyReportRecommendationsController::class,'update'])->name('strategyreportrecommendations.update');
Route::get('/strategy-objectives/{objective}',[StrategyObjectivesController::class,'get'])->name('strategyobjectives.get');
Route::get('/strategy-actions/{action}',[StrategyActionsController::class,'get'])->name('strategyactions.get');

Route::put('/client/{client:io_id}/investment-recommendation/{section}/{step}',[InvestmentRecommendationController::class,'update']);
Route::put('/client/{client:io_id}/risk-assessment/{section}/{step}',[RiskAssessmentController::class,'update']);

Route::prefix('risk-profile-questionnaire')->group(function () {
    Route::post('/q1/submit/{client:io_id}', [RiskProfileQuestionnaireController::class, 'submitQ1'])->name('risk.q1.submit');
    Route::post('/q2/submit/{client:io_id}', [RiskProfileQuestionnaireController::class, 'submitQ2'])->name('risk.q2.submit');
    Route::post('/q3/submit/{client:io_id}', [RiskProfileQuestionnaireController::class, 'submitQ3'])->name('risk.q3.submit');
    Route::post('/q4/submit/{client:io_id}', [RiskProfileQuestionnaireController::class, 'submitQ4'])->name('risk.q4.submit');
    Route::post('/q5/submit/{client:io_id}', [RiskProfileQuestionnaireController::class, 'submitQ5'])->name('risk.q5.submit');
    Route::post('/q6/submit/{client:io_id}', [RiskProfileQuestionnaireController::class, 'submitQ6'])->name('risk.q6.submit');
    Route::post('/q7/submit/{client:io_id}', [RiskProfileQuestionnaireController::class, 'submitQ7'])->name('risk.q7.submit');
});

Route::middleware('auth:sanctum')->group(function (){
    Route::delete('/expenditures/{expenditure}', [ExpenditureController::class,'delete']);
    Route::delete('/incomes/{income}', [IncomeController::class,'delete']);
    Route::delete('/assets/{asset}',[AssetController::class,'delete']);
    Route::delete('/addresses/{address}',[ClientController::class,'deleteAddress']);
    Route::delete('/pensions/{pension}',[PensionController::class,'delete']);
    Route::delete('/pension-funds/{pension_fund}',[PensionFundController::class,'delete']);
    Route::delete('/liabilities/{liability}',[LiabilityController::class,'delete']);
    Route::delete('/lsc/{lsc}',[LumpSumCapitalController::class,'delete']);
    Route::delete('/share-save-schemes/{scheme}',[ShareSaveSchemeController::class,'delete']);
    Route::delete('/investments/{investment}',[InvestmentController::class,'delete']);
    Route::delete('/strategy-objectives/{objective}',[StrategyObjectivesController::class,'delete']);
    Route::delete('/strategy-actions/{action}',[StrategyActionsController::class,'delete']);
    Route::delete('/investment-recommendation-items/{item}', [InvestmentRecommendationItemController::class,'delete']);
    Route::delete('/pr-contributions/{contribution}', [PRContributionController::class,'delete']);
    Route::delete('/pr-allowances/{allowance}', [PRAllowanceController::class,'delete']);
    Route::delete('/pr-items/{item}', [PRItemsController::class,'delete']);
    Route::put('/risk-outcome/{outcome}/assess-outcome',[RiskAssessmentController::class,'assessOutcome']);
});


