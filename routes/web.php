<?php

use App\Http\Controllers\Api\SyncClientController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\ClientRelationshipController;
use App\Http\Controllers\DataIntoIoController;
use App\Http\Controllers\FactFindController;
use App\Http\Controllers\InvestmentRecommendationController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\PensionObjectivesController;
use App\Http\Controllers\StrategyReportController;
use App\Http\Controllers\RiskAssessmentController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use \App\Http\Controllers\DashboardController;
use \App\Http\Controllers\TwoFaController;
use \App\Http\Controllers\SetPasswordController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('/logout',function (){
    \Illuminate\Support\Facades\Auth::logout();
    return redirect()->to('/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/set-password', [SetPasswordController::class,'show'])->name('set-password');
    Route::post('/set-password', [SetPasswordController::class, 'store'])->name('set-password-store');
    Route::get('/2fa-setup', [TwoFaController::class,'show'])->name('2fa-setup');
    Route::post('/2fa-setup', [TwoFaController::class,'store'])->name('2fa-store');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    '2fa',
    'check_temporary_password',
    'is_not_c2'
])->group(function () {

//    Route::get('/test',function (){
//       $dis = App::make(\App\Services\DataIngestService::class);
//       $dis->getClientsForAdviser(Auth::user()->io_id);
//    });

    Route::name('client.')->prefix('/client/{client:io_id}/')->group(function (){
       Route::get('/dashboard',ClientDashboardController::class)->name('dashboard');
       Route::get('/relationships/select',ClientRelationshipController::class)->name('relationships');
       Route::get('/strategy-report',StrategyReportController::class)->name('strategy');
       Route::get('/fact-find',[FactFindController::class,'show'])->name('factfind');
        Route::put('/fact-find/{section}/{step}',[FactFindController::class,'update'])->name('factfind.update');
        Route::get('/investment-recommendation',[InvestmentRecommendationController::class,'show'])->name('investmentrecommendation');

       Route::get('/pension-objectives',[PensionObjectivesController::class,'show'])->name('pensionobjectives');
        Route::get('/risk-assessment',[RiskAssessmentController::class,'show'])->name('riskassessment');

       Route::get('/example',[ExampleController::class,'edit'])->name('example.edit');
       Route::put('/example',[ExampleController::class,'update'])->name('example.update');

        //"API" style requests
        Route::post('/sync',SyncClientController::class)->name('sync');
        Route::post('/commit-to-io',DataIntoIoController::class)->name('commit-to-io');

    });

    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::get('/clients', ClientController::class)->name('clients');
});


