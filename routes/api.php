<?php

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
use App\Http\Controllers\Api\PensionObjectivesController;
use App\Http\Controllers\Api\InvestmentController;

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


Route::middleware('auth:sanctum')->group(function (){
    Route::delete('/expenditures/{expenditure}', [ExpenditureController::class,'delete']);
    Route::delete('/assets/{asset}',[AssetController::class,'delete']);
    Route::delete('/pensions/{pension}',[PensionController::class,'delete']);
    Route::delete('/liabilities/{liability}',[LiabilityController::class,'delete']);
    Route::delete('/lsc/{lsc}',[LumpSumCapitalController::class,'delete']);
    Route::delete('/share-save-schemes/{scheme}',[ShareSaveSchemeController::class,'delete']);
    Route::delete('/investments/{investment}',[InvestmentController::class,'delete']);


});


