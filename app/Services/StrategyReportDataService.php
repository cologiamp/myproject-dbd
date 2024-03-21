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
                    'salary' => '75,000'
                ],
                [
                    'employment_status' => 'Retired',
                    'retirement_income' => ''
                ],
            ],
            'bottom_left_status' => '',
            'bottom_left_description' => '',
            'marital_status' => 'Married',
            'name' => 'John Winston Lenno',
            'age' => '55',
            'birth_date' => '8th Feb 1969',
            'dependent' => '3 children',
            'dependent_description' => '2 of which are financially dependant',
            'annual_expenditure' => '32,665',
            'home_value_status' => '',
            'home_value' => '750,126',
            'address' => '206 Flixton Road, Urmston',
            'liquid_assets' => '62,456',
            'pension_status' => '',
            'pension_value' => '76,000'
          ],
           'your_objectives' => $client->strategy_report_recommendation->objectives->sortBy('order')->groupBy('is_primary')->mapWithKeys(function ($items){
               return [
                   $items->first()->is_primary ? 'primary_objectives' : 'secondary_objectives' => $items->map(function ($item){
                       return  [
                           'icon' => $item->icon,
                           'type' => $item->formatted_type,
                           'title' => $item->formatted_objective,
                           'description' => $item->formatted_what_for,
                       ];
                   })->toArray()
               ];
           })->toArray(),
            'your_finances' => [
                'total_assets' => '944,901',
                'property_and_possessions' => [
                    'total' => '222,222',
                    'breakdown' => [
                        [
                            'title' => 'Main Residence (Equity Value)',
                            'value' => 'XXX,XXX'
                        ],
                        [
                            'title' => 'Other Property (Equity Value)',
                            'value' => 'XXX,XXX'
                        ],
                        [
                            'title' => 'Possessions',
                            'value' => 'XXX,XXX'
                        ]
                    ]
                ],
                'liquid_assets' => [
                    'total' => '339,901',
                    'taxable' => [
                        [
                            'title' => 'Cash/Savings Accounts',
                            'value' => 'XXX,XXX'
                        ],
                        [
                            'title' => 'Fixed Term Cash Deposits',
                            'value' => 'XXX,XXX'
                        ],
                        [
                            'title' => '',
                            'value' => ''
                        ]
                    ],
                    'tax_free' => [
                        [
                            'title' => 'Cash ISA',
                            'value' => 'XXX,XXX'
                        ],
                        [
                            'title' => 'Stocks & Shares ISA',
                            'value' => 'XXX,XXX'
                        ],
                        [
                            'title' => '',
                            'value' => ''
                        ]
                    ]
                ],
                'pensions' => [
                    'total' => 'XXX,XXx',
                    'breakdown' => [
                        [
                            'title' => 'Personal Pension Plan',
                            'value' => 'XXX,XXX'
                        ],
                        [
                            'title' => 'Occupational Defined Contribution',
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
                'calls_to_action' =>
                    $client->strategy_report_recommendation->actions->sortBy('order')->map(function ($item){
                    return [
                        'title' => $item->formatted_cta
                    ];
                })->toArray()
            ],
            'next_steps' => [
                'next_meeting_title' => 'Advice presentation',
                'next_meeting_date' => '25th April 2024 - 3.00pm',
            ]
        ];
    }
}
