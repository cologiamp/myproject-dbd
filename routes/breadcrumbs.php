<?php // routes/breadcrumbs.php
use App\Models\Client;
use Illuminate\Support\Facades\Route;

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use App\Http\Controllers\ClientDashboardController;
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

// Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});
//Route::get('/fact-find',[FactFindController::class,'show'])->name('factfind');
Breadcrumbs::for('/client/{client:io_id}/fact-find', function (BreadcrumbTrail $trail) {
    $trail->push('Fact Find', route('factfind'));
});

//Route::get('/strategy-report',StrategyReportController::class)->name('strategy');
Breadcrumbs::for('/client/{client:io_id}/strategy-report', function (BreadcrumbTrail $trail) {
    $trail->push('Strategy Report', route('strategy-report'));
});

//Route::get('/investment-recommendation',[InvestmentRecommendationController::class,'show'])->name('investmentrecommendation');
Breadcrumbs::for('/client/{client:io_id}/investment-recommendation', function (BreadcrumbTrail $trail) {
    $trail->push('Investment Recommendation', route('investmentrecommendation'));
});

//Route::get('/example',[ExampleController::class,'edit'])->name('example.edit');
Breadcrumbs::for('/client/{client:io_id}/example', function (BreadcrumbTrail $trail) {
    $trail->push('Example', route('client.example'));
});

// Dashboard > Clients
Breadcrumbs::for('clients', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Clients', route('clients'));
});


//CLIENT MANAGEMENT/SELECTION BREADCRUMBS
Breadcrumbs::for('client.dashboard', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name']}", route('client.dashboard', $client));
});

// Dashboard > Clients > "Client Name" > Fact Find
Breadcrumbs::for('client.factfind', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name']}", route('client.dashboard', $client));
    $trail->push('Fact Find');
});

// Dashboard > Clients > "Client Name" > Strategy
Breadcrumbs::for('client.strategy', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name']}", route('client.dashboard', $client));
    $trail->push('Strategy Report');
});

// Dashboard > Clients > "Client Name" > Investment Recommendation
Breadcrumbs::for('client.investmentrecommendation', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name']}", route('client.dashboard', $client));
    $trail->push('Investment Recommendation');
});

// Dashboard > Clients > "Client Name" > Example
Breadcrumbs::for('client.example.edit', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name']}", route('client.example.edit', $client));
    $trail->push('Example');
});


