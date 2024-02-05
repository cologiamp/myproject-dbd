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
            $pension = $this->updateBasePensionSchemeRecord($item);
            $this->createOrUpdateDC($pension,$item);
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
    private function createOrUpdateDC($pension,$item): void
    {
        if($pension->has('defined_contribution_pension') && $pension->defined_contribution_pension != null)
        {
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
        else{
            $db->retained_value = null;
        }



        $db->save();



    }

    private function createOrUpdateDB($pension,$item): void
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

    private function updateBP(PensionScheme $pension,$item):PensionScheme
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
        return $pension;
    }

    public function deletePension($pension):bool
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
