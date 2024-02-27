<?php
namespace App\Repositories;

use App\Models\DefinedBenefitPension;
use App\Models\DefinedContributionPension;
use App\Models\PensionScheme;

class PensionRepository extends BaseRepository
{

    public function createOrUpdateDCPensions(mixed $dcPensions):void
    {
        collect($dcPensions)->each(function ($item){
            ray($item)->red();
            $pension = $this->updateBasePensionSchemeRecord($item);
            ray($pension)->orange();
            $this->createOrUpdateDC($pension,$item);
        });
    }

    public function createOrUpdateDBPensions(mixed $dbPensions):void
    {
        ray($dbPensions);
        collect($dbPensions)->each(function ($item){
            $pension = $this->updateBasePensionSchemeRecord($item);
            $this->createOrUpdateDB($pension,$item);
        });
    }


    //Private
    private function createOrUpdateDC(PensionScheme $pension,array $item): void
    {
        if($pension->has('defined_contribution_pension') && $pension->defined_contribution_pension != null)
        {
            ray('HERE')->green();
            $db = $pension->defined_contribution_pension;
        }
        else{
            $db = new DefinedContributionPension();
            $db->pension_scheme_id = $pension->id;
        }
        if(array_key_exists('type',$item))
        {
            $db->type = $item['type'];
        }
        else{
            $db->type = null;
        }
        if(array_key_exists('administrator',$item))
        {
            $db->administrator = $item['administrator'];
        }
        else{
            $db->administrator = null;
        }

        if(array_key_exists('policy_start_at',$item))
        {
            $db->policy_start_at = $item['policy_start_at'];
        }
        else{
            $db->policy_start_at = null;
        }

        if(array_key_exists('policy_number',$item))
        {
            $db->policy_number = $item['policy_number'];
        }
        else{
            $db->policy_number = null;
        }

        if(array_key_exists('gross_contribution_percent',$item))
        {
            $db->gross_contribution_percent = $item['gross_contribution_percent'];
        }
        else{
            $db->gross_contribution_percent = null;
        }

        if(array_key_exists('gross_contribution_absolute',$item))
        {
            $db->gross_contribution_absolute = $item['gross_contribution_absolute'];
        }
        else{
            $db->gross_contribution_absolute = null;
        }
        if(array_key_exists('employer_contribution_percent',$item))
        {
            $db->employer_contribution_percent = $item['employer_contribution_percent'];
        }
        else{
            $db->employer_contribution_percent = null;
        }
        if(array_key_exists('employer_contribution_absolute',$item))
        {
            $db->employer_contribution_absolute = $item['employer_contribution_absolute'];
        }
        else{
            $db->employer_contribution_absolute = null;
        }
        if(array_key_exists('value',$item))
        {
            $db->value = $item['value'];
        }
        else{
            $db->value = null;
        }
        if(array_key_exists('valuation_at',$item))
        {
            $db->valuation_at = $item['valuation_at'];
        }
        else{
            $db->valuation_at = null;
        }
        if(array_key_exists('is_retained',$item))
        {
            $db->is_retained = $item['is_retained'];
        }
        else{
            $db->is_retained = null;
        }
        if(array_key_exists('retained_value',$item))
        {
            $db->retained_value = $item['retained_value'];
        }

        if(array_key_exists('current_fund_value',$item)) {
            $db->current_fund_value = $item['current_fund_value'];
        }
        if(array_key_exists('current_transfer_value',$item)) {
            $db->current_transfer_value = $item['current_transfer_value'];
        }
        if(array_key_exists('fund_name',$item)) {
            $db->fund_name = $item['fund_name'];
        }
        if(array_key_exists('fund_type',$item)) {
            $db->fund_type = $item['fund_type'];
        }
        if(array_key_exists('crystallised_status',$item)) {
            $db->crystallised_status = $item['crystallised_status'];
        }
        if(array_key_exists('crystallised_percentage',$item)) {
            $db->crystallised_percentage = $item['crystallised_percentage'];
        }

        else{
            $db->retained_value = null;
        }



        $db->save();



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
        ray('updateBP');
        ray($pension)->purple();
        ray($item)->purple();
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
        if(array_key_exists('lqa_submitted',$item))
        {
            $pension->lqa_submitted = $item['lqa_submitted'];
        }
        else{
            $pension->lqa_submitted = null;
        }
        if(array_key_exists('policy_reviewed_transfer',$item))
        {
            $pension->policy_reviewed_transfer = $item['policy_reviewed_transfer'];
        }
        else{
            $pension->policy_reviewed_transfer = null;
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
            $pension->defined_contribution_pension->delete();
        }
        $pension->delete();
        return true;
    }
}
