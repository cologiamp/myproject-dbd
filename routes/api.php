<?php

use App\Http\Controllers\Api\LumpSumCapitalController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\PensionController;
use App\Http\Controllers\Api\LiabilityController;
use App\Http\Controllers\Api\SyncClientController;
use App\Http\Controllers\Api\ExpenditureController;
use App\Http\Controllers\Api\ShareSaveSchemeController;
use App\Http\Controllers\Api\FactFindController;
use App\Http\Controllers\Api\InvestmentRecommendationController;

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

Route::middleware('auth:sanctum')->group(function (){
    Route::delete('/expenditures/{expenditure}', [ExpenditureController::class,'delete']);
    Route::delete('/assets/{asset}',[AssetController::class,'delete']);
    Route::delete('/pensions/{pension}',[PensionController::class,'delete']);
    Route::delete('/liabilities/{liability}',[LiabilityController::class,'delete']);
    Route::delete('/lsc/{lsc}',[LumpSumCapitalController::class,'delete']);
    Route::delete('/share-save-schemes/{scheme}',[ShareSaveSchemeController::class,'delete']);

    Route::put('/client/{client:io_id}/fact-find/{section}/{step}',[FactFindController::class,'update']);
    Route::put('/client/{client:io_id}/investment-recommendation/{section}/{step}',[InvestmentRecommendationController::class,'update']);
});


