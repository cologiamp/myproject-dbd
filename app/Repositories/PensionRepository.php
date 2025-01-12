<?php
namespace App\Repositories;

use App\Models\DefinedBenefitPension;
use App\Models\DefinedContributionPension;
use App\Models\PensionFund;
use App\Models\PensionScheme;

class PensionRepository extends BaseRepository
{

    public function createOrUpdateDCPensions(mixed $dcPensions):void
    {
        collect($dcPensions)->each(function ($item){
            $pension = $this->updateBasePensionSchemeRecord($item);
            $dc_pension = $this->createOrUpdateDC($pension,$item);
            if(array_key_exists('funds',$item))
            {
                collect($item['funds'])->each(function ($fund) use ($dc_pension){

                    if(array_key_exists('id',$fund) && $fund['id'] != null)
                    {
                        $fundRecord = PensionFund::find($fund['id']);
                    }
                    else{
                        $fundRecord = new PensionFund();
                        $fundRecord->defined_contribution_pension_id = $dc_pension['id'];

                    }
                    $fundRecord->fund_name = $fund['fund_name'];
                    $fundRecord->fund_type = $fund['fund_type'];
                    $fundRecord->current_fund_value = $fund['current_fund_value'];
                    $fundRecord->current_transfer_value = $fund['current_transfer_value'];
                    $fundRecord->save();
                });
            }

        });
    }

    public function createOrUpdateDBPensions(mixed $dbPensions):void
    {
        collect($dbPensions)->each(function ($item){
            $pension = $this->updateBasePensionSchemeRecord($item);
            $this->createOrUpdateDB($pension,$item);
        });
    }


    //Private
    private function createOrUpdateDC(PensionScheme $pension,array $item): DefinedContributionPension
    {
        if($pension->has('defined_contribution_pension') && $pension->defined_contribution_pension != null)
        {
            $dc = $pension->defined_contribution_pension;
        }
        else{
            $dc = new DefinedContributionPension();
            $dc->pension_scheme_id = $pension->id;
        }
        if(array_key_exists('type',$item))
        {
            $dc->type = $item['type'];
        }
        else{
            $dc->type = null;
        }
        if(array_key_exists('administrator',$item))
        {
            $dc->administrator = $item['administrator'];
        }
        else{
            $dc->administrator = null;
        }

        if(array_key_exists('policy_start_at',$item))
        {
            $dc->policy_start_at = $item['policy_start_at'];
        }
        else{
            $dc->policy_start_at = null;
        }

        if(array_key_exists('policy_number',$item))
        {
            $dc->policy_number = $item['policy_number'];
        }
        else{
            $dc->policy_number = null;
        }

        if(array_key_exists('gross_contribution_percent',$item))
        {
            $dc->gross_contribution_percent = $item['gross_contribution_percent'];
        }
        else{
            $dc->gross_contribution_percent = null;
        }

        if(array_key_exists('gross_contribution_absolute',$item))
        {
            $dc->gross_contribution_absolute = $item['gross_contribution_absolute'];
        }
        else{
            $dc->gross_contribution_absolute = null;
        }
        if(array_key_exists('employee_contribution_frequency',$item))
        {
            $dc->employee_contribution_frequency = $item['employee_contribution_frequency'];
        }
        else{
            $dc->employee_contribution_frequency = null;
        }
        if(array_key_exists('employer_contribution_percent',$item))
        {
            $dc->employer_contribution_percent = $item['employer_contribution_percent'];
        }
        else{
            $dc->employer_contribution_percent = null;
        }
        if(array_key_exists('employer_contribution_absolute',$item))
        {
            $dc->employer_contribution_absolute = $item['employer_contribution_absolute'];
        }
        else{
            $dc->employer_contribution_absolute = null;
        }
        if(array_key_exists('value',$item))
        {
            $dc->value = $item['value'];
        }
        else{
            $dc->value = null;
        }
        if(array_key_exists('valuation_at',$item))
        {
            $dc->valuation_at = $item['valuation_at'];
        }
        else{
            $dc->valuation_at = null;
        }
        if(array_key_exists('is_retained',$item))
        {
            $dc->is_retained = $item['is_retained'];
        }
        else{
            $dc->is_retained = null;
        }
        if(array_key_exists('retained_value',$item))
        {
            $dc->retained_value = $item['retained_value'];
        }

        if(array_key_exists('current_fund_value',$item)) {
            $dc->current_fund_value = $item['current_fund_value'];
        }
        if(array_key_exists('current_transfer_value',$item)) {
            $dc->current_transfer_value = $item['current_transfer_value'];
        }
        if(array_key_exists('fund_name',$item)) {
            $dc->fund_name = $item['fund_name'];
        }
        if(array_key_exists('fund_type',$item)) {
            $dc->fund_type = $item['fund_type'];
        }
        if(array_key_exists('crystallised_status',$item)) {
            $dc->crystallised_status = $item['crystallised_status'];
        }
        if(array_key_exists('crystallised_percentage',$item)) {
            $dc->crystallised_percentage = $item['crystallised_percentage'];
        }
        if(array_key_exists('employer_contribution_frequency',$item)) {
            $dc->employer_contribution_frequency = $item['employer_contribution_frequency'];
        }

        else{
            $dc->retained_value = null;
        }



        $dc->save();

        return $dc;

    }

