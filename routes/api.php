<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\PensionController;
use App\Http\Controllers\Api\LiabilityController;

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

    Route::delete('/assets/{asset}',[AssetController::class,'delete']);
    Route::delete('/pensions/{pension}',[PensionController::class,'delete']);
    Route::delete('/liabilities/{liability}',[LiabilityController::class,'delete']);
});


