<?php
namespace App\Services;

use App\Concerns\InterractsWithDataHub;
use App\Models\EmploymentDetail;
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

        $bld = null;
        if(!$client_two && $client->strategy_report_recommendation->objective_type !== null)
        {
            $ed1 = EmploymentDetail::where('client_id', $client->id)->first();
            if($client->strategy_report_recommendation->objective_type == array_flip(config('enums.strategy_report_recommendations.objective_type'))['Accumulating Wealth'])
            {
                $bld = 'To build capital for a better financial future';
            }
            elseif($ed1->employment_status === array_flip(config('enums.employment.employment_status'))['Retired'])
            {
                $bld = 'In ' . Carbon::parse($ed1->start_at)->format('Y');
            }
            else{
                $ed = EmploymentDetail::where('client_id', $client->id)->where('intended_retirement_age','!=',null)->first();
                if($ed)
                {
                    $date = Carbon::parse($client->date_of_birth)->addYears($ed->intended_retirement_age)->format('Y');
                    $bld = 'At age ' . $ed->intended_retirement_age . ' (' . $date .')';
                }
                else{
                    $bld = '';
                }
            }
        }

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
            'type' =>  $client_two == null
                            ? (in_array($client->strategy_report_recommendation->objective_type,[
                                array_flip(config('enums.strategy_report_recommendations.objective_type'))['Considering Retirement'],
                                array_flip(config('enums.strategy_report_recommendations.objective_type'))['Retiring']
                            ]) ? 'retiring' : 'individual')
                            : 'couple', //individual, retiring, couple,
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
            'bottom_left_status' => $client_two == null ? config('enums.strategy_report_recommendations.objective_type')[$client->strategy_report_recommendation->objective_type] : null,
            'bottom_left_description' => $client_two == null ? $bld : null,
            'marital_status' => config('enums.client.marital_status')[$client->strategy_report_recommendation->marital_status],
            'personal_details' => $client_two == null ? [

            ] : [],
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
                'calls_to_action' => $client->strategy_report_recommendation->actions->sortBy('order')->map(function ($item){
                    return [
                        'title' => $item->formatted_cta
                    ];
                })->toArray()
            ],
            'next_steps' => [
                'next_meeting_title' => 'Advice presentation',
                'next_meeting_date' => $client->strategy_report_recommendation->next_meeting_date != null ? Carbon::parse($client->strategy_report_recommendation->next_meeting_date)->format('jS F Y \- h.iA') : 'Date to be confirmed'
            ]
        ];
    }
}