    private function createOrUpdateDB(PensionScheme $pension, array $item): void
    {
        if($pension->has('defined_benefit_pension') && $pension->defined_benefit_pension != null)
        {
            $db = $pension->defined_benefit_pension;
        }
        else{
            $db = new DefinedBenefitPension();
            $db->pension_scheme_id = $pension->id;
        }
        if(array_key_exists('status',$item))
        {
            $db->status = $item['status'];
        }
        else{
            $db->status = null;
        }
        if(array_key_exists('prospective_pension_standard',$item))
        {
            $db->prospective_pension_standard = $item['prospective_pension_standard'];
        }
        else{
            $db->prospective_pension_standard = null;
        }

        if(array_key_exists('prospective_pension_max',$item))
        {
            $db->prospective_pension_max = $item['prospective_pension_max'];
        }
        else{
            $db->prospective_pension_max = null;
        }

        if(array_key_exists('prospective_pcls_standard',$item))
        {
            $db->prospective_pcls_standard = $item['prospective_pcls_standard'];
        }
        else{
            $db->prospective_pcls_standard = null;
        }

        if(array_key_exists('prospective_pcls_max',$item))
        {
            $db->prospective_pcls_max = $item['prospective_pcls_max'];
        }
        else{
            $db->prospective_pcls_max = null;
        }

        if(array_key_exists('cetv',$item))
        {
            $db->cetv = $item['cetv'];
        }
        else{
            $db->cetv = null;
        }
        if(array_key_exists('cetv_ends_at',$item))
        {
            $db->cetv_ends_at = $item['cetv_ends_at'];
        }
        else{
            $db->cetv_ends_at = null;
        }
        if(array_key_exists('chosen',$item))
        {
            $db->chosen = $item['chosen'];
        }
        else{
            $db->chosen = null;
        }
        if(array_key_exists('notes',$item))
        {
            $db->notes = $item['notes'];
        }
        else{
            $db->notes = null;
        }
        $db->save();



    }


    private function updateBasePensionSchemeRecord(array $item):PensionScheme
    {
        if(array_key_exists('id', $item) && $item['id'] != null){
            //Pension first
            $pension = PensionScheme::where('id',$item['id'])->first();
            $pension = $this->updateBP($pension,$item);
        }
        else {
            $pension = new PensionScheme();
            $pension = $this->updateBP($pension,$item);
        }
        $pension->save();
        return $pension;
    }

    private function updateBP(PensionScheme $pension, array $item):PensionScheme
    {
        if(array_key_exists('client_id',$item))
        {
            $pension->client_id = $item['client_id'];
        }
        else{
            $pension->client_id = null;
        }
        if(array_key_exists('employer',$item))
        {
            $pension->employer = $item['employer'];
        }
        else{
            $pension->employer = null;
        }
        if(array_key_exists('retirement_age',$item))
        {
            $pension->retirement_age = $item['retirement_age'];
        }
        else{
            $pension->retirement_age = null;
        }
        if(array_key_exists('loa_submitted',$item))
        {
            $pension->loa_submitted = $item['loa_submitted'];
        }
        else{
            $pension->loa_submitted = null;
        }
        if(array_key_exists('is_retained',$item))
        {
            $pension->is_retained = $item['is_retained'];
        }
        else{
            $pension->is_retained = null;
        }
        if(array_key_exists('active_pension_member',$item))
        {
            $pension->active_pension_member = $item['active_pension_member'];
        }
        else{
            $pension->active_pension_member = null;
        }
        if(array_key_exists('active_pension_member_reason_not',$item))
        {
            $pension->active_pension_member_reason_not = $item['active_pension_member_reason_not'];
        }
        else{
            $pension->active_pension_member_reason_not = null;
        }

        return $pension;
    }

    public function deletePension(PensionScheme $pension):bool
    {
        if($pension->defined_benefit_pension)
        {
            $pension->defined_benefit_pension->delete();
        }
        if($pension->defined_contribution_pension)
        {
            $pension->defined_contribution_pension->pension_funds()->delete();
            $pension->defined_contribution_pension->delete();
        }
        $pension->delete();
        return true;
    }
    public function deletePensionFund(PensionFund $pensionFund):bool
    {
        $pensionFund->delete();
        return true;
    }
}
