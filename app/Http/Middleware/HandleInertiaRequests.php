<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'logo' => config('constants.logo'),
            'navigation' => [
                [
                    'name' => 'Dashboard',
                    'href' => '/dashboard',
                    'icon' => 'HomeIcon',
//                    'current' => Str::contains($request->route()->getName(),'dashboard')
                    'current' => $request->route()->getName() === 'dashboard'
                ],
                [
                    'name' => 'Clients',
                    'href' => '/clients',
                    'icon' => 'ClientIcon',
                    'current' => $request->route()->getName() === 'clients'
                ],
            ]
            //Chore: make when there's a "client" in the URL that the other tabs appear
            //Fact Find
            //Strategy Report

//]

        ]);
    }
}
