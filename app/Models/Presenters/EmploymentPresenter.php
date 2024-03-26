<?php

namespace App\Models\Presenters;

use App\Concerns\FlipsEnums;
use App\Concerns\FormatsCurrency;
use App\Models\Asset;
use App\Models\Dependent;
use App\Models\Income;
use PHPUnit\Framework\Attributes\Depends;

class EmploymentPresenter extends BasePresenter
{
    use FlipsEnums, FormatsCurrency;
   public function formatForStrategyReport()
   {
       if(in_array($this->model->employment_status,[
           $this->enumValueByName('employment.employment_status','Self Employed'),
           $this->enumValueByName('employment.employment_status','Company Director'),
           $this->enumValueByName('employment.employment_status','Employed'),
       ]))
       {

           if($this->model->client->strategy_report_recommendation->retirement_status === $this->enumValueByName('strategy_report_recommendations.retirement_status','Retiring'))
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
                       'employer' => $this->model->employer,
                       'job_title' => $this->model->occupation,
                       'pension_option_p_a' => $ps->defined_benefit_pension->chosen === $this->enumValueByName('assets.chosen','Standard pension/standard tax free cash') ? $this->currencyIntToString( $ps->defined_benefit_pension->prospective_pension_standard) : $this->currencyIntToString( $ps->defined_benefit_pension->prospective_pension_max) ,
                       'pension_option_l_s' =>  $ps->defined_benefit_pension->chosen === $this->enumValueByName('assets.chosen','Standard pension/standard tax free cash') ?$this->currencyIntToString( $ps->defined_benefit_pension->prospective_pcls_standard) : $this->currencyIntToString( $ps->defined_benefit_pension->prospective_pcls_max)
                   ];
               }
               else{
                   //dc pension
                   return [
                       'employment_status' => 'Retiring',
                       'employer' => $this->model->employer,
                       'job_title' => $this->model->occupation,
                       'pension_option_p_a' => $this->currencyIntToString($this->model->client->incomes()->whereIn('category',[
                           $this->enumValueByName('incomes.income_type','Regular Pension Income'),
                           $this->enumValueByName('incomes.income_type','State Pension Income'),
                       ])->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount))
                   ];
               }


           }
           elseif($this->model->client->strategy_report_recommendation->retirement_status === $this->enumValueByName('strategy_report_recommendations.retirement_status','Flex Retirement'))
           {
               return [
                   'employment_status' => 'Flex Retirement',
                   'employer' => $this->model->employer,
                   'job_title' => $this->model->occupation,
                   'retirement_income' => $this->currencyIntToString($this->model->client->incomes()->whereIn('category',[
                       $this->enumValueByName('incomes.income_type','Regular Pension Income'),
                       $this->enumValueByName('incomes.income_type','State Pension Income'),
                   ])->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount))
               ];
           }
           else{
               return [
                   'employment_status' => 'Employed',
                   'employer' => $this->model->employer,
                   'job_title' => $this->model->occupation,
                   'salary' => $this->currencyIntToString($this->model->client->incomes()->whereIn('category', [
                       $this->enumValueByName('incomes.income_type','Salary'),
                       $this->enumValueByName('incomes.income_type','Self-Employment Annual Profit'),
                   ])->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount)),
                   'benefits' => $this->currencyIntToString($this->model->client->incomes()->whereIn('category',[
                       $this->enumValueByName('incomes.income_type','Regular Pension Income'),
                       $this->enumValueByName('incomes.income_type','State Pension Income'),
                   ])->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount))
               ];
           }
       }
       elseif($this->model->employment_status == $this->enumValueByName('employment.employment_status','Retired'))
       {
           return [
                   'employment_status' => 'Retired',
                   'retirement_income' => $this->currencyIntToString($this->model->client->incomes()->whereIn('category',[
                       $this->enumValueByName('incomes.income_type','Regular Pension Income'),
                       $this->enumValueByName('incomes.income_type','State Pension Income'),
                   ])->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount))
           ];
       }
       else{
           return [
               'employment_status' => config('enums.employment.employment_status')[$this->model->employment_status],
               'income' => $this->currencyIntToString($this->model->client->incomes()->whereIn('category',[
                   $this->enumValueByName('incomes.income_type','Regular Pension Income'),
                   $this->enumValueByName('incomes.income_type','State Pension Income'),
               ])->get()->reduce(fn(?int $carry, $item) => $carry + $item->gross_annual_amount))
           ];
       }


//],
//[

//],

   }

}
