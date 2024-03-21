<?php

namespace App\Models\Presenters;

use App\Concerns\FlipsEnums;
use App\Concerns\FormatsCurrency;
use App\Models\Asset;
use App\Models\Dependent;
use PHPUnit\Framework\Attributes\Depends;

class EmploymentPresenter extends BasePresenter
{
    use FlipsEnums;
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
                   //Need to do this next 
                   return [
                       'employment_status' => 'Retiring',
                       'employer' => $this->model->employer,
                       'job_title' => $this->model->job_title,
                       'pension_option_p_a' => $ps->defined_benefit_pension->chosen === 0 ? '' : '',
                       'pension_option_l_s' =>  $ps->defined_benefit_pension->chosen === 0 ? '' : '',
                   ];
               }
               else{
                   //dc pension
                   return [
                       'employment_status' => 'Retiring',
                       'employer' => $this->model->employer,
                       'job_title' => $this->model->job_title,
                       'pension_option_p_a' => $ps->defined_benefit_pension->chosen === 0 ? '' : '',
                       'pension_option_l_s' =>  $ps->defined_benefit_pension->chosen === 0 ? '' : '',
                   ];
               }


           }
           if($this->model->client->strategy_report_recommendation->retirement_status === $this->enumValueByName('strategy_report_recommendations.retirement_status','Flex Retirement'))
           {

           }

//[
//    'employment_status' => 'Employed',
//    'employer' => '',
//    'job_title' => '',
//    'salary' => ''
       }
       elseif($this->model->employment_status == $this->enumValueByName('employment.employment_status','Retired'))
       {
           return [
                   'employment_status' => 'Retired',
                   'retirement_income' => ''
           ];
       }
       else{

       }


//],
//[

//],

   }

}
