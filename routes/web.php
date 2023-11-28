<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use \App\Http\Controllers\DashboardController;
use \App\Http\Controllers\TwoFaController;

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
    Route::get('/2fa-setup', [TwoFaController::class,'show'])->name('2fa-setup');
    Route::post('/2fa-setup', [TwoFaController::class,'store'])->name('2fa-store');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    '2fa'
])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
});
