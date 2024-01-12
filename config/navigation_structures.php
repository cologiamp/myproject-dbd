<?php

use Illuminate\Validation\Rule;

return [

    /*
    |--------------------------------------------------------------------------
    | Fact Find
    |--------------------------------------------------------------------------
    |
    | The navigation structures for "fact find"
    |
    */

    'factfind' => [
        //FACTFIND:// you need to make one of these for every step
        1 => [
            'name' => 'Basic Details',
            'sections' => [
                    1 => [
                        'name' => 'Personal Details',
                        'fields' => [
                            "clients.date_of_birth",
                            "clients.first_name",
                            "clients.last_name"
                        ],
                        'rules' => [
                            'first_name' => 'sometimes|max:127',
                            'last_name' => 'sometimes|max:127',
                            'title' => [
                                'sometimes',
                                'numeric',
                                'integer',
                                Rule::in(array_keys(config('enums.client.title'))),
                            ],
                        ]
                    ],
                    2 => [
                        'name' => 'Health Details',
                        'fields' => [
                            "clients.date_of_birth",
                            "clients.first_name",
                            "clients.last_name"
                        ]
                    ],
                    3 => [
                        'name' => 'Address and Contact Details',
                        'fields' => [
                            "clients.date_of_birth",
                            "clients.first_name",
                            "clients.last_name"
                        ]
                    ],
                    4 => [
                        'name' => 'Family',
                        'fields' => [
                            "clients.date_of_birth",
                            "clients.first_name",
                            "clients.last_name"
                        ]
                    ],
                    5 => [
                        'name' => 'Employment Details',
                        'fields' => [
                            "clients.date_of_birth",
                            "clients.first_name",
                            "clients.last_name"
                        ]
                    ],
                ],
            ],
        2 =>  [
                'name' => 'Income and Expenditure',
                'sections' => [
                    1 => [
                        'name' => 'Foo',
                        'fields' => [
                            "clients.date_of_birth",
                            "clients.first_name",
                            "clients.last_name"
                        ]
                    ],
                    2 => [
                        'name' => 'Bar',
                        'fields' => [
                            "clients.date_of_birth",
                            "clients.first_name",
                            "clients.last_name"
                        ]
                    ],
                    3 => [
                        'name' => 'Baz',
                        'fields' => [
                            "clients.date_of_birth",
                            "clients.first_name",
                            "clients.last_name"
                        ]
                    ],
                ],
            ],
        3 => [
            'name' => 'Assets',
            'sections' => [
                1 => [
                    'name' => 'Foo',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
                2 => [
                    'name' => 'Bar',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
                3 => [
                    'name' => 'Baz',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
            ],
        ],
        4 => [
            'name' => 'Liabilities',
            'sections' => [
                1 => [
                    'name' => 'Foo',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
                2 => [
                    'name' => 'Bar',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
                3 => [
                    'name' => 'Baz',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
            ],
        ],
        5 => [
            'name' => 'Risk',
            'sections' => [
                1 => [
                    'name' => 'Foo',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
                2 => [
                    'name' => 'Bar',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
                3 => [
                    'name' => 'Baz',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
            ],
        ],
        6 => [
            'name' => 'Objectives',
            'sections' => [
                1 => [
                    'name' => 'Foo',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
                2 => [
                    'name' => 'Bar',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
                3 => [
                    'name' => 'Baz',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
            ],
        ]
    ]
];
