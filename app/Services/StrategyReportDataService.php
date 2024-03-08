<?php
namespace App\Services;

class StrategyReportDataService
{
    public static function getStrategyReportData():array
    {
        return [
          'cover' => [
              'name' => '',
              'date' => ''
          ],
          'about_us' => [
              'group_assets' => '',
              'group_employees' => '',
              'corporate_clients' => '',
              'group_offices' => '',
              'office_map' => ''
          ],
          'meet_the_team' => [
              'adviser_picture' => '',
              'adviser_name' => '',
              'adviser_title' => '',
              'adviser_bio' => ''
          ],
          'a_holistic_approach' => [
              'report_version'
          ],
          'about_you' => [
//            'type' => '', //individual, retiring, couple,
//            'employments' => [
//                [
//                    'employment_status' => 'Retiring',
//                    'employer' => '',
//                    'job_title' => '',
//                    'pension_option_p_a' => '',
//                    'pension_option_l_s' => '',
//                ],
//                [
//                    'employment_status' => 'Employed',
//                    'employer' => '',
//                    'job_title' => '',
//                    'salary' => ''
//                ],
//                [
//                    'employment_status' => 'Retired',
//                    'retirement_income' => ''
//                ],
//            ],
//            'bottom_left_status' => '',
//            'bottom_left_description' => '',
//            'marital_status' => '',
//            'name' => '',
//            'age' => '',
//            'birth_date' => '',
//            'dependent' => '',
//            'dependent_description' => '',
//            'annual_expenditure' => '',
//            'home_value_status' => '',
//            'home_value' => '',
//            'address' => '',
//            'liquid_assets' => '',
//            'pension_status' => '',
//            'pension_value' => ''
          ],
           'your_objectives' => [
               'primary_objectives' => [
                    [
                        'icon' => '',
                        'title' => '',
                        'description' => ''
                    ],
                    [
                        'icon' => '',
                        'title' => '',
                        'description' => ''
                    ]
               ],
               'secondary_objectives' => [
                   [
                       'icon' => '',
                       'title' => '',
                       'description' => ''
                   ],
                   [
                       'icon' => '',
                       'title' => '',
                       'description' => ''
                   ]
               ],
           ],
            'your_finances' => [
                'total_assets' => '',
                'property_and_possessions' => [
                    'total' => '',
                    'breakdown' => [
                        [
                            'title' => '',
                            'value' => ''
                        ],
                        [
                            'title' => '',
                            'value' => ''
                        ],
                        [
                        'title' => '',
                        'value' => ''
                        ]
                    ]
                ],
                'liquid_assets' => [
                    'total' => '',
                    'taxable' => [
                        [
                            'title' => '',
                            'value' => ''
                        ],
                        [
                            'title' => '',
                            'value' => ''
                        ],
                        [
                            'title' => '',
                            'value' => ''
                        ]
                    ],
                    'tax_free' => [
                        [
                            'title' => '',
                            'value' => ''
                        ],
                        [
                            'title' => '',
                            'value' => ''
                        ],
                        [
                            'title' => '',
                            'value' => ''
                        ]
                    ]
                ],
                'pensions' => [
                    'total' => '',
                    'breakdown' => [
                        [
                            'title' => '',
                            'value' => ''
                        ],
                        [
                            'title' => '',
                            'value' => ''
                        ],
                        [
                            'title' => '',
                            'value' => ''
                        ]
                    ]
                ],
            ],
            'summary' => [
                'calls_to_action' => [
                    [
                        'title' => ''
                    ],
                    [
                       'title' => ''
                    ]
                ]
            ],
            'next_steps' => [
                'next_meeting_title' => '',
                'next_meeting_date' => ''
            ]
        ];
    }
}
