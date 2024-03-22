<?php
namespace App\Services;

use App\Concerns\FlipsEnums;
use App\Concerns\FormatsCurrency;
use App\Concerns\InterractsWithDataHub;
use App\Models\Asset;
use App\Models\EmploymentDetail;
use App\Models\OtherInvestment;
use App\Models\PensionScheme;
use Carbon\Carbon;
use Illuminate\Support\Str;

class StrategyReportDataService
{
    use InterractsWithDataHub, FormatsCurrency, FlipsEnums;
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
            if($client->strategy_report_recommendation->objective_type == $this->enumValueByName('strategy_report_recommendations.objective_type','Accumulating Wealth'))
            {
                $bld = 'To build capital for a better financial future';
            }
            elseif($ed1->employment_status === $this->enumValueByName('employment.employment_status','Retired'))
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
        if(!$client_two)
        {
            $dependents = $client->dependents->values();
        }
        else{
            $dependents =  collect($client->dependents->merge($client_two->dependents))->values();
        }
        $dc = count($dependents);
        $dependents_title = (string)$dc . ' ';
        $dependents_title .=   $dependents->filter(fn($i)=> in_array($i->pivot->relationship_type, [
                $this->enumValueByName('dependent.relationship_type','Daughter'),
                $this->enumValueByName('dependent.relationship_type','Son')
            ]))->count() === $dc ? 'child' . ($dc > 1 ? 'ren' : ''): 'dependent' . ($dc > 1 ? 's' : '');

        if($dc == 0)
        {
            $dependents_desc = null;
        }
        else{
            $ddd = $dependents->filter(fn($i) => $i->financial_dependent)->count();
            if($ddd === 1)
            {
                if($ddd === $dc)
                {
                    $dependents_desc = 'Who is';
                }
                else{
                    $dependents_desc = 'One of whom is';
                }
            }
            else{
                if($ddd === 0)
                {
                    $dependents_desc = 'None';
                }
                elseif($ddd == 2 && $ddd === $dc)
                {
                    $dependents_desc = 'Both';
                }
                elseif($ddd > 2 && $ddd === $dc)
                {
                    $dependents_desc = 'All';
                }
                else{
                    $dependents_desc = (string)$ddd;
                }
                $dependents_desc .= ' of whom are';
            }
            $dependents_desc .= ' financially dependant';
        }

        $home =  $client->assets()->where('type',$this->enumValueByName('assets.types','MainResidence'))->first();
        if($home)
        {
            $home_value_status = 'Home Value';
            $home_value = $this->currencyIntToString($home->current_value);
        }
        else
        {
            $home_value_status = 'Home Value';
            $home_value = $this->currencyIntToString(0);
        }

        if($client_two)
        {
            $employments = collect($client->employment_details()->get()->take(1)->merge($client_two->employment_detailss()->get()->take(1)));
            $c1e = $client->expenditures()->where(function ($q){
                return $q->where('starts_at',null)->orWhereDate('starts_at','<=',Carbon::now());
            })->get();
            $c2e = $client_two->expenditures()->where(function ($q){
                return $q->where('starts_at',null)->orWhereDate('starts_at','<=',Carbon::now());
            })->get();
            $expenditures = $c1e->merge($c2e);

            $assets_ids =  $client->assets->where('category',array_flip(config('enums.assets.categories'))['savings'])->pluck('id')->merge($client_two->assets->where('category',array_flip(config('enums.assets.categories'))['savings'])->pluck('id'));
            $investments = $client->other_investments->merge($client_two->other_investments);
            $db_pension =  PensionScheme::with('defined_benefit_pension')->whereHas('defined_benefit_pension')->whereIn('client_id',[$client->id,$client_two->id])->get();
            $dc_pension =  PensionScheme::with('defined_contribution_pension')->whereHas('defined_contribution_pension')->whereIn('client_id',[$client->id,$client_two->id])->get();
        }
        else{
            $assets_ids =  $client->assets->where('category',array_flip(config('enums.assets.categories'))['savings'])->pluck('id');

            $employments = collect($client->employment_details()->get()->take(1));
            $expenditures = $client->expenditures()->where(function ($q){
                return $q->where('starts_at',null)->orWhereDate('starts_at','<=',Carbon::now());
            })->get();
            $investments = $client->other_investments;
            $db_pension =  PensionScheme::with('defined_benefit_pension')->whereHas('defined_benefit_pension')->where('client_id',$client->id)->get();
            $dc_pension =  PensionScheme::with('defined_contribution_pension')->whereHas('defined_contribution_pension')->where('client_id',$client->id)->get();
        }
        $employments = $employments->map(fn($item)=> $item->presenter()->formatForStrategyReport())->values();
        $assets =  Asset::with('clients')->whereIn('id',$assets_ids)->get();

        if(count($db_pension) > 0)
        {
            if(count($dc_pension) > 0)
            {
                $pension_status = 'DB & DC Pensions';
                $pension_value =  $this->currencyIntToString($dc_pension->reduce(fn(?int $carry, $item) => $carry + $item->defined_contribution_pension->value) + $db_pension->reduce(fn(?int $carry, $item) => $carry + $item->defined_benefit_pension->cetv));
            }
            else{
                $pension_status = 'Defined Benefit Pensions';
                $pension_value = $this->currencyIntToString($db_pension->reduce(fn(?int $carry, $item) => $carry + $item->defined_benefit_pension->cetv));
            }
        }
        elseif(count($dc_pension) > 0)
        {
            $pension_status = 'Defined Contribution Pensions';
            $pension_value =  $this->currencyIntToString($dc_pension->reduce(fn(?int $carry, $item) => $carry + $item->defined_contribution_pension->value));
        }
        else{
            $pension_status = 'No Pensions';
            $pension_value = '£0.00';
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
                                $this->enumValueByName('strategy_report_recommendations.objective_type','Considering Retirement'),
                                $this->enumValueByName('strategy_report_recommendations.objective_type','Retiring')
                            ]) ? 'retiring' : 'individual')
                            : 'couple', //individual, retiring, couple,
            'employments' => $employments->toArray(),
            'bottom_left_status' => $client_two == null ? config('enums.strategy_report_recommendations.objective_type')[$client->strategy_report_recommendation->objective_type] : null,
            'bottom_left_description' => $client_two == null ? $bld : null,
            'marital_status' => config('enums.client.marital_status')[$client->marital_status],
            'personal_details' => $client_two == null ? [
                'icon' => config('strategy_report.icons.personal_details_one_client'),
                'clients' => [$client->presenter()->formatForPersonalDetails()]
            ] : [
                'icon' => config('strategy_report.icons.personal_details_two_clients'),
                'clients' => [
                    $client->presenter()->formatForPersonalDetails(),
                    $client_two->presenter()->formatForPersonalDetails()
                ]
            ],
            'dependent' => $dependents_title,
            'dependent_description' => $dependents_desc,
            'dependent_icon' => str_contains($dependents_desc,'child') ? config('enums.strategy_report_icons.dependents')['child'] : config('enums.strategy_report_icons.dependents')['adult'] ,
            'annual_expenditure' =>  $this->currencyIntToString($expenditures->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount)),
            'home_value_status' => $home_value_status,
            'home_value' => $home_value,
            'address' => $client->addresses->first()->formatForStrategyReport(),
            'liquid_assets' => $this->currencyIntToString($assets->sum('current_value') + $investments->sum('current_value')),
            'pension_status' => $pension_status,
            'pension_value' => $pension_value
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
