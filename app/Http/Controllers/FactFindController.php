<?php

namespace App\Http\Controllers;

use App\Repositories\ClientRepository;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FactFindController extends Controller
{
    protected ClientRepository $clientRepository;
    public function __construct(ClientRepository $cr)
    {
        $this->clientRepository = $cr;
    }
    public function show($client, Request $request) //fact-find?step=1&section=6
    {
        $tabs = [
            [
                'name' => 'Basic Details',
                'href' => '#',
                'current' => true,
                'progress' => 30,
                'sidebaritems' => [
                    [
                        'name' => 'Personal Details',
                        'href' => '#',
                        'current' => true
                    ],
                    [
                        'name' => 'Health Details',
                        'href' => '#',
                        'current' => false
                    ],
                    [
                        'name' => 'Address and contact details',
                        'href' => '#',
                        'current' => false
                    ],
                    [
                        'name' => 'Family',
                        'href' => '#',
                        'current' => false
                    ],
                    [
                        'name' => 'Employment details',
                        'href' => '#',
                        'current' => false
                    ]
                ]
            ],
            [
                'name' => 'Income and Expenditure',
                'href' => '#',
                'current' => false,
                'progress' => 100,
                'sidebaritems' => [
                    [
                        'name' => 'X1',
                        'href' => '#',
                        'current' => true
                    ],
                    [
                        'name' => 'X2',
                        'href' => '#',
                        'current' => false
                    ]
                ]
            ],
            [
                'name' => 'Assets',
                'href' => '#',
                'current' => false,
                'progress' => 80,
                'sidebaritems' => [
                    [
                        'name' => 'Y1',
                        'href' => '#',
                        'current' => true
                    ],
                    [
                        'name' => 'Y2',
                        'href' => '#',
                        'current' => false
                    ],
                    [
                        'name' => 'Y3',
                        'href' => '#',
                        'current' => false
                    ]
                ]
            ],
            [
                'name' => 'Liabilities',
                'href' => '#',
                'current' => false,
                'progress' => 0,
                'sidebaritems' => [
                    [
                        'name' => 'Z1',
                        'href' => '#',
                        'current' => true
                    ],
                    [
                        'name' => 'Z2',
                        'href' => '#',
                        'current' => false
                    ]
                ]
            ],
            [
                'name' => 'Risk',
                'href' => '#',
                'current' => false,
                'progress' => 10,
                'sidebaritems' => [
                    [
                        'name' => 'W1',
                        'href' => '#',
                        'current' => true
                    ]
                ]
            ],
            [
                'name' => 'Objectives',
                'href' => '#',
                'current' => false,
                'progress' => 25
            ]
        ];

        return Inertia::render('FactFind',[
            'title' => 'Fact Find',
            'breadcrumbs' => $this->clientRepository->loadBreadcrumbs(),
            'step' =>  $request->step,
            'section' => $request->section,
            'tabs' => $tabs
        ]);
    }
}
