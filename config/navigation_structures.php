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
                            'clients.poa_granted'
                        ],
                        'rules' => [
                            'first_name' => 'sometimes|max:127',
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
                            'ni_number' => 'sometimes|nullable|max:9',
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
                            'poa_granted' => 'sometimes|nullable|boolean'
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
                            'addresses' => 'sometimes|nullable|array',
                            'addresses.*.address_line_1' => 'sometimes|nullable|max:320',
                            'addresses.*.address_id' => 'sometimes|nullable',
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
                            'dependents' => 'sometimes|nullable|array',
                            'dependents.*.name' => 'sometimes|nullable|string',
                            'dependents.*.relationship_type' => [
                                'required',
                                'numeric',
                                'integer',
                                Rule::in(array_keys((config('enums.dependent.relationship_type'))))
                            ],
                            'dependents.*.born_at' => 'sometimes|nullable|date',
                            'dependents.*.financial_dependent' => 'sometimes|nullable|boolean',
                            'dependents.*.is_living_with_clients' => 'sometimes|nullable|boolean'
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
                            'employment_details.*.end_at' => 'sometimes|nullable|date'
                        ]
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
                            "incomes.belongs_to",
                            "incomes.record_exists",
                            "incomes.is_primary"
                        ],
                        'rules' => [
                            'incomes' => 'sometimes|nullable|array',
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
                            'incomes.*.belongs_to' => 'sometimes|nullable|integer',
                            'incomes.*.record_exists' => 'sometimes|nullable|boolean',
                            'incomes.*.is_primary' => 'sometimes|nullable|boolean'
                        ]
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
                            'expenditures.*.ends_at' => 'sometimes|nullable|date'
                        ]
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
                            'expenditures.*.ends_at' => 'sometimes|nullable|date'
                        ]
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
                            'expenditures.*.ends_at' => 'sometimes|nullable|date'
                        ]
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
                            'expenditures.*.ends_at' => 'sometimes|nullable|date'
                        ]
                    ]
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
                        ]
                    ],
                    'rules' => [
                        'saving_assets' => 'sometimes|array',
                        'saving_assets.*.id' => 'sometimes|nullable|integer',
                        'saving_assets.*.provider' => [
                            'sometimes',
                            'nullable',
                            'numeric',
                            'integer',
                            Rule::in(array_keys((config('enums.assets.providers'))))
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
                        'saving_assets.*.is_retained' => 'sometimes|nullable|boolean',
                        'saving_assets.*.start_date' => 'sometimes|nullable|date',
                        'saving_assets.*.end_date' => 'sometimes|nullable|date',
                        'saving_assets.*.interest_rate' => 'sometimes|nullable|numeric'
                    ]
                ],
                3 => [
                    'name' => 'Investments',
                    'rules' => [
                        'investments' => 'sometimes|array',
                        'investments.*.id' => 'sometimes|nullable|integer',
                        'investments.*.owner' => 'sometimes|nullable',
                        'investments.*.provider' => [
                            'sometimes',
                            'numeric',
                            'nullable',
                            'integer',
                            Rule::in(array_keys((config('enums.assets.investment_providers'))))
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
                    ]
                ],
                4=> [
                    'name' => 'Pensions',
                    'rules' => [
                        'db_pensions' => 'sometimes|array',
                            'db_pensions.*.id' => 'sometimes|nullable|integer',
                            'db_pensions.*.owner' => 'sometimes|nullable',
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
                            'db_pensions.*.cetv' => 'sometimes|nullable|string',
                            'db_pensions.*.cetv_ends_at' => 'sometimes|nullable|date',
                        'dc_pensions' => 'sometimes|array',
                            'dc_pensions.*.id' => 'sometimes|nullable|integer',
                            'dc_pensions.*.owner' => 'sometimes|nullable',
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
                                'numeric',
                                'nullable',
                                'integer',
                                Rule::in(array_keys((config('enums.assets.dc_pension_administrators'))))
                            ],
                            'dc_pensions.*.policy_starts_at' => 'sometimes|nullable|date',
                            'dc_pensions.*.policy_number' => 'sometimes|nullable|max:255',
                            'dc_pensions.*.gross_contribution_percent' => 'sometimes|nullable',
                            'dc_pensions.*.gross_contribution_absolute' => 'sometimes|nullable|string',
                            'dc_pensions.*.employer_contribution_percent' => 'sometimes|nullable',
                            'dc_pensions.*.employer_contribution_absolute' => 'sometimes|nullable|string',
                            'dc_pensions.*.valuation_at' => 'sometimes|nullable|date',
                            'dc_pensions.*.value' => 'sometimes|nullable|string',
                            'dc_pensions.*.retained_value' => 'sometimes|nullable|string',
                            'dc_pensions.*.is_retained' => 'sometimes|nullable|boolean'
                    ]
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
                    ]
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
                    ]
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
                        'liabilities.*.repay_details' => 'sometimes|nullable|max:1024'
                    ]
                ]
            ],
        ]
    ],
    'investmentrecommendation' => [
        //INVESTMENTRECOMMENDATION:// you need to make one of these for every step
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
                    ]
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
                    ]
                ],
//                3 => [
//                    'name' => 'Tax Consequences',
//                    'fields' => [
//
//                    ],
//                    'rules' => [
//
//                    ]
//                ],
//                4 => [
//                    'name' => 'Investment Recommendations',
//                    'fields' => [
//
//                    ],
//                    'rules' => [
//
//                    ]
//                ]
            ],
        ]
    ]
];
