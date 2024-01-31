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
                            "clients.last_name",
                            'clients.date_of_birth',
                            'clients.gender',
                            'clients.marital_status',
                            'clients.nationality',
                            'clients.ni_number',
                            'clients.country_of_domicile',
                            'clients.country_of_residence',
                            'clients.valid_will',
                            'clients.will_up_to_date',
                            'clients.poa_granted'
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
                            'gender' => [
                                'sometimes',
                                'numeric',
                                'integer',
                                Rule::in(array_keys(config('enums.client.gender'))),
                            ],
                            'date_of_birth' => 'sometimes|date',
                            'marital_status' => [
                                'sometimes',
                                'numeric',
                                'integer',
                                Rule::in(array_keys(config('enums.client.marital_status')))
                            ],
                            'nationality' => [
                                'sometimes',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.client.nationality'))))
                            ],
                            'ni_number' => 'sometimes|max:9',
                            'country_of_domicile' => [
                                'sometimes',
                            ],
                            'country_of_residence' => [
                                'sometimes',
                            ],
                            'valid_will' => 'sometimes|boolean',
                            'will_up_to_date' => 'sometimes|boolean',
                            'poa_granted' => 'sometimes|boolean'
                        ]
                    ],
                    2 => [
                        'name' => 'Health Details',
                        'fields' => [
                            "health.is_in_good_health",
                            "health.health_details",
                            "health.has_life_expectancy_concerns",
                            "health.life_expectancy_details",
                            "health.medical_conditions",
                            "health.smoker",
                            "health.smoked_in_last_12_months"
                        ],
                        'rules' => [
                            'is_in_good_health' => 'sometimes|nullable|boolean',
                            'health_details' => 'sometimes|nullable|max:1024',
                            'has_life_expectancy_concerns' => 'sometimes|nullable|boolean',
                            'life_expectancy_details' => 'sometimes|nullable|max:1024',
                            'medical_conditions' => 'sometimes|nullable|max:1024',
                            'smoker' => 'sometimes|nullable|integer',
                            'smoked_in_last_12_months' => 'sometimes|nullable|boolean'
                        ]
                    ],
                    3 => [
                        'name' => 'Address and Contact Details',
                        'fields' => [
                            'addresses' => [
                                'addresses.address_line_1',
                                'addresses.address_line_2',
                                'addresses.city',
                                'addresses.county',
                                'addresses.postcode',
                                'addresses.country',
                                'addresses.residency_status',
                                'addresses.date_from'
                            ],
                            "clients.phone_number",
                            "clients.email_address"
                        ],
                        'rules' => [
                            'addresses' => 'sometimes|array',
                            'addresses.*.address_line_1' => 'sometimes|max:320',
                            'addresses.*.address_id' => 'sometimes',
                            'addresses.*.address_line_2' => 'sometimes|nullable|max:320',
                            'addresses.*.city' => 'sometimes|nullable|max:320',
                            'addresses.*.county' => 'sometimes|nullable|max:320',
                            'addresses.*.postcode' => 'sometimes|nullable|max:320',
                            'addresses.*.country' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.address.country'))))
                            ],
                            'addresses.*.residency_status' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.address.residency_status'))))
                            ],
                            'addresses.*.date_from' => 'sometimes|nullable|date',
                            'phone_number' => 'sometimes|nullable|max:20',
                            'email_address' => 'sometimes|nullable|max:120'
                        ]
                    ],
                    4 => [
                        'name' => 'Family',
                        'fields' => [
                            'dependents' => [
                                'dependents.name',
                                'dependents.relationship_type',
                                'dependents.born_at',
                                'dependents.financial_dependent',
                                'dependents.is_living_with_clients'
                            ]
                        ],
                        'rules' => [
                            'dependents' => 'sometimes|array',
                            'dependents.*.name' => 'sometimes|string',
                            'dependents.*.relationship_type' => [
                                'required',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.dependent.relationship_type'))))
                            ],
                            'dependents.*.born_at' => 'sometimes|nullable|date',
                            'dependents.*.financial_dependent' => 'sometimes|boolean',
                            'dependents.*.is_living_with_clients' => 'sometimes|boolean'
                        ]
                    ],
                    5 => [
                        'name' => 'Employment Details',
                        'fields' => [
                            "employment_details.id",
                            "employment_details.employment_status",
                            "employment_details.intended_retirement_age",
                            "employment_details.occupation",
                            "employment_details.employer",
                            "employment_details.start_at",
                            "employment_details.end_at"
                        ],
                        'rules' => [
                            'employment_details' => 'sometimes|array',
                            'employment_details.*.id' => 'sometimes|nullable|integer',
                            'employment_details.*.employment_status' => [
                                'sometimes',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.employment.employment_status'))))
                            ],
                            'employment_details.*.intended_retirement_age' => 'sometimes|nullable|integer',
                            'employment_details.*.occupation' => 'sometimes|nullable|string',
                            'employment_details.*.employer' => 'sometimes|nullable|string',
                            'employment_details.*.start_at' => 'sometimes|nullable|date',
                            'employment_details.*.end_at' => 'sometimes|nullable|date'
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
                    'name' => 'Fixed Assets',
                    'fields' => [
                        'assets' => [
                            'assets.owner',
                            'assets.type',
                            'assets.description',
                            'assets.percent_ownership',
                            'assets.original_value',
                            'assets.start_at',
                            'assets.current_value',
                            'assets.is_retained',
                            'assets.retained_value',

                        ]
                    ],
                    'rules' => [
                        'fixed_assets' => 'sometimes|array',
                        'fixed_assets.*.id' => 'sometimes|nullable|integer',
                        'fixed_assets.*.asset_type' => [
                            'sometimes',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.assets.types'))))
                        ],
                        'fixed_assets.*.description' => 'sometimes|nullable|max:1024',
                        'fixed_assets.*.owner' => 'sometimes|nullable',
                        'fixed_assets.*.percent_ownership' => 'sometimes|nullable|array',
                        'fixed_assets.*.original_value' => 'sometimes|nullable|string',
                        'fixed_assets.*.current_value' => 'sometimes|nullable|string',
                        'fixed_assets.*.retained_value' => 'sometimes|nullable|string',
                        'fixed_assets.*.purchased_at' => 'sometimes|nullable|date',
                        'fixed_assets.*.is_retained' => 'sometimes|nullable|boolean'
                    ]
                ],
                2 => [
                    'name' => 'Savings',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
                3 => [
                    'name' => 'Investments',
                    'fields' => [
                        "clients.date_of_birth",
                        "clients.first_name",
                        "clients.last_name"
                    ]
                ],
                4=> [
                    'name' => 'Pensions',
                ]
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
        ]
    ]
];
