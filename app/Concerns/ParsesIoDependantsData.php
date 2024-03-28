<?php

namespace App\Concerns;

use Illuminate\Support\Collection;
use Carbon\Carbon;

trait ParsesIoDependantsData{

    /**
     * The reverse of ParseDependantsData. Take in Dependants, return them formatted for use in IO
     * @param $data
     * @return array
     */
    public function unparseDependantsData($data):array
    {
        $dependants = collect($data)->map(function ($dependant){
            return $this->unparseDependantFields($dependant);
        });

        return [
            'dependants' => $dependants
        ];
    }

    /**
     * Taking in a parsed data array of IO Json, map it, determine what fields associate
     * where in our Database schema and isolate them into Eloquent Model clusters
     * @param $data
     * @return array
     */
    public function parseDependantsData($data):array
    {
        $dependants = collect($data)->map(function ($dependant){
            return $this->parseDependantFields($dependant);
        });

        return [
            'dependants' => $dependants
        ];
    }

    public function parseDependantFields($dependant):array
    {
        $data = [];

        if(array_key_exists('name',$dependant) && $dependant['name'] != null)
        {
            $data['name'] = $dependant['name'];
        }
        if(array_key_exists('dateOfBirth',$dependant) && $dependant['dateOfBirth'] != null)
        {
            $data['born_at'] = substr($dependant['dateOfBirth'], 0, 10); //YYYY-MM-DD
        }
        if(array_key_exists('isFinanciallyDependant',$dependant) && $dependant['isFinanciallyDependant'] != null)
        {
            $data['financial_dependent'] = $dependant['isFinanciallyDependant'] === true ? 1 : 0;
        }
        if(array_key_exists('isLivingWith',$dependant) && $dependant['isLivingWith'] != null)
        {
            $data['is_living_with_clients'] = $dependant['isLivingWith'] === true ? 1 : 0;
        }
        return $data;
    }

    public function unparseDependantFields($dependant):array
    {
        $data = [];

        if($dependant->name != null)
        {
            $data['name'] = $dependant->name;
        }
        if($dependant->born_at != null)
        {
            $data['dateOfBirth'] = Carbon::parse($dependant->born_at)->toDateTimeString();
        }
        if($dependant->financial_dependent != null)
        {
            $data['isFinanciallyDependant'] = (bool)$dependant->financial_dependent;
        }
        if($dependant->is_living_with_clients != null)
        {
            $data['isLivingWith'] = (bool)$dependant->is_living_with_clients;
        }

        return $data;
    }

}
