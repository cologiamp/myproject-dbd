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

// Home
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

// Home > Blog
Breadcrumbs::for('clients', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Clients', route('clients'));
});

// Home > Blog > [Category]
Breadcrumbs::for('category', function (BreadcrumbTrail $trail, $category) {
    $trail->parent('blog');
    $trail->push($category->title, route('category', $category));
});


//http://adviserhub.test/client/31643522/dashboard
/*
Breadcrumbs::for('client', function (BreadcrumbTrail $trail) {
    $trail->parent('clients');
    $trail->push('Client', route('client'));
});
*/

// Home > Blog > Post 123 > Edit
/*
Breadcrumbs::for("{$name}.edit", function (BreadcrumbTrail $trail, SomeModel $model) use ($name) {
    $trail->parent("{$name}.show", $model);
    $trail->push('Edit', route("{$name}.edit", $model));
});
*/

$name = '';
//http://adviserhub.test/client/31643522/dashboard

/*
 Breadcrumbs::for("/client/{client:io_id}/dashboard", function (BreadcrumbTrail $trail, Client $model) use ($name) {
    $trail->parent("clients", $model);
    $trail->push('Client', route("/client/{client:io_id}/dashboard", $model));
});
/*

Breadcrumbs::for("/client/{client:io_id}/dashboard", function (BreadcrumbTrail $trail, Client $model) use ($name) {
    $trail->parent("clients");
    $trail->push('Edit', route("/client/{client:io_id}/dashboard", $model));
});

/*
Route::name('client.')->prefix('/client/{client:io_id}/')->group(function (){
    Route::get('/dashboard',ClientDashboardController::class)->name('dashboard');
});
*/
