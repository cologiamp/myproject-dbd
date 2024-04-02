<?php

namespace App\Models\Presenters;

use App\Concerns\FlipsEnums;
use App\Concerns\FormatsCurrency;
use App\Models\Asset;
use App\Models\Client;
use App\Models\Dependent;
use App\Models\Income;
use PHPUnit\Framework\Attributes\Depends;

class EmploymentPresenter extends BasePresenter
{
    use FlipsEnums, FormatsCurrency;
   public function formatForStrategyReport()
   {
       $srr = $this->model->client->strategy_report_recommendation;
       if($srr == null)
       {
           $client_one = Client::where('c2_id',$this->model->client->id)->first();
           if($client_one != null)
           {
               $srr = $client_one->strategy_report_recommendation;
           }
       }
       switch ($this->model->employment_status){
           case $this->enumValueByName('employment.employment_status','Self Employed'):
           case $this->enumValueByName('employment.employment_status','Employed'):
           case $this->enumValueByName('employment.employment_status','Company Director'):
               $empicon = config('constants.cdn_url') . config('strategy_report.icon_path') . config('strategy_report.dynamic_icons.employed');
               break;
           case $this->enumValueByName('employment.employment_status','Unemployed'):
               $empicon = config('constants.cdn_url') . config('strategy_report.icon_path') . config('strategy_report.dynamic_icons.unemployed');
               break;
           case $this->enumValueByName('employment.employment_status','Retired'):
               $empicon = config('constants.cdn_url') . config('strategy_report.icon_path') . config('strategy_report.dynamic_icons.retired');
               break;
           case $this->enumValueByName('employment.employment_status','Houseperson'):
               $empicon = config('constants.cdn_url') . config('strategy_report.icon_path') . config('strategy_report.dynamic_icons.house-person');
               break;
           case $this->enumValueByName('employment.employment_status','Student'):
               $empicon = config('constants.cdn_url') . config('strategy_report.icon_path') . config('strategy_report.dynamic_icons.student');
               break;
           case $this->enumValueByName('employment.employment_status','Maternity Leave'):
               $empicon = config('constants.cdn_url') . config('strategy_report.icon_path') . config('strategy_report.dynamic_icons.maternity-leave');
               break;
           case $this->enumValueByName('employment.employment_status','Long Term Illness'):
               $empicon = config('constants.cdn_url') . config('strategy_report.icon_path') . config('strategy_report.dynamic_icons.long-term-illness');
               break;
           case $this->enumValueByName('employment.employment_status','Contract Worker'):
               $empicon = config('constants.cdn_url') . config('strategy_report.icon_path') . config('strategy_report.dynamic_icons.contract-worker');
               break;
           case $this->enumValueByName('employment.employment_status','Carer Of a Child Under 16'):
           case $this->enumValueByName('employment.employment_status','Carer Of a Person Over 16'):
               $empicon = config('constants.cdn_url') . config('strategy_report.icon_path') . config('strategy_report.dynamic_icons.carer');
               break;
           default:
               $empicon = config('constants.cdn_url') . config('strategy_report.icon_path') . config('strategy_report.dynamic_icons.employed');
       }

       if(in_array($this->model->employment_status,[
           $this->enumValueByName('employment.employment_status','Self Employed'),
           $this->enumValueByName('employment.employment_status','Company Director'),
           $this->enumValueByName('employment.employment_status','Employed'),
       ]))
       {

           if($srr->retirement_status === $this->enumValueByName('strategy_report_recommendations.retirement_status','Retiring'))
           {
               //best fit pension scheme
               $ps = $this->model->client->pension_schemes->where('employer','LIKE','%'.$this->model->employer)->first();
               if($ps == null)
               {
                   $ps = $this->model->client->pension_schemes->first();//take the first one
               }
               if($ps->defined_benefit_pension)
               {
                   //case where they chose "other" is not accounted for
                   return [
                       'employment_status' => 'Retiring',
                       'icon' => $empicon,
                       'employer' => $this->model->employer,
                       'job_title' => $this->model->occupation,
                       'pension_option_p_a' => $ps->defined_benefit_pension->chosen === $this->enumValueByName('assets.chosen','Standard pension/standard tax free cash') ? $this->currencyIntToString( $ps->defined_benefit_pension->prospective_pension_standard,0) : $this->currencyIntToString( $ps->defined_benefit_pension->prospective_pension_max,0) ,
                       'pension_option_l_s' =>  $ps->defined_benefit_pension->chosen === $this->enumValueByName('assets.chosen','Standard pension/standard tax free cash') ?$this->currencyIntToString( $ps->defined_benefit_pension->prospective_pcls_standard,0) : $this->currencyIntToString( $ps->defined_benefit_pension->prospective_pcls_max,0)
                   ];
               }
               else{
                   //dc pension
                   return [
                       'employment_status' => 'Retiring',
                       'icon' => $empicon,
                       'employer' => $this->model->employer,
                       'job_title' => $this->model->occupation,
                       'pension_option_p_a' => $this->currencyIntToString($this->model->client->incomes()->whereIn('category',[
                           $this->enumValueByName('incomes.income_type','Regular Pension Income'),
                           $this->enumValueByName('incomes.income_type','State Pension Income'),
                       ])->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount),0)
                   ];
               }


           }
           elseif($srr->retirement_status === $this->enumValueByName('strategy_report_recommendations.retirement_status','Flex Retirement'))
           {
               return [
                   'employment_status' => 'Flex Retirement',
                   'icon' => $empicon,
                   'employer' => $this->model->employer,
                   'job_title' => $this->model->occupation,
                   'retirement_income' => $this->currencyIntToString($this->model->client->incomes()->whereIn('category',[
                       $this->enumValueByName('incomes.income_type','Regular Pension Income'),
                       $this->enumValueByName('incomes.income_type','State Pension Income'),
                   ])->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount),0)
               ];
           }
           else{
               return [
                   'employment_status' => 'Employed',
                   'icon' => $empicon,
                   'employer' => $this->model->employer,
                   'job_title' => $this->model->occupation,
                   'salary' => $this->currencyIntToString($this->model->client->incomes()->whereIn('category', [
                       $this->enumValueByName('incomes.income_type','Salary'),
                       $this->enumValueByName('incomes.income_type','Self-Employment Annual Profit'),
                   ])->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount),0),
                   'benefits' => $this->currencyIntToString($this->model->client->incomes()->whereIn('category',[
                       $this->enumValueByName('incomes.income_type','Regular Pension Income'),
                       $this->enumValueByName('incomes.income_type','State Pension Income'),
                   ])->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount),0)
               ];
           }
       }
       elseif($this->model->employment_status == $this->enumValueByName('employment.employment_status','Retired'))
       {
           return [
                   'employment_status' => 'Retired',
                    'icon' => $empicon,
                   'retirement_income' => $this->currencyIntToString($this->model->client->incomes()->whereIn('category',[
                       $this->enumValueByName('incomes.income_type','Regular Pension Income'),
                       $this->enumValueByName('incomes.income_type','State Pension Income'),
                   ])->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount),0)
           ];
       }
       else{
           return [
               'employment_status' => config('enums.employment.employment_status')[$this->model->employment_status],
               'icon' => $empicon,
               'income' => $this->currencyIntToString($this->model->client->incomes()->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount),0)
           ];
       }


   }

}
