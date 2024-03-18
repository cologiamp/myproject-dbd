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

//Route::get('/recommendations',[InvestmentRecommendationController::class,'show'])->name('recommendations');
Breadcrumbs::for('/client/{client:io_id}/recommendations', function (BreadcrumbTrail $trail) {
    $trail->push('Investment Recommendation', route('recommendations'));
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

Breadcrumbs::for('/client/{client:io_id}/pension-objectives', function (BreadcrumbTrail $trail) {
    $trail->push('Pension Objectives', route('pensionobjectives'));
});


//CLIENT MANAGEMENT/SELECTION BREADCRUMBS
Breadcrumbs::for('client.dashboard', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name_with_c2']}", route('client.dashboard', $client));
});

//CLIENT MANAGEMENT/SELECTION BREADCRUMBS
Breadcrumbs::for('client.relationships', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name_with_c2']}", route('client.dashboard', $client));
    $trail->push("Relationships", route('client.relationships', $client));
});


// Dashboard > Clients > "Client Name" > Fact Find
Breadcrumbs::for('client.factfind', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name_with_c2']}", route('client.dashboard', $client));
    $trail->push('Fact Find');
});

// Dashboard > Clients > "Client Name" > Strategy
Breadcrumbs::for('client.strategy', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name_with_c2']}", route('client.dashboard', $client));
    $trail->push('Strategy Report');
});

// Dashboard > Clients > "Client Name" > Recommendations
Breadcrumbs::for('client.recommendations', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name']}", route('client.dashboard', $client));
    $trail->push('Recommendations');
});

// Dashboard > Clients > "Client Name" > Example
Breadcrumbs::for('client.example.edit', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name_with_c2']}", route('client.example.edit', $client));
    $trail->push('Example');
});

// Dashboard > Clients > "Client Name" > Pension Objectives
Breadcrumbs::for('client.pensionobjectives', function (BreadcrumbTrail $trail, Client $client): void {
    $trail->parent('clients');
    $trail->push("{$client['name_with_c2']}", route('client.dashboard', $client));
    $trail->push('Pension Objectives');
});
