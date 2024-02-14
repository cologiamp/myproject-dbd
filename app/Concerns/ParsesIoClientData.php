<?php

namespace App\Concerns;

use Illuminate\Support\Collection;

trait ParsesIoClientData{

    /**
     * The reverse of ParseClientData. Take in a Client, return it formatted for use in IO
     * @param $client
     * @return array
     */
    public function unparseClientData($client):array
    {
        $data = [];
        if($client->title != null)
        {
            $data['title'] = config('enums.client.title')[$client->title];
        }
        $data['firstName'] = $client->first_name;
        $data['middleName'] = $client->middle_name;
        $data['lastName'] = $client->last_name;
        if($client->date_of_birth != null)
        {
            $data['dateOfBirth'] = $client->date_of_birth;
        }
        if($client->gender != null)
        {
            $data['gender'] = config('enums.client.gender')[$client->gender];
        }
        if($client->marital_status != null)
        {
            $data['maritalStatus'] = config('enums.client.marital_status')[$client->marital_status];
        }
        if($client->nationality != null)
        {
            $data['nationalityCountry']['isoCode'] = config('enums.client.nationalityISO')[$client->nationality];
        }
        if($client->country_of_residence != null)
        {
            $data['territorialProfile']['countryOfResidence']['code'] = config('enums.client.iso_2_int')[$client->country_of_residence];
        }
        if($client->country_of_domicile != null)
        {
            $data['territorialProfile']['countryOfDomicile']['code'] = config('enums.client.iso_2_int')[$client->country_of_domicile];
        }
        if($client->salutation != null)
        {
            $data['salutation'] = $client->salutation;
        }
        if($client->ni_number != null)
        {
            $data['niNumber'] = $client->ni_number;
        }
        if($client->valid_will !== null) //Chore: Boolean fields can't use != null if false
        {
            $data['hasWill'] = $client->valid_will;
        }
        if($client->will_up_to_date !== null)
        {
            $data['isWillUptoDate'] = $client->will_up_to_date;
        }
        if($client->poa_granted !== null)
        {
            $data['isPowerOfAttorneyGranted'] = $client->poa_granted;
        }

        return $data;
    }



    /**
     * Taking in a parsed data array of IO Json, map it, determine what fields associate
     * where in our Database schema and isolate them into Eloquent Model clusters
     * @param $data
     * @return Collection
     */
    public function parseClientData($data):array
    {
        $client = $this->parseClientFields($data['person']);
        $cd = collect($data['contactdetails']);
        if($cd->where('type','Telephone')->first() != null)
        {
            $client['phone_number'] = $cd->where('type','Telephone')->first()['value'];
        }
        if($cd->where('type','Email')->first() != null)
        {
            $client['email_address'] = $cd->where('type','Email')->first()['value'];
        }

        $addresses = collect($data['addresses'])->map(function ($address){
            return $this->parseAddressFields($address);
        });

        return [
            'client' => $client,
            'addresses' => $addresses
        ];
    }

    public function parseAddressFields($address):array
    {
        $data = [];
        if(array_key_exists('residencyStatus',$address) && $address['residencyStatus'] != null)
        {
            $data['residency_status'] = array_flip(config('enums.address.residency_status'))[$address['residencyStatus']];
        }
        if(array_key_exists('type',$address) && $address['type'] != null)
        {
            $data['type'] = array_flip(config('enums.address.types'))[$address['type']];
        }
        if(array_key_exists('residentFrom',$address) && $address['residentFrom'] != null)
        {
            $data['date_from'] = $address['residentFrom'];
        }
        if(array_key_exists('line1',$address['address']) && $address['address']['line1'] != null)
        {
            $data['address_line_1'] = $address['address']['line1'];
        }
        if(array_key_exists('line2',$address['address']) && $address['address']['line2'] != null)
        {
            $data['address_line_2'] = $address['address']['line2'];
        }
        if(array_key_exists('locality',$address['address']) && $address['address']['locality'] != null)
        {
            $data['city'] = $address['address']['locality'];
        }
        if(array_key_exists('postalCode',$address['address']) && $address['address']['postalCode'] != null)
        {
            $data['postcode'] = $address['address']['postalCode'];
        }
        if(array_key_exists('country',$address['address']) && $address['address']['country']['name'] != null)
        {
            $data['country'] = array_flip(config('enums.address.country'))[$address['address']['country']['name']];
        }
        if(array_key_exists('county',$address['address']) && $address['address']['county']['name'] != null)
        {
            $data['county'] = $address['address']['county']['name'];
        }
        return $data;
    }



    public function parseClientFields($person):array
    {
        $data = [];
        if(array_key_exists('title',$person) && $person['title'] != null)
        {
            $data['title'] = array_flip(config('enums.client.title'))[$person['title']];
        }
        if(array_key_exists('firstName',$person) && $person['firstName'] != null)
        {
            $data['first_name'] = $person['firstName'];
        }
        if(array_key_exists('middleName',$person) && $person['middleName'] != null)
        {
            $data['middle_name'] = $person['middleName'];
        }
        if(array_key_exists('lastName',$person) && $person['lastName'] != null)
        {
            $data['last_name'] = $person['lastName'];
        }
        if(array_key_exists('dateOfBirth',$person) && $person['dateOfBirth'] != null)
        {
            $data['date_of_birth'] = $person['dateOfBirth'];
        }
        if(array_key_exists('gender',$person) && $person['gender'] != null)
        {
            $data['gender'] =  array_flip(config('enums.client.gender'))[$person['gender']] ;
        }
        if(array_key_exists('maritalStatus',$person) && $person['maritalStatus'] != null)
        {
            $data['marital_status'] = array_flip(config('enums.client.marital_status'))[$person['maritalStatus']];
        }
        if(array_key_exists('NationalityCountry',$person) && $person['NationalityCountry']['name'] != null)
        {
            $data['nationality'] = array_flip(config('enums.client.nationality'))[$person['NationalityCountry']['name']];
        }
        if(array_key_exists('salutation',$person) && $person['salutation'] != null)
        {
            $data['salutation'] = $person['salutation'];
        }
        return $data;
    }
}
