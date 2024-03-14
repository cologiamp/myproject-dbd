<?php
namespace App\Services;

use App\Concerns\InterractsWithDataHub;
use Carbon\Carbon;
use Illuminate\Support\Str;

class StrategyReportDataService
{
    use InterractsWithDataHub;
    public function getStrategyReportData($client):array
    {
        $client_two = $client->client_two;
        $name = 'For ';
        if($client_two)
        {
            if(Str::lower($client_two->last_name) == Str::lower($client->last_name))
            {
                $name .= $client->preferred_name . ' & ' . $client_two->preferred_name . ' ' . $client->last_name;
            }
            else{
                $name.= $client->preferred_name . ' ' . $client->last_name . ' & ' . $client_two->preferred_name . ' ' . $client_two->last_name;
            }
        }
        else{
            $name .= $client->preferred_name . ' ' . $client->last_name;
        }
        $adviser = $this->getAdviser($client);

        return [
          'cover' => [
              'name' => $name,
              'date' => Carbon::now()->format('F Y')
          ],
          'about_us' => [
              'group_assets' => $this->getStat('group-assets'),
              'group_employees' => $this->getStat('waw-group-employees'),
              'corporate_clients' => $this->getStat('corporate-clients'),
              'group_offices' => $this->getStat('group-offices'),
              'group_offices_description' => $this->getStat('group-offices-desc'),
              'office_map' => $this->getStat('group-offices-image'),
          ],
          'meet_the_team' => [
              'adviser_picture' => $adviser['adviser_picture'],
              'adviser_name' => $adviser['adviser_name'],
              'adviser_title' => $adviser['adviser_title'],
              'adviser_bio' => $adviser['adviser_bio']
          ],
          'a_holistic_approach' => [
              'report_version' => config('enums.strategy_report_recommendations.report_version')[$client->strategy_report_recommendation->report_version]
          ],
          'about_you' => [
            'type' => '', //individual, retiring, couple,
            'employments' => [
                [
                    'employment_status' => 'Retiring',
                    'employer' => '',
                    'job_title' => '',
                    'pension_option_p_a' => '',
                    'pension_option_l_s' => '',
                ],
                [
                    'employment_status' => 'Employed',
                    'employer' => '',
                    'job_title' => '',
                    'salary' => ''
                ],
                [
                    'employment_status' => 'Retired',
                    'retirement_income' => ''
                ],
            ],
            'bottom_left_status' => '',
            'bottom_left_description' => '',
            'marital_status' => '',
            'name' => '',
            'age' => '',
            'birth_date' => '',
            'dependent' => '',
            'dependent_description' => '',
            'annual_expenditure' => '',
            'home_value_status' => '',
            'home_value' => '',
            'address' => '',
            'liquid_assets' => '',
            'pension_status' => '',
            'pension_value' => ''
          ],
           'your_objectives' => $client->strategy_report_recommendation->objectives->groupBy('is_primary')->mapWithKeys(function ($items){
               return [
                   $items->first()->is_primary ? 'primary_objectives' : 'secondary_objectives' => $items->map(function ($item){
                       return  [
                           'icon' => '',
                           'title' => '',
                           'description' => ''
                       ];
                   })


//
               ];
           }),
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
