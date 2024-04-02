<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $nav1= [
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
        ];
        if($request->route()->hasParameter('client'))
        {
            $client = $request->client;

            $nav1 = array_merge($nav1,[
                [
                    'name' => 'Client Dashboard',
                    'href' => '/client/'.$client->io_id.'/dashboard',
                    'icon' => 'CDIcon',
                    'current' => $request->route()->getName() === 'client.dashboard'
                ],
                [
                    'name' => 'Fact-Find',
                    'href' => '/client/'.$client->io_id.'/fact-find',
                    'icon' => 'FFIcon',
                    'current' => $request->route()->getName() === 'client.factfind'
                ],
                [
                    'name' => 'Pension Objectives',
                    'href' => '/client/'.$client->io_id.'/pension-objectives',
                    'icon' => 'PIIcon',
                    'current' => $request->route()->getName() === 'client.pensionobjectives'
                ],
                [
                    'name' => 'Strategy Report',
                    'href' => '/client/'.$client->io_id.'/strategy-report',
                    'icon' => 'SRIcon',
                    'current' => $request->route()->getName() === 'client.strategy'
                ],
                [
                    'name' => 'Recommendations',
                    'href' => '/client/'.$client->io_id.'/recommendations',
                    'icon' => 'IRIcon',
                    'current' => $request->route()->getName() === 'client.recommendations'
                ],
                [
                    'name' => 'Strategy Report Options',
                    'href' => '/client/'.$client->io_id.'/strategy-report-recommendations',
                    'icon' => 'SRRIcon',
                    'current' => $request->route()->getName() === 'client.strategyreportrecommendations'
                ],
                [
                    'name' => 'Strategy Report',
                    'href' => '/client/'.$client->io_id.'/strategy-report',
                    'icon' => 'SRIcon',
                    'current' => $request->route()->getName() === 'client.strategy'
                ],
                [
                    'name' => 'Risk Assessment',
                    'href' => '/client/'.$client->io_id.'/risk-assessment',
                    'icon' => 'RPIcon',
                    'current' => $request->route()->getName() === 'client.riskassessment'
                ],
            ]);
        }

        if(Auth::check() && Auth::user()->profile_photo_path)
        {
            $pp = config('constants.cdn_url') . Auth::user()->profile_photo_path;
        }
        else{
            $pp =  '/images/logo_square.svg';
        }

        return array_merge(parent::share($request), [
            'logo' => config('constants.new_logo'),
            'navigation' => $nav1,
            'profile_picture' => $pp
            //Chore: make when there's a "client" in the URL that the other tabs appear
            //Client Dashboard

            //Fact Find
            //Strategy Report
            //]

        ]);
    }
}
