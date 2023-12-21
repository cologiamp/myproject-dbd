<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;
use Waw\Io\Io;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
//        $i = new Io();
//        dd($i->getClient(36045374));
//        dd($i->getClients(5));
        return Inertia::render('Dashboard',[
            'title' => 'Dashboard',
            'breadcrumbs' => [
                [
                    'title' => 'Hardcoded',
                    'link' => '#',
                    'is_active' => false
                ],
                [
                    'title' => 'As Required',
                    'link' => '#',
                    'is_active' => false
                ],
                [
                    'title' => 'In The',
                    'link' => '#',
                    'is_active' => false
                ], [
                    'title' => 'Designs',
                    'link' => '#',
                    'is_active' => true
                ],

            ]
        ]);
    }
}
