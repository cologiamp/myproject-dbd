<?php

namespace App\Http\Controllers;

use App\Repositories\BaseRepository;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Waw\Io\Io;

class DashboardController extends Controller
{
    protected BaseRepository $repository;
    public function __construct(BaseRepository $br)
    {
        $this->repository = $br;
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Dashboard',[
            'title' => 'Dashboard',
            'breadcrumbs' => $this->repository->loadBreadcrumbs()
        ]);
    }
}
