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
                            "clients.middle_name",
                            "clients.last_name",
                            "clients.salutation",
                            'clients.date_of_birth',
                            'clients.gender',
                            'clients.marital_status',
                            'clients.nationality',
                            'clients.ni_number',
                            'clients.country_of_domicile',
                            'clients.country_of_residence',
                            'clients.valid_will',
                            'clients.will_up_to_date',
                            'clients.poa_granted',
                            'clients.poa_name'
                        ],
                        'rules' => [
                            'first_name' => 'sometimes|max:127',
                            'middle_name' => 'sometimes|nullable|max:127',
                            'last_name' => 'sometimes|max:127',
                            'salutation' => 'sometimes|max:127',
                            'title' => [
                                'sometimes',
                                'numeric',
                                'nullable',
                                'integer',
                                Rule::in(array_keys(config('enums.client.title'))),
                            ],
                            'gender' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys(config('enums.client.gender'))),
                            ],
                            'date_of_birth' => 'sometimes|nullable|date',
                            'marital_status' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys(config('enums.client.marital_status')))
                            ],
                            'nationality' => [
                                'sometimes',
                                'numeric',
                                'nullable',
                                'integer',
                                Rule::in(array_keys((config('enums.client.nationality'))))
                            ],
                            'ni_number' => [
                                'sometimes',
                                'nullable',
                                'regex:/^[A-CEGHJ-PR-TW-Z]{1}[A-CEGHJ-NPR-TW-Z]{1}[0-9]{6}[A-D]{1}$/i'
                            ],
                            'country_of_domicile' => [
                                'sometimes',
                                'nullable',
                            ],
                            'country_of_residence' => [
                                'sometimes',
                                'nullable',
                            ],
                            'valid_will' => 'sometimes|nullable|boolean',
                            'will_up_to_date' => 'sometimes|nullable|boolean',
                            'poa_granted' => 'sometimes|nullable|boolean',
                            'poa_name' => 'sometimes|nullable|max:127'
                        ],
                        'messages' => [
                            'ni_number.regex' => 'The entered National Insurance Number has an invalid format',
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
                        ],
                        'messages' => []
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
                            "clients.mobile_number",
                            "clients.email_address"
                        ],
                        'rules' => [
                            'addresses' => 'sometimes|nullable|array',
                            'addresses.*.address_line_1' => 'sometimes|nullable|max:320',
                            'addresses.*.address_id' => 'sometimes|nullable',
                            'addresses.*.address_line_2' => 'sometimes|nullable|max:320',
                            'addresses.*.city' => 'sometimes|nullable|max:320',
                            'addresses.*.county' => 'sometimes|nullable|max:320',
                            'addresses.*.postcode' => 'sometimes|nullable|max:320',
                            'addresses.*.country' => [
                                'sometimes',
                                'nullable'
                            ],
                            'addresses.*.residency_status' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.address.residency_status'))))
                            ],
                            'addresses.*.date_from' => 'sometimes|nullable|date',
                            'addresses.*.percent_ownership' => 'sometimes|nullable',
                            'addresses.*.owner' => 'sometimes|nullable',
                            'phone_number' => 'sometimes|nullable|max:20',
                            'mobile_number' => 'sometimes|nullable|max:20',
                            'email_address' => 'sometimes|nullable|max:120|unique:clients'
                        ],
                        'messages' => []
                    ],
                    4 => [
                        'name' => 'Family',
                        'fields' => [
                            'dependents' => [
                                'dependents.dependent_id',
                                'dependents.name',
                                'dependents.relationship_type',
                                'dependents.born_at',
                                'dependents.financially_dependent_until',
                                'dependents.financial_dependent',
                                'dependents.is_living_with_clients'
                            ]
                        ],
                        'rules' => [
                            'dependents' => 'sometimes|nullable|array',
                            'dependents.*.name' => 'sometimes|nullable|string',
                            'dependents.*.dependent_id' => 'sometimes|nullable',
                            'dependents.*.relationships' => 'sometimes|nullable|array',
                            'dependents.*.born_at' => 'sometimes|nullable|date',
                            'dependents.*.financially_dependent_until' => 'sometimes|nullable|date',
                            'dependents.*.financial_dependent' => 'sometimes|nullable|boolean',
                            'dependents.*.is_living_with_clients' => 'sometimes|nullable|boolean',
                            'dependents.*.related_to' => 'sometimes|nullable',
                        ],
                        'messages' => []
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
                            'employment_details' => 'sometimes|nullable|array',
                            'employment_details.*.id' => 'sometimes|nullable|integer',
                            'employment_details.*.employment_status' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.employment.employment_status'))))
                            ],
                            'employment_details.*.intended_retirement_age' => 'sometimes|nullable|integer',
                            'employment_details.*.occupation' => 'sometimes|nullable|string',
                            'employment_details.*.employer' => 'sometimes|nullable|string',
                            'employment_details.*.start_at' => 'sometimes|nullable|date',
                            'employment_details.*.end_at' => 'sometimes|nullable|date',
                            'employment_details.*.employee' => 'sometimes|nullable',
                        ],
                        'messages' => []
                    ],
                ],
            ],
        2 =>  [
                'name' => 'Income and Expenditure',
                'sections' => [
                    1 => [
                        'name' => 'Income',
                        'fields' => [
                            "incomes.income_id",
                            "incomes.income_type",
                            "incomes.gross_amount",
                            "incomes.net_amount",
                            "incomes.expenses",
                            "incomes.frequency",
                            "incomes.ends_at",
                            "incomes.starts_at",
                            "incomes.belongs_to",
                            "incomes.record_exists",
                            "incomes.is_primary"
                        ],
                        'rules' => [
                            'incomes' => 'sometimes|nullable|array',
                            'total' => 'sometimes|nullable',
                            'incomes.*.income_id' => 'sometimes|nullable|integer',
                            'incomes.*.income_type' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.incomes.income_type'))))
                            ],
                            'incomes.*.gross_amount' => 'sometimes|nullable|string',
                            'incomes.*.net_amount' => 'sometimes|nullable|string',
                            'incomes.*.expenses' => 'sometimes|nullable|string',
                            'incomes.*.frequency' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.incomes.frequency'))))
                            ],
                            'incomes.*.ends_at' => 'sometimes|nullable|date',
                            'incomes.*.starts_at' => 'sometimes|nullable|date',
                            'incomes.*.belongs_to' => 'sometimes|nullable',
                            'incomes.*.record_exists' => 'sometimes|nullable|boolean',

                            'incomes.*.is_primary' => 'sometimes|nullable|boolean',
                        ],
                        'messages' => []
                    ],
                    2 => [
                        'name' => 'Basic Essential Expenditure',
                        'fields' => [
                            'expenditures' => [
                                'expenditures.expenditure_id',
                                'expenditures.expenditure_type',
                                'expenditures.description',
                                'expenditures.amount',
                                'expenditures.frequency',
                                'expenditures.currently_active',
                                'expenditures.known_end_date',
                                'expenditures.starts_at',
                                'expenditures.ends_at'
                            ]
                        ],
                        'rules' => [
                            'expenditures' => 'sometimes|nullable|array',
                            'expenditures.*.expenditure_id' => 'sometimes|nullable|integer',
                            'expenditures.*.expenditure_type' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.expenditures.basic_essential_expenditure'))))
                            ],
                            'expenditures.*.description' => 'sometimes|nullable|string',
                            'expenditures.*.amount' => 'sometimes|nullable|string',
                            'expenditures.*.frequency' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.incomes.frequency'))))
                            ],
                            'expenditures.*.currently_active' => 'sometimes|nullable|boolean',
                            'expenditures.*.known_end_date' => 'sometimes|nullable|boolean',
                            'expenditures.*.starts_at' => 'sometimes|nullable|date',
                            'expenditures.*.ends_at' => 'sometimes|nullable|date',
                            'expenditures.*.belongs_to' => 'sometimes|nullable',
                        ],
                        'messages' => []
                    ],
                    3 => [
                        'name' => 'Basic Quality Of Living Expenditure',
                        'fields' => [
                            'expenditures' => [
                                'expenditures.expenditure_id',
                                'expenditures.expenditure_type',
                                'expenditures.description',
                                'expenditures.amount',
                                'expenditures.frequency',
                                'expenditures.currently_active',
                                'expenditures.known_end_date',
                                'expenditures.starts_at',
                                'expenditures.ends_at'
                            ]
                        ],
                        'rules' => [
                            'expenditures' => 'sometimes|nullable|array',
                            'expenditures.*.expenditure_id' => 'sometimes|nullable|integer',
                            'expenditures.*.expenditure_type' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.expenditures.basic_quality_of_living_expenditure'))))
                            ],
                            'expenditures.*.description' => 'sometimes|nullable|string',
                            'expenditures.*.amount' => 'sometimes|nullable|string',
                            'expenditures.*.frequency' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.incomes.frequency'))))
                            ],
                            'expenditures.*.currently_active' => 'sometimes|nullable|boolean',
                            'expenditures.*.known_end_date' => 'sometimes|nullable|boolean',
                            'expenditures.*.starts_at' => 'sometimes|nullable|date',
                            'expenditures.*.ends_at' => 'sometimes|nullable|date',
                            'expenditures.*.belongs_to' => 'sometimes|nullable',
                        ],
                        'messages' => []
                    ],
                    4 => [
                        'name' => 'Non Essential Outgoings Expenditure',
                        'fields' => [
                            'expenditures' => [
                                'expenditures.expenditure_id',
                                'expenditures.expenditure_type',
                                'expenditures.description',
                                'expenditures.amount',
                                'expenditures.frequency',
                                'expenditures.currently_active',
                                'expenditures.known_end_date',
                                'expenditures.starts_at',
                                'expenditures.ends_at'
                            ]
                        ],
                        'rules' => [
                            'expenditures' => 'sometimes|nullable|array',
                            'expenditures.*.expenditure_id' => 'sometimes|nullable|integer',
                            'expenditures.*.expenditure_type' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.expenditures.non_essential_outgoings_expenditure'))))
                            ],
                            'expenditures.*.description' => 'sometimes|nullable|string',
                            'expenditures.*.amount' => 'sometimes|nullable|string',
                            'expenditures.*.frequency' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.incomes.frequency'))))
                            ],
                            'expenditures.*.currently_active' => 'sometimes|nullable|boolean',
                            'expenditures.*.known_end_date' => 'sometimes|nullable|boolean',
                            'expenditures.*.starts_at' => 'sometimes|nullable|date',
                            'expenditures.*.ends_at' => 'sometimes|nullable|date',
                            'expenditures.*.belongs_to' => 'sometimes|nullable',
                        ],
                        'messages' => []
                    ],
                    5 => [
                        'name' => 'Liability Expenditure',
                        'fields' => [
                            'expenditures' => [
                                'expenditures.expenditure_id',
                                'expenditures.expenditure_type',
                                'expenditures.description',
                                'expenditures.amount',
                                'expenditures.frequency',
                                'expenditures.currently_active',
                                'expenditures.known_end_date',
                                'expenditures.starts_at',
                                'expenditures.ends_at'
                            ]
                        ],
                        'rules' => [
                            'expenditures' => 'sometimes|nullable|array',
                            'expenditures.*.expenditure_id' => 'sometimes|nullable|integer',
                            'expenditures.*.expenditure_type' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.expenditures.liability_expenditure'))))
                            ],
                            'expenditures.*.description' => 'sometimes|nullable|string',
                            'expenditures.*.amount' => 'sometimes|nullable|string',
                            'expenditures.*.frequency' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.incomes.frequency'))))
                            ],
                            'expenditures.*.currently_active' => 'sometimes|nullable|boolean',
                            'expenditures.*.known_end_date' => 'sometimes|nullable|boolean',
                            'expenditures.*.starts_at' => 'sometimes|nullable|date',
                            'expenditures.*.ends_at' => 'sometimes|nullable|date',
                            'expenditures.*.belongs_to' => 'sometimes|nullable',
                        ],
                        'messages' => []
                    ],
                    6 => [
                        'name' => 'Lump Sum Expenditure',
                        'fields' => [
                            'expenditures' => [
                                'expenditures.type',
                                'expenditures.amount',
                                'expenditures.frequency',
                                'expenditures.description',
                                'expenditures.starts_at'
                            ]
                        ],
                        'rules' => [
                            'expenditures' => 'sometimes|nullable|array',
                            'expenditures.*.expenditure_id' => 'sometimes|nullable|integer',
                            'expenditures.*.expenditure_type' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.expenditures.lump_sum_expenditure'))))
                            ],
                            'expenditures.*.description' => 'sometimes|nullable|string',
                            'expenditures.*.amount' => 'sometimes|nullable|string',
                            'expenditures.*.frequency' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.incomes.frequency'))))
                            ],
                            'expenditures.*.starts_at' => 'sometimes|nullable|date',
                            'expenditures.*.belongs_to' => 'sometimes|nullable',
                        ],
                        'messages' => []
                    ]
                ],
            ],
        3 => [
            'name' => 'Assets',
            'sections' => [
                1 => [
                    'name' => 'Property & Possessions',
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
                            'assets.equity'
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
                        'fixed_assets.*.is_retained' => 'sometimes|nullable|boolean',
                        'fixed_assets.*.equity' => 'sometimes|nullable|string'
                    ],
                    'messages' => []
                ],
                2 => [
                    'name' => 'Savings',
                    'fields' => [
                        'assets' => [
                            'assets.owner',
                            'assets.type',
                            'assets.provider',
                            'assets.account_type',
                            'assets.name',
                            'assets.current_balance',
                            'assets.start_date',
                            'assets.end_date',
                            'assets.interest_rate',
                            'assets.is_retained',
                            'assets.retained_value',
                            'assets.regular_contributions',
                            'assets.contribution_amount',
                        ]
                    ],
                    'rules' => [
                        'saving_assets' => 'sometimes|array',
                        'saving_assets.*.id' => 'sometimes|nullable|integer',
                        'saving_assets.*.provider' => [
                            'sometimes',
                            'nullable',
                        ],
                        'saving_assets.*.account_type' => [
                            'sometimes',
                            'numeric',
                            'nullable',
                            'integer',
                            Rule::in(array_keys((config('enums.assets.account_types'))))
                        ],
                        'saving_assets.*.name' => 'sometimes|nullable|max:1024',
                        'saving_assets.*.owner' => 'sometimes|nullable',
                        'saving_assets.*.current_balance' => 'sometimes|nullable|string',
                        'saving_assets.*.retained_value' => 'sometimes|nullable|string',
                        'saving_assets.*.contribution_amount' => 'sometimes|nullable|string',
                        'saving_assets.*.is_retained' => 'sometimes|nullable|boolean',
                        'saving_assets.*.regular_contributions' => 'sometimes|nullable|boolean',
                        'saving_assets.*.start_date' => 'sometimes|nullable|date',
                        'saving_assets.*.end_date' => 'sometimes|nullable|date',
                        'saving_assets.*.interest_rate' => 'sometimes|nullable|numeric',
                        'saving_assets.*.frequency' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.assets.frequency'))))
                        ],
                    ],
                    'messages' => []
                ],
                3 => [
                    'name' => 'Investments',
                    'rules' => [
                        'investments' => 'sometimes|array',
                        'investments.*.id' => 'sometimes|nullable|integer',
                        'investments.*.owner' => 'sometimes|nullable',
                        'investments.*.provider' => [
                            'sometimes',
                            'nullable',
                        ],
                        'investments.*.account_type' => [
                            'sometimes',
                            'numeric',
                            'nullable',
                            'integer',
                            Rule::in(array_keys((config('enums.assets.investment_account_types'))))
                        ],
                        'investments.*.product_name' => 'sometimes|nullable|max:1024',
                        'investments.*.is_retained' => 'sometimes|nullable|boolean',
                        'investments.*.retained_value' => 'sometimes|nullable|string',
                        'investments.*.current_value' => 'sometimes|nullable|string',
                        'investments.*.regular_contribution' => 'sometimes|nullable|string',
                        'investments.*.lump_sum_contribution' => 'sometimes|nullable|string',
                        'investments.*.frequency' => [
                            'sometimes',
                            'numeric',
                            'nullable',
                            'integer',
                            Rule::in(array_keys((config('enums.assets.frequency'))))
                        ],
                        'investments.*.valuation_at' => 'sometimes|nullable|date',
                        'investments.*.start_date' => 'sometimes|nullable|date',
                        'investments.*.maturity_date' => 'sometimes|nullable|date',
                    ],
                    'messages' => []
                ],
                4=> [
                    'name' => 'Pensions',
                    'rules' => [
                        'db_pensions' => 'sometimes|array',
                            'db_pensions.*.id' => 'sometimes|nullable|integer',
                            'db_pensions.*.owner' => 'sometimes|nullable',
                            'db_pensions.*.loa_submitted' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.pension_recommendation.loa_submitted'))))
                            ],
                            'db_pensions.*.status' => [
                                'sometimes',
                                'numeric',
                                'nullable',
                                'integer',
                                Rule::in(array_keys((config('enums.assets.db_pension_statuses'))))
                            ],
                            'db_pensions.*.employer' => 'sometimes|nullable|max:255',
                            'db_pensions.*.retirement_age' => 'sometimes|nullable|integer',
                            'db_pensions.*.prospective_pension_standard' => 'sometimes|nullable|string',
                            'db_pensions.*.prospective_pension_max' => 'sometimes|nullable|string',
                            'db_pensions.*.prospective_pcls_standard' => 'sometimes|nullable|string',
                            'db_pensions.*.prospective_pcls_max' => 'sometimes|nullable|string',
                            'db_pensions.*.chosen' => [
                                'sometimes',
                                'numeric',
                                'nullable',
                                'integer',
                                Rule::in(array_keys((config('enums.assets.chosen'))))
                            ],
                            'db_pensions.*.notes' => 'sometimes|nullable|string',
                            'db_pensions.*.cetv' => 'sometimes|nullable|string',
                            'db_pensions.*.cetv_ends_at' => 'sometimes|nullable|date',
                            'dc_pensions' => 'sometimes|array',
                            'dc_pensions.*.id' => 'sometimes|nullable|integer',
                            'dc_pensions.*.owner' => 'sometimes|nullable',
                            'dc_pensions.*.loa_submitted' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.pension_recommendation.loa_submitted'))))
                            ],
                            'dc_pensions.*.type' => [
                                'sometimes',
                                'numeric',
                                'nullable',
                                'integer',
                                Rule::in(array_keys((config('enums.assets.dc_pension_types'))))
                            ],
                            'dc_pensions.*.employer' => 'sometimes|nullable|max:255',
                            'dc_pensions.*.administrator' => [
                                'sometimes',
                                'nullable',
                            ],
                            'dc_pensions.*.policy_starts_at' => 'sometimes|nullable|date',
                            'dc_pensions.*.policy_number' => 'sometimes|nullable|max:255',
                            'dc_pensions.*.gross_contribution_percent' => 'sometimes|nullable',
                            'dc_pensions.*.gross_contribution_absolute' => 'sometimes|nullable|string',
                            'dc_pensions.*.employee_contribution_frequency' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.assets.frequency'))))
                            ],
                            'dc_pensions.*.employer_contribution_percent' => 'sometimes|nullable',
                            'dc_pensions.*.employer_contribution_absolute' => 'sometimes|nullable|string',
                            'dc_pensions.*.valuation_at' => 'sometimes|nullable|date',
                            'dc_pensions.*.value' => 'sometimes|nullable|string',
                            'dc_pensions.*.retained_value' => 'sometimes|nullable|string',
                            'dc_pensions.*.is_retained' => 'sometimes|nullable|boolean',
                            'dc_pensions.*.crystallised_status' => [
                                'sometimes',
                                'numeric',
                                'nullable',
                                'integer',
                                Rule::in(array_keys((config('enums.assets.pension_crystallised_statuses'))))
                            ],
                            'dc_pensions.*.crystallised_percentage' => 'sometimes|nullable',
                            'dc_pensions.*.funds.*.fund_type' => [
                                'sometimes',
                                'numeric',
                                'nullable',
                                'integer',
                                Rule::in(array_keys((config('enums.assets.pension_fund_types'))))
                            ],
                            'dc_pensions.*.funds.*.id' => 'sometimes|nullable',
                            'dc_pensions.*.funds.*.current_fund_value' => 'sometimes|nullable|string',
                            'dc_pensions.*.funds.*.fund_name' => 'sometimes|nullable|max:255',
                            'dc_pensions.*.funds.*.current_transfer_value' => 'sometimes|nullable|string',
                            'dc_pensions.*.employer_contribution_frequency' => [
                                'sometimes',
                                'nullable',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.assets.frequency'))))
                            ],
                    ],
                    'messages' => []
                ],
                5 => [
                    'name' => 'Share Save Schemes',
                    'rules' => [
                        'schemes' => 'sometimes|array',
                        'schemes.*.id' => 'sometimes|nullable',
                        'schemes.*.owner' => 'sometimes|nullable',
                        'schemes.*.name' => 'sometimes|nullable|max:255',
                        'schemes.*.option_price' => 'sometimes|nullable|string',
                        'schemes.*.monthly_saving' => 'sometimes|nullable|string',
                        'schemes.*.number_of_shares' => 'sometimes|nullable|integer',
                        'schemes.*.matures_at' => 'sometimes|nullable|date',
                        'schemes.*.start_at' => 'sometimes|nullable|date',
                    ],
                    'messages' => []
                ],
                6 => [
                    'name' => 'New Lump Sum Capital',
                    'rules' => [
                        'capitals' => 'sometimes|array',
                        'capitals.*.id' => 'sometimes|nullable',
                        'capitals.*.owner' => 'sometimes|nullable',
                        'capitals.*.description' => 'sometimes|nullable|max:255',
                        'capitals.*.amount' => 'sometimes|nullable|string',
                        'capitals.*.is_retained' =>'sometimes|nullable|boolean',
                        'capitals.*.retained_value' =>'sometimes|nullable|string',
                        'capitals.*.due_at' => 'sometimes|nullable|date',
                    ],
                    'messages' => []
                ]
            ],
        ],
        4 => [
            'name' => 'Liabilities',
            'sections' => [
                1 => [
                    'name' => 'Liabilities',
                    'fields' => [
                        "liabilities.id",
                        "liabilities.owner",
                        "liabilities.type",
                        "liabilities.repayment",
                        "liabilities.amount_outstanding",
                        "liabilities.monthly_repayment",
                        "liabilities.lender",
                        "liabilities.ends_at",
                        "liabilities.is_to_be_repaid",
                        "liabilities.repay_details",
                    ],
                    'rules' => [
                        'liabilities' => 'sometimes|array',
                        'liabilities.*.id' => 'sometimes|nullable|integer',
                        'liabilities.*.owner' => 'sometimes|nullable',
                        'liabilities.*.type' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.liabilities.types'))))
                        ],
                        'liabilities.*.repayment' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.liabilities.repayment_or_interest'))))
                        ],
                        'liabilities.*.amount_outstanding' => 'sometimes|nullable|string',
                        'liabilities.*.monthly_repayment' => 'sometimes|nullable|string',
                        'liabilities.*.lender' => 'sometimes|nullable|string',
                        'liabilities.*.ends_at' => 'sometimes|nullable|date',
                        'liabilities.*.is_to_be_repaid' => 'sometimes|nullable|boolean',
                        'liabilities.*.repay_details' => 'sometimes|nullable|max:1024',
                        'liabilities.*.interest_rate' => 'sometimes|nullable|numeric'
                    ],
                    'messages' => []
                ]
            ],
        ]
    ],
    'pensionobjectives' => [
        1 => [
            'name' => 'Pension Objectives',
            'rules' => [
                'intended_retirement' => 'sometimes|nullable|numeric',
                'intended_benefits_drawn' => 'sometimes|nullable|numeric',
                'income_option' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.pension_objectives.income_option'))),
                ],
                'notes' => 'sometimes|nullable|max:1024',
                'lifetime_allowance_protection' => 'sometimes|nullable|array',
            ]
        ],
        2 =>  [
            'name' => 'Accumulation',
            'rules' => [
                'additional_contributions' => 'sometimes|boolean',
                'in_specie_transfers' => 'sometimes|boolean',
                'if_experience_self_select' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.pension_objectives.if_experience_self_select'))),
                ],
                'if_experience_lifestyle' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.pension_objectives.if_experience_lifestyle'))),
                ],
                'if_experience_advisory' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.pension_objectives.if_experience_advisory'))),
                ],
                'if_experience_discretionary' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.pension_objectives.if_experience_discretionary'))),
                ],
                'is_explained' => 'sometimes|nullable|boolean',
                'preferred_option' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.pension_objectives.preferred_option'))),
                ],
                'preferred_explanation' => 'sometimes|nullable|max:1024',
                'wide_range_of_assets' => 'sometimes|nullable|boolean',
                'include_exclude_specifics' => 'sometimes|nullable|max:1024',
                'require_flexibility' => 'sometimes|nullable|boolean',
                'retirement_vs_legacy' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.pension_objectives.retirement_vs_legacy'))),
                ],
                'retirement_vs_legacy_specifics' => 'sometimes|nullable|max:1024',
                'dependents_suffer' => 'sometimes|nullable|max:128',
                'iht_concerns' => 'sometimes|nullable|boolean',
            ]
        ],
        3 =>  [
            'name' => 'Decumulation',
            'rules' => [
                'known_income_required' => 'sometimes|nullable|boolean',
                'prefer_flexibility' => 'sometimes|nullable|boolean',
                'what_age_annuity' => 'sometimes|nullable|numeric',
                'proportion_of_total_funds' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.pension_objectives.proportion_of_total_funds'))),
                ],
                'spouse_income_proportion' => 'sometimes|nullable|boolean',
                'spouse_lump_sum_death' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.pension_objectives.spouse_lump_sum_death'))),
                ],
                'maximise_lifetime' => 'sometimes|nullable|boolean',
                'no_spouse' => 'sometimes|nullable|boolean',
                'spouse_details' => 'sometimes|nullable|max:1024',
                'tax_free_lump_sum_preference' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.pension_objectives.tax_free_lump_sum_preference'))),
                ],
                'tax_free_lump_sum_value' => 'sometimes|nullable|string',
                'lump_sum_death_benefits' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.pension_objectives.lump_sum_death_benefits'))),
                ],
            ]
        ]
    ],
    'recommendations' => [
        1 => [
            'name' => 'Investment Recommendations',
            'sections' => [
                1 => [
                    'name' => 'Basic Details',
                    'fields' => [
                        'investment_recommendations.is_ethical_investor',
                        'investment_recommendations.risk_profile',
                        'investment_recommendations.previously_invested_amount',
                        'investment_recommendations.fee_basis',
                        'investment_recommendations.fee_basis_discount'
                    ],
                    'rules' => [
                        'is_ethical_investor' => 'sometimes|nullable|boolean',
                        'risk_profile' => 'sometimes|nullable',
                        'previously_invested_amount' => 'sometimes|nullable|string',
                        'fee_basis' => [
                            'sometimes',
                            'numeric',
                            'nullable',
                            'integer',
                            Rule::in(array_keys(config('enums.investment_recommendation.fee_basis'))),
                        ],
                        'fee_basis_discount' => 'sometimes|nullable|string'
                    ],
                    'messages' => []
                ],
                2 => [
                    'name' => 'Income and Growth Report',
                    'fields' => [
                        'investment_recommendations.id',
                        'investment_recommendations.report_for',
                        'investment_recommendations.report_type',
                        'investment_recommendations.isa_allowance_used',
                        'investment_recommendations.cgt_allowance_used',
                        'investment_recommendations.net_income_required',
                        'investment_recommendations.regular_cash_required',
                        'investment_recommendations.regular_cash_duration'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable',
                        'report_for' => 'sometimes|nullable',
                        'report_type' => [
                            'sometimes',
                            'numeric',
                            'nullable',
                            'integer',
                            Rule::in(array_keys(config('enums.investment_recommendation.report_type'))),
                        ],
                        'isa_allowance_used' => 'sometimes|nullable|string',
                        'cgt_allowance_used' => 'sometimes|nullable|string',
                        'net_income_required' => 'sometimes|nullable|string',
                        'regular_cash_required' => 'sometimes|nullable|string',
                        'regular_cash_duration' => [
                            'sometimes',
                            'numeric',
                            'nullable',
                            'integer',
                            Rule::in(array_keys(config('enums.investment_recommendation.frequency'))),
                        ],
                    ],
                    'messages' => []
                ],
                3 => [
                    'name' => 'Tax Consequences',
                    'fields' => [
                        'investment_recommendations.id',
                        'investment_recommendations.cta_base_costs_available',
                        'investment_recommendations.cta_sell_to_cgt_exemption',
                        'investment_recommendations.cta_sell_all',
                        'investment_recommendations.cta_sell_set_amount',
                        'investment_recommendations.dta_base_costs_available',
                        'investment_recommendations.dta_sell_to_cgt_exemption',
                        'investment_recommendations.dta_sell_all',
                        'investment_recommendations.dta_sell_set_amount',
                        'investment_recommendations.isa_transfer_exit_penalty_not_ascertained',
                        'investment_recommendations.isa_transfer_exit_penalty_ascertained',
                        'investment_recommendations.investment_bonds_managed_funds',
                        'investment_recommendations.investment_bonds_with_profits',
                        'investment_recommendations.investment_bonds_chargeable_gain_not_calculated',
                        'investment_recommendations.investment_bonds_exit_penalty_not_ascertained',
                        'investment_recommendations.investment_bonds_exit_penalty_ascertained',
                        'investment_bonds.id',
                        'investment_bonds.provider',
                        'investment_bonds.initial_investment',
                        'investment_bonds.surrender_value',
                        'investment_bonds.withdrawals',
                        'investment_bonds.total_gain',
                        'investment_bonds.top_slice',
                        'investment_bonds.complete_years_held'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable',
                        'cta_base_costs_available' => 'sometimes|nullable|string',
                        'cta_sell_to_cgt_exemption' => 'sometimes|nullable|boolean',
                        'cta_sell_all' => 'sometimes|nullable|boolean',
                        'cta_sell_set_amount' => 'sometimes|nullable|string',
                        'dta_base_costs_available' => 'sometimes|nullable|string',
                        'dta_sell_to_cgt_exemption' => 'sometimes|nullable|boolean',
                        'dta_sell_all' => 'sometimes|nullable|boolean',
                        'dta_sell_set_amount' => 'sometimes|nullable|string',
                        'isa_transfer_exit_penalty_not_ascertained' => 'sometimes|nullable|boolean',
                        'isa_transfer_exit_penalty_ascertained' => 'sometimes|nullable|string',
                        'investment_bonds_managed_funds' => 'sometimes|nullable|boolean',
                        'investment_bonds_with_profits' => 'sometimes|nullable|boolean',
                        'investment_bonds_chargeable_gain_not_calculated' => 'sometimes|nullable|boolean',
                        'investment_bonds_exit_penalty_not_ascertained' => 'sometimes|nullable|boolean',
                        'investment_bonds_exit_penalty_ascertained' => 'sometimes|nullable|string',
                        'investment_bonds' => 'sometimes|nullable|array',
                        'investment_bonds.*.id' => 'sometimes|nullable|integer',
                        'investment_bonds.*.provider' => 'sometimes|nullable|string',
                        'investment_bonds.*.initial_investment' => 'sometimes|nullable|string',
                        'investment_bonds.*.surrender_value' => 'sometimes|nullable|string',
                        'investment_bonds.*.withdrawals' => 'sometimes|nullable|string',
                        'investment_bonds.*.total_gain' => 'sometimes|nullable|string',
                        'investment_bonds.*.top_slice' => 'sometimes|nullable|string',
                        'investment_bonds.*.complete_years_held' => 'sometimes|nullable|int'
                    ],
                    'messages' => []
                ],
                4 => [
                    'name' => 'Investment Recommendations',
                    'fields' => [
                        'investment_recommendation_items' => [
                            'investment_recommendation_items.id',
                            'investment_recommendation_items.type',
                            'investment_recommendation_items.source_plan',
                            'investment_recommendation_items.description',
                            'investment_recommendation_items.stock_type',
                            'investment_recommendation_items.number_of_units',
                            'investment_recommendation_items.amount'
                        ]
                    ],
                    'rules' => [
                        'investment_recommendation_items' => 'sometimes|nullable|array',
                        'investment_recommendation_items.*.id' => 'sometimes|nullable|integer',
                        'investment_recommendation_items.*.type' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.investment_recommendation_items.types'))))
                        ],
                        'investment_recommendation_items.*.source_plan' => 'sometimes|nullable|string',
                        'investment_recommendation_items.*.description' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.investment_recommendation_items.descriptions'))))
                        ],
                        'investment_recommendation_items.*.stock_type' => 'sometimes|nullable|string',
                        'investment_recommendation_items.*.number_of_units' => 'sometimes|nullable|integer',
                        'investment_recommendation_items.*.amount' => 'sometimes|nullable|string'
                    ],
                    'messages' => []
                ]
            ]
        ],
        2 => [
            'name' => 'Pension Recommendations',
            'sections' => [
                1 => [
                    'name' => 'Pension Basic Details',
                    'fields' => [
                        'pension_recommendation.previously_invested_amount',
                        'pension_recommendation.fee_basis',
                        'pension_recommendation.fee_basis_discount',
                        'pension_recommendation.report_type'
                    ],
                    'rules' => [
                        'pension_recommendation' => 'sometimes|nullable',
                        'pension_recommendation.*.previously_invested_amount' => 'sometimes|nullable|integer',
                        'pension_recommendation.*.fee_basis' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.fee_basis')))) //change enums to be same with investment recommendation later
                        ],
                        'pension_recommendation.*.fee_basis_discount' => 'sometimes|nullable|integer',
                        'pension_recommendation.*.report_type' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.report_type'))))
                        ]
                    ],
                    'messages' => []
                ],
                2 => [
                    'name' => 'Client Details',
                    'fields' => [
                        'pension_recommendation.employment_status',
                        'pension_recommendation.current_employer_name',
                        'pension_recommendation.workplace_pension_type',
                        'pension_recommendation.employers_pension_name',
                        'pension_recommendation.active_pension_member',
                        'pension_recommendation.active_pension_member_reason_not',
                        'pension_recommendation.active_pension_review_for_transfer',
                        'pension_recommendation.active_pension_review_transfer_reason',
                        'pension_recommendation.pension_draw_age',
                        'pension_recommendation.retirement_option'
                    ],
                    'rules' => [
                        'pension_recommendation' => 'sometimes|nullable',
                        'pension_recommendation.*.employment_status' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.employment_status'))))
                        ],
                        'pension_recommendation.*.current_employer_name' => 'sometimes|nullable|string',
                        'pension_recommendation.*.workplace_pension_type' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.workplace_pension_type'))))
                        ],
                        'pension_recommendation.*.employers_pension_name' => 'sometimes|nullable|string',
                        'pension_recommendation.*.active_pension_member' => 'sometimes|nullable|boolean',
                        'pension_recommendation.*.active_pension_member_reason_not' => 'sometimes|nullable|string',
                        'pension_recommendation.*.active_pension_review_for_transfer' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.pension_review_transfer'))))
                        ],
                        'pension_recommendation.*.active_pension_review_transfer_reason' => 'sometimes|nullable|string',
                        'pension_recommendation.*.pension_draw_age' => 'sometimes|nullable|integer',
                        'pension_recommendation.*.retirement_option' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.retirement_option'))))
                        ]
                    ],
                    'messages' => []
                ],
                3 => [
                    'name' => 'Existing Pension Plans',
                    'fields' => [
                        'existing_pension_plans.client_id',
                        'existing_pension_plans.employer',
                        'existing_pension_plans.administrator',
                        'existing_pension_plans.policy_type',
                        'existing_pension_plans.policy_number',
                        'existing_pension_plans.loa_submitted',
                        'existing_pension_plans.is_retained',
                        'existing_pension_plans.active_pension_member',
                        'existing_pension_plans.active_pension_member_reason_not'
                    ],
                    'rules' => [
                        'existing_pension_plans' => 'sometimes|nullable',
                        'existing_pension_plans.*.client_id' => 'sometimes|nullable|integer',
                        'existing_pension_plans.*.employer' => 'sometimes|nullable|string',
                        'existing_pension_plans.*.administrator' => 'sometimes|nullable',
                        'existing_pension_plans.*.policy_type' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.policy_type'))))
                        ],
                        'existing_pension_plans.*.policy_number' => 'sometimes|nullable|string',
                        'existing_pension_plans.*.loa_submitted' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.loa_submitted'))))
                        ],
                        'existing_pension_plans.*.is_retained' => 'sometimes|nullable|boolean',
                        'existing_pension_plans.*.active_pension_member' => 'sometimes|nullable|boolean',
                        'existing_pension_plans.*.active_pension_member_reason_not' => 'sometimes|nullable|string'
                    ],
                    'messages' => []
                ],
                4 => [
                    'name' => 'New Contributions',
                    'fields' => [
                        'prnew_contributions.id',
                        'prnew_contributions.pension_recommendation_id',
                        'prnew_contributions.tax_year',
                        'prnew_contributions.estimated_relevant_earnings',
                        'prnew_contributions.estimated_adjusted_income',
                        'prnew_contributions.type',
                        'prnew_contributions.paid_by',
                        'prnew_contributions.amount_gross',
                        'prnew_contributions.frequency'
                    ],
                    'rules' => [
                        'prnew_contributions' => 'sometimes|nullable',
                        'prnew_contributions.*.id' => 'sometimes|nullable|integer',
                        'prnew_contributions.*.pension_recommendation_id' => 'sometimes|nullable|integer',
                        'prnew_contributions.*.tax_year' => 'sometimes|nullable|string',
                        'prnew_contributions.*.estimated_relevant_earnings' => 'sometimes|nullable|string',
                        'prnew_contributions.*.estimated_adjusted_income' => 'sometimes|nullable|string',
                        'prnew_contributions.*.type' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.new_contribution_type'))))
                        ],
                        'prnew_contributions.*.paid_by' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.new_contribution_paid_by'))))
                        ],
                        'prnew_contributions.*.amount_gross' => 'sometimes|nullable|string',
                        'prnew_contributions.*.frequency' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.frequency')))) //use existing frequency enum
                        ],
                    ],
                    'messages' => []
                ],
                5 => [
                    'name' => 'Annual Allowance and Draw Down',
                    'fields' => [
                        'pr_annual_allowances' => [
                            'pr_annual_allowances.tax_year',
                            'pr_annual_allowances.annual_allowance',
                            'pr_annual_allowances.pension_input',
                            'pr_annual_allowances.unused_allowance'
                        ],
                        'pension_recommendation.pension_recommendation_id',
                        'pension_recommendation.dd_pcls_spend',
                        'pension_recommendation.dd_pcls_income',
                        'pension_recommendation.dd_income'
                    ],
                    'rules' => [
                        'pr_annual_allowances' => 'sometimes|nullable',
                        'pr_annual_allowances.*.tax_year' => 'sometimes|nullable|string',
                        'pr_annual_allowances.*.annual_allowance' => 'sometimes|nullable|string',
                        'pr_annual_allowances.*.pension_input' => 'sometimes|nullable|string',
                        'pr_annual_allowances.*.unused_allowance' => 'sometimes|nullable|string',
                        'pension_recommendation_id' => 'sometimes|nullable|integer',
                        'dd_pcls_spend' => 'sometimes|nullable|string',
                        'dd_pcls_income' => 'sometimes|nullable|string',
                        'dd_income' => 'sometimes|nullable|string'
                    ],
                    'messages' => []
                ],
                6 => [
                    'name' => 'Pension Recommendations',
                    'fields' => [
                        'pr_items.type',
                        'pr_items.value',
                        'pr_items.percentage',
                        'pr_items.is_percentage'
                    ],
                    'rules' => [
                        'pr_items' => 'sometimes|nullable|array',
                        'pr_items.*.id' => 'sometimes|nullable|integer',
                        'pr_items.*.pension_recommendation_id' => 'sometimes|nullable|integer',
                        'pr_items.*.type' => [
                            'sometimes',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.pension_recommendation.item_type'))))
                        ],
                        'pr_items.*.value' => 'sometimes',
                        'pr_items.*.percentage' => 'sometimes|nullable',
                        'pr_items.*.is_percentage' => 'sometimes|boolean'
                    ],
                    'messages' => []
                ]
            ]
        ]
    ],
    'strategyreportrecommendations' => [
        1 => [
            'name' => 'Strategy Report Recommendations',
            'rules' => [
                'report_version' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.strategy_report_recommendations.report_version'))),
                ],
                'retirement_status' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.strategy_report_recommendations.retirement_status'))),
                ],
                'objective_type' => [
                    'sometimes',
                    'nullable',
                    'numeric',
                    'integer',
                    Rule::in(array_keys(config('enums.strategy_report_recommendations.objective_type'))),
                ],
                'next_meeting_date' => 'sometimes|nullable|date',
            ]
        ],
        2 => [
            'name' => 'Objectives',
            'rules' => [
                'id' => 'sometimes|nullable|numeric',
                'strategy_report_recommendation_id' => 'sometimes|nullable|numeric',
                'objective_type' => 'sometimes|nullable|numeric',
                'type' => 'sometimes|nullable|numeric',
                'objective' => 'sometimes|nullable|numeric',
                'objective_custom' => 'sometimes|nullable|string',
                'what_for' => 'sometimes|nullable|numeric',
                'what_for_custom' => 'sometimes|nullable|string',
                'objectives' => 'sometimes|nullable',
                'objectives.*.id' => 'sometimes|nullable|numeric',
                'objectives.*.strategy_report_recommendation_id' => 'sometimes|nullable|numeric',
                'objectives.*.is_primary' => 'sometimes|nullable|numeric',
                'objectives.*.type' => 'sometimes|nullable|numeric',
                'objectives.*.objective' => 'sometimes|nullable|numeric',
                'objectives.*.objective_custom' => 'sometimes|nullable|string',
                'objectives.*.what_for' => 'sometimes|nullable|numeric',
                'objectives.*.what_for_custom' => 'sometimes|nullable|string',
            ],
            'messages' => []
        ],
        3 => [
            'name' => 'Call to Actions',
            'rules' => [
                'id' => 'sometimes|nullable|numeric',
                'strategy_report_recommendation_id' => 'sometimes|nullable|numeric',
                'call_to_action' => 'sometimes|nullable|numeric',
                'call_to_action_custom' => 'sometimes|nullable|string',
                'actions' => 'sometimes|nullable',
                'actions.*.id' => 'sometimes|nullable|numeric',
                'actions.*.strategy_report_recommendation_id' => 'sometimes|nullable|numeric',
                'actions.*.call_to_action' => 'sometimes|nullable|numeric',
                'actions.*.call_to_action_custom' => 'sometimes|nullable|string'
            ]
        ]
    ],
    'riskassessment' => [
        1 => [
            'name' => 'Investment Risk Assessment',
            'sections' => [
                1 => [
                    'name' => 'Knowledge and Experience',
                    'fields' => [
                        'id',
                        'particular_issues',
                        'level_of_knowledge',
                        'aware_of_market_fluctuations',
                        'comfort_of_fluctuations',
                        'active_interest',
                        'discretionary_experience',
                        'ever_taken_invest_advice',
                        'experience_buying_cash',
                        'experience_buying_bonds',
                        'experience_buying_equities',
                        'experience_buying_insurance',
                        'experience_details'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable|integer',
                        'particular_issues' => 'sometimes|nullable|boolean',
                        'level_of_knowledge' => 'sometimes|nullable|integer',
                        'aware_of_market_fluctuations' => 'sometimes|nullable|boolean',
                        'comfort_of_fluctuations' => 'sometimes|nullable|boolean',
                        'active_interest' => 'sometimes|nullable|boolean',
                        'discretionary_experience' => 'sometimes|nullable|boolean',
                        'ever_taken_invest_advice' => 'sometimes|nullable|boolean',
                        'experience_buying_cash' => 'sometimes|nullable|array',
                        'experience_buying_bonds' => 'sometimes|nullable|array',
                        'experience_buying_equities' => 'sometimes|nullable|array',
                        'experience_buying_insurance' => 'sometimes|nullable|array',
                        'experience_details' => 'sometimes|nullable|string'
                    ],
                    'messages' => [
                    ]
                ],
                2 => [
                    'name' => 'Capacity for Loss',
                    'fields' => [
                        'id',
                        'investment_length',
                        'standard_of_living',
                        'emergency_funds',
                        'capacity_for_loss_investment'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable|integer',
                        'investment_length' => 'sometimes|nullable|integer',
                        'standard_of_living' => 'sometimes|nullable|integer',
                        'emergency_funds' => 'sometimes|nullable|integer',
                        'capacity_for_loss_investment' => 'sometimes|nullable|integer'
                    ],
                    'messages' => [
                    ]
                ],
                3 => [
                    'name' => 'Knowledge and Experience',
                    'fields' => [
                        'id',
                        'particular_issues',
                        'level_of_knowledge',
                        'aware_of_market_fluctuations',
                        'comfort_of_fluctuations',
                        'active_interest',
                        'discretionary_experience',
                        'ever_taken_invest_advice',
                        'experience_buying_cash',
                        'experience_buying_bonds',
                        'experience_buying_equities',
                        'experience_buying_insurance',
                        'experience_details'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable|integer',
                        'particular_issues' => 'sometimes|nullable|boolean',
                        'level_of_knowledge' => 'sometimes|nullable|integer',
                        'aware_of_market_fluctuations' => 'sometimes|nullable|boolean',
                        'comfort_of_fluctuations' => 'sometimes|nullable|boolean',
                        'active_interest' => 'sometimes|nullable|boolean',
                        'discretionary_experience' => 'sometimes|nullable|boolean',
                        'ever_taken_invest_advice' => 'sometimes|nullable|boolean',
                        'experience_buying_cash' => 'sometimes|nullable|array',
                        'experience_buying_bonds' => 'sometimes|nullable|array',
                        'experience_buying_equities' => 'sometimes|nullable|array',
                        'experience_buying_insurance' => 'sometimes|nullable|array',
                        'experience_details' => 'sometimes|nullable|string'
                    ],
                    'messages' => [
                    ]
                ],
                4 => [
                    'name' => 'Capacity for Loss',
                    'fields' => [
                        'id',
                        'investment_length',
                        'standard_of_living',
                        'emergency_funds',
                        'capacity_for_loss_investment'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable|integer',
                        'investment_length' => 'sometimes|nullable|integer',
                        'standard_of_living' => 'sometimes|nullable|integer',
                        'emergency_funds' => 'sometimes|nullable|integer',
                        'capacity_for_loss_investment' => 'sometimes|nullable|integer'
                    ],
                    'messages' => [
                    ]
                ],
            ]
        ],
        2 => [
            'name' => 'Pension Risk Assessment',
            'sections' => [
                1 => [
                    'name' => 'Knowledge and Experience',
                    'fields' => [
                        'id',
                        'particular_issues',
                        'level_of_knowledge',
                        'aware_of_market_fluctuations',
                        'comfort_of_fluctuations',
                        'active_interest',
                        'discretionary_experience',
                        'ever_taken_invest_advice',
                        'experience_buying_cash',
                        'experience_buying_bonds',
                        'experience_buying_equities',
                        'experience_buying_insurance',
                        'execution_only_experience',
                        'experience_details',
                        'experience_of_annuities',
                        'experience_of_income_drawdown',
                        'experience_of_phased_retirement',
                        'spoken_to_pensionwise'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable|integer',
                        'particular_issues' => 'sometimes|nullable|boolean',
                        'level_of_knowledge' => 'sometimes|nullable|integer',
                        'aware_of_market_fluctuations' => 'sometimes|nullable|integer',
                        'comfort_of_fluctuations' => 'sometimes|nullable|boolean',
                        'active_interest' => 'sometimes|nullable|boolean',
                        'discretionary_experience' => 'sometimes|nullable|boolean',
                        'ever_taken_invest_advice' => 'sometimes|nullable|boolean',
                        'experience_buying_cash' => 'sometimes|nullable|array',
                        'experience_buying_bonds' => 'sometimes|nullable|array',
                        'experience_buying_equities' => 'sometimes|nullable|array',
                        'experience_buying_insurance' => 'sometimes|nullable|array',
                        'execution_only_experience' => 'sometimes|nullable|boolean',
                        'experience_details' => 'sometimes|nullable|string',
                        'experience_of_annuities' => 'sometimes|nullable|integer',
                        'experience_of_income_drawdown' => 'sometimes|nullable|integer',
                        'experience_of_phased_retirement' => 'sometimes|nullable|integer',
                        'spoken_to_pensionwise' => 'sometimes|nullable|boolean'
                    ],
                    'messages' => [
                    ]
                ],
                2 => [
                    'name' => 'Capacity for Loss',
                    'fields' => [
                        'id',
                        'investment_length',
                        'standard_of_living',
                        'emergency_funds',
                        'capacity_for_loss_pension'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable|integer',
                        'investment_length' => 'sometimes|nullable|integer',
                        'standard_of_living' => 'sometimes|nullable|integer',
                        'emergency_funds' => 'sometimes|nullable|integer',
                        'capacity_for_loss_pension' => 'sometimes|nullable|integer'
                    ],
                    'messages' => [
                    ]
                ],
                3 => [
                    'name' => 'Knowledge and Experience',
                    'fields' => [
                        'id',
                        'particular_issues',
                        'level_of_knowledge',
                        'aware_of_market_fluctuations',
                        'comfort_of_fluctuations',
                        'active_interest',
                        'discretionary_experience',
                        'ever_taken_invest_advice',
                        'experience_buying_cash',
                        'experience_buying_bonds',
                        'experience_buying_equities',
                        'experience_buying_insurance',
                        'execution_only_experience',
                        'experience_details',
                        'experience_of_annuities',
                        'experience_of_income_drawdown',
                        'experience_of_phased_retirement',
                        'spoken_to_pensionwise'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable|integer',
                        'particular_issues' => 'sometimes|nullable|boolean',
                        'level_of_knowledge' => 'sometimes|nullable|integer',
                        'aware_of_market_fluctuations' => 'sometimes|nullable|integer',
                        'comfort_of_fluctuations' => 'sometimes|nullable|boolean',
                        'active_interest' => 'sometimes|nullable|boolean',
                        'discretionary_experience' => 'sometimes|nullable|boolean',
                        'ever_taken_invest_advice' => 'sometimes|nullable|boolean',
                        'experience_buying_cash' => 'sometimes|nullable|array',
                        'experience_buying_bonds' => 'sometimes|nullable|array',
                        'experience_buying_equities' => 'sometimes|nullable|array',
                        'experience_buying_insurance' => 'sometimes|nullable|array',
                        'execution_only_experience' => 'sometimes|nullable|boolean',
                        'experience_details' => 'sometimes|nullable|string',
                        'experience_of_annuities' => 'sometimes|nullable|integer',
                        'experience_of_income_drawdown' => 'sometimes|nullable|integer',
                        'experience_of_phased_retirement' => 'sometimes|nullable|integer',
                        'spoken_to_pensionwise' => 'sometimes|nullable|boolean'
                    ],
                    'messages' => [
                    ]
                ],
                4 => [
                    'name' => 'Capacity for Loss',
                    'fields' => [
                        'id',
                        'investment_length',
                        'standard_of_living',
                        'emergency_funds',
                        'capacity_for_loss_pension'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable|integer',
                        'investment_length' => 'sometimes|nullable|integer',
                        'standard_of_living' => 'sometimes|nullable|integer',
                        'emergency_funds' => 'sometimes|nullable|integer',
                        'capacity_for_loss_pension' => 'sometimes|nullable|integer'
                    ],
                    'messages' => [
                    ]
                ]
            ]
        ],
        3 => [
            'name' => 'Risk Profile',
            'sections' => [
                1 => [
                    'name' => 'Risk Profile',
                    'fields' => [
                        'id',
                        'comfort_fluctuate_market',
                        'day_to_day_volatility',
                        'short_term_volatility',
                        'medium_term_volatility',
                        'volatility_behaviour',
                        'long_term_volatility',
                        'time_in_market'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable|integer',
                        'comfort_fluctuate_market' => 'sometimes|nullable|boolean',
                        'day_to_day_volatility' => 'sometimes|nullable|integer',
                        'short_term_volatility' => 'sometimes|nullable|array',
                        'medium_term_volatility' => 'sometimes|nullable|integer',
                        'volatility_behaviour' => 'sometimes|nullable|integer',
                        'long_term_volatility' => 'sometimes|nullable|integer',
                        'time_in_market' => 'sometimes|nullable|integer'
                    ],
                    'messages' => [
                    ]
                ],
                2 => [
                    'name' => 'Risk Profile',
                    'fields' => [
                        'id',
                        'comfort_fluctuate_market',
                        'day_to_day_volatility',
                        'short_term_volatility',
                        'medium_term_volatility',
                        'volatility_behaviour',
                        'long_term_volatility',
                        'time_in_market'
                    ],
                    'rules' => [
                        'id' => 'sometimes|nullable|integer',
                        'comfort_fluctuate_market' => 'sometimes|nullable|boolean',
                        'day_to_day_volatility' => 'sometimes|nullable|integer',
                        'short_term_volatility' => 'sometimes|nullable|array',
                        'medium_term_volatility' => 'sometimes|nullable|integer',
                        'volatility_behaviour' => 'sometimes|nullable|integer',
                        'long_term_volatility' => 'sometimes|nullable|integer',
                        'time_in_market' => 'sometimes|nullable|integer'
                    ],
                    'messages' => [
                    ]
                ]
            ]
        ],
        4 => [
            'name' => 'Summary',
            'sections' => [
                1 => [
                    'name' => 'Summary',
                    'fields' => [
                        'using_calculated_risk_profile_investment',
                        'using_calculated_risk_profile_pension',
                        'adviser_recommendation_investment',
                        'adviser_recommendation_pension',
                        'why_investment',
                        'why_pension'
                    ],
                    'rules' => [
                        'using_calculated_risk_profile_investment' => 'sometimes|nullable|boolean',
                        'using_calculated_risk_profile_pension' => 'sometimes|nullable|boolean',
                        'adviser_recommendation_investment' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.risk_assessment.assessment_result_public'))))
                        ],
                        'adviser_recommendation_pension' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.risk_assessment.assessment_result_public'))))
                        ],
                        'why_investment' => 'sometimes|nullable|string',
                        'why_pension' => 'sometimes|nullable|string'
                    ],
                    'messages' => [
                    ]
                ],
                2 => [
                    'name' => 'Summary',
                    'fields' => [
                        'using_calculated_risk_profile_investment',
                        'using_calculated_risk_profile_pension',
                        'adviser_recommendation_investment',
                        'adviser_recommendation_pension',
                        'why_investment',
                        'why_pension'
                    ],
                    'rules' => [
                        'using_calculated_risk_profile_investment' => 'sometimes|nullable|boolean',
                        'using_calculated_risk_profile_pension' => 'sometimes|nullable|boolean',
                        'adviser_recommendation_investment' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.risk_assessment.assessment_result_public'))))
                        ],
                        'adviser_recommendation_pension' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.risk_assessment.assessment_result_public'))))
                        ],
                        'why_investment' => 'sometimes|nullable|string',
                        'why_pension' => 'sometimes|nullable|string'
                    ],
                    'messages' => [
                    ]
                ]
            ]
        ]
    ]
];
