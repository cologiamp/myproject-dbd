<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enums
    |--------------------------------------------------------------------------
    |
    | Here we define config backed enums
    |
    */
    'factfind' => [
       'basic-details' => [
           'personal-details',
           'health-details',
           'address-and-contact-details',
           'family',
           'employment-details',
       ],
       'income-and-expenditure' => [

       ],
       'assets' => [

       ],
       'liabilities' => [

       ],
       'risk' => [

       ],
    ],
    'health' => [
        'smoker' => [
            0 => 'Unknown',
            1 => 'Yes',
            2 => 'No'
        ]
    ],
    'client' => [
        'statuses' => [
            0 => 'New Case',
            1 => 'In Progress',
            4 => 'Completed'
        ],
        'gendered_titles' => [
            0 => 2,
            1 => 1,
            2 => 2,
            3 => 2,
            4 => 0,
            5 => 0,
            6 => 0,
            7 => 0,
            8 => 1,
            9 => 2,
            10 =>  0,
            11 => 0,
            12 => 0,
            13 => 0,
            14 => 0,
            15 => 0,
            16 => 0,
            17 => 0,
            18 => 1,
            19 => 2,
            20 => 2,
            21 => 1,
            22 => 2,
            23 => 0,
            24 => 1,
            25 => 2,
            26 => 1,
            27 => 0,
            28 => 0,
            29 => 0,
            30 => 0,
            31 => 2,
            32 => 0,
            33 => 0,
            34 => 0,
            35 => 0,
            36 => 1,
            37 => 2,
            38 => 0,
            39 => 0,
            40 => 1,
            41 => 1,
            42 => 0,
            43 => 0,
            44 => 1,
            45 => 1,
            46 => 2,
            47 => 0,
            48 => 0,
            49 => 0,
            50 => 0,
            51 => 0,
            52 => 0,
            53 => 0,
            54 => 0,
            55 => 0,
            56 =>0,
            57 =>0,
            58 => 0,
            59 => 1,
            60 => 2,
            61 => 0,
            62 => 0,
            63 => 0,
            64 => 1,
            65 => 0,
            66 => 0,
            67 => 0,
            68 => 0,
            69 => 0,
            70 => 1,
            71 => 2,
            72 => 0
        ],
        'title' => [
            0 => 'Miss',
            1 => 'Mr',
            2 => 'Mrs',
            3 => 'Ms',
            4 => 'Admiral',
            5 => 'Air Commodore',
            6 => 'Air Vice Marshall',
            7 => 'Archdeacon',
            8 => 'Baron',
            9 => 'Baroness',
            10 => 'Bishop',
            11 => 'Brigadier',
            12 => 'Canon',
            13 => 'Captain',
            14 => 'Colonel',
            15 => 'Commander',
            16 => 'Commodore',
            17 => 'Corporal',
            18 => 'Count',
            19 => 'Countess',
            20 => 'Dame',
            21 => 'Deacon',
            22 => 'Deaconess',
            23 => 'Dr',
            24 => 'Duchess',
            25 => 'Duke',
            26 => 'Father',
            27 => 'Flight Lieutenant',
            28 => 'General',
            29 => 'Group Captain',
            30 => 'Judge',
            31 => 'Lady',
            32 => 'Lance Corporal',
            33 => 'Lieutenant',
            34 => 'Lieutenant Colonel',
            35 => 'Lieutenant Commander',
            36 => 'Lord',
            37 => 'Madam',
            38 => 'Major',
            39 => 'Major General',
            40 => 'Master',
            41 => 'Monsignor',
            42 => 'Mx',
            43 => 'Other',
            44 => 'Pastor',
            45 => 'Prince',
            46 => 'Princess',
            47 => 'Private',
            48 => 'Professor',
            49 => 'Rabbi',
            50 => 'Rear Admiral',
            51 => 'Reverend',
            52 => 'Reverend Doctor',
            53 => 'Right Honourable',
            54 => 'Right Honourable Lord',
            55 => 'Right Reverend',
            56 => 'Second Lieutenant',
            57 => 'Sergeant',
            58 => 'Sheriff',
            59 => 'Sir',
            60 => 'Sister',
            61 => 'Squadron Leader',
            62 => 'Staff Sergeant',
            63 => 'Surgeon Captain',
            64 => 'The Earl of',
            65 => 'The Honourable',
            66 => 'The Reverend Canon',
            67 => 'Trooper',
            68 => 'Venerable',
            69 => 'Very Reverend',
            70 => 'Viscount',
            71 => 'Viscountess',
            72 => 'Wing Commander'
        ],
        'title_gender' => [
            0 => 2,
            1 => 1,
            2 => 2,
            3 => 2,
            4 => 'Admiral',
            5 => 'Air Commodore',
            6 => 'Air Vice Marshall',
            7 => 'Archdeacon',
            8 => 1,
            9 => 2,
            10 => 'Bishop',
            11 => 'Brigadier',
            12 => 'Canon',
            13 => 1,
            14 => 1,
            15 => 1,
            16 => 'Commodore',
            17 => 'Corporal',
            18 => 1,
            19 => 2,
            20 => 2,
            21 => 1,
            22 => 2,
            23 => 'Dr',
            24 => 2,
            25 => 1,
            26 => 1,
            27 => 'Flight Lieutenant',
            28 => 'General',
            29 => 'Group Captain',
            30 => 'Judge',
            31 => 2,
            32 => 'Lance Corporal',
            33 => 'Lieutenant',
            34 => 'Lieutenant Colonel',
            35 => 'Lieutenant Commander',
            36 => 1,
            37 => 2,
            38 => 'Major',
            39 => 'Major General',
            40 => 'Master',
            41 => 'Monsignor',
            42 => 'Mx',
            43 => 'Other',
            44 => 1,
            45 => 1,
            46 => 2,
            47 => 'Private',
            48 => 'Professor',
            49 => 1,
            50 => 'Rear Admiral',
            51 => 'Reverend',
            52 => 'Reverend Doctor',
            53 => 'Right Honourable',
            54 => 1,
            55 => 'Right Reverend',
            56 => 'Second Lieutenant',
            57 => 'Sergeant',
            58 => 'Sheriff',
            59 => 1,
            60 => 2,
            61 => 'Squadron Leader',
            62 => 'Staff Sergeant',
            63 => 'Surgeon Captain',
            64 => 'The Earl of',
            65 => 'The Honourable',
            66 => 'The Reverend Canon',
            67 => 'Trooper',
            68 => 'Venerable',
            69 => 'Very Reverend',
            70 => 1,
            71 => 2,
            72 => 'Wing Commander'
        ],
        'gender' => [
            0 => 'Unspecified',
            1 => 'Male',
            2 => 'Female'
        ],
        'marital_status' =>[
            1 => 'Single',
            2 => 'Engaged',
            3 => 'Married',
            4 => 'Civil Partner',
            5 => 'Dissolved Civil Partner',
            6 => 'Surviving Civil Partner',
            7 => 'Divorced',
            8 => 'Separated',
            9 => 'Widowed',
            10 => 'Living Together',
            0 => 'Unknown'
        ],
        'nationality' => [
            0 => 'British',
            1 => 'English',
            2 => 'Manx',
            3 => 'Northern Irish',
            4 => 'Scottish',
            5 => 'Welsh',
            6 => 'Other',
            7 => 'Afghan',
            8 => 'Albanian',
            9 => 'Algerian',
            10 => 'American',
            11 => 'Andorran',
            12 => 'Angolan',
            13 => 'Antiguans',
            14 => 'Argentinean',
            15 => 'Armenian',
            16 => 'Australian',
            17 => 'Austrian',
            18 => 'Azerbaijani',
            19 => 'Bahamian',
            20 => 'Bahraini',
            21 => 'Bangladeshi',
            22 => 'Barbadian',
            23 => 'Barbudans',
            24 => 'Batswana',
            25 => 'Belarusian',
            26 => 'Belgian',
            27 => 'Belizean',
            28 => 'Beninese',
            29 => 'Bermudian',
            30 => 'Bhutanese',
            31 => 'Bolivian',
            32 => 'Bosnian',
            33 => 'Brazilian',
            34 => 'Bruneian',
            35 => 'Bulgarian',
            36 => 'Burkinabe',
            37 => 'Burmese',
            38 => 'Burundian',
            39 => 'Cambodian',
            40 => 'Cameroonian',
            41 => 'Canadian',
            42 => 'Cape Verdean',
            43 => 'Central African',
            44 => 'Chadian',
            45 => 'Chilean',
            46 => 'Chinese',
            47 => 'Colombian',
            48 => 'Comoran',
            49 => 'Congolese',
            50 => 'Costa Rican',
            51 => 'Croatian',
            52 => 'Cuban',
            53 => 'Cypriot',
            54 => 'Czech',
            55 => 'Danish',
            56 => 'Djibouti',
            57 => 'Dominican',
            58 => 'Dutch',
            59 => 'East Timorese',
            60 => 'Ecuadorean',
            61 => 'Egyptian',
            62 => 'Emirian',
            63 => 'Equatorial Guinean',
            64 => 'Eritrean',
            65 => 'Estonian',
            66 => 'Ethiopian',
            67 => 'Fijian',
            68 => 'Filipino',
            69 => 'Finnish',
            70 => 'French',
            71 => 'Gabonese',
            72 => 'Gambian',
            73 => 'Georgian',
            74 => 'German',
            75 => 'Ghanaian',
            76 => 'Greek',
            77 => 'Grenadian',
            78 => 'Guatemalan',
            79 => 'Guinea-Bissauan',
            80 => 'Guinean',
            81 => 'Guyanese',
            82 => 'Haitian',
            83 => 'Herzegovinian',
            84 => 'Honduran',
            85 => 'Hungarian',
            86 => 'Icelander',
            87 => 'I-Kiribati',
            88 => 'Indian',
            89 => 'Indonesian',
            90 => 'Iranian',
            91 => 'Iraqi',
            92 => 'Irish',
            93 => 'Israeli',
            94 => 'Italian',
            95 => 'Ivorian',
            96 => 'Jamaican',
            97 => 'Japanese',
            98 => 'Jordanian',
            99 => 'Kazakhstani',
            100 => 'Kenyan',
            101 => 'Kittian and Nevisian',
            102 => 'Kuwaiti',
            103 => 'Kyrgyz',
            104 => 'Laotian',
            105 => 'Latvian',
            106 => 'Lebanese',
            107 => 'Liberian',
            108 => 'Libyan',
            109 => 'Liechtensteiner',
            110 => 'Lithuanian',
            111 => 'Luxembourger',
            112 => 'Macedonian',
            113 => 'Malagasy',
            114 => 'Malawian',
            115 => 'Malaysian',
            116 => 'Maldivan',
            117 => 'Malian',
            118 => 'Maltese',
            119 => 'Marshallese',
            120 => 'Mauritanian',
            121 => 'Mauritian',
            122 => 'Mexican',
            123 => 'Micronesian',
            124 => 'Moldovan',
            125 => 'Monacan',
            126 => 'Mongolian',
            127 => 'Moroccan',
            128 => 'Mosotho',
            129 => 'Motswana',
            130 => 'Mozambican',
            131 => 'Namibian',
            132 => 'Nauruan',
            133 => 'Nepalese',
            134 => 'New Zealander',
            135 => 'Nicaraguan',
            136 => 'Nigerian',
            137 => 'Nigerien',
            138 => 'North Korean',
            139 => 'Norwegian',
            140 => 'Omani',
            141 => 'Pakistani',
            142 => 'Palauan',
            143 => 'Panamanian',
            144 => 'Papua New Guinean',
            145 => 'Paraguayan',
            146 => 'Peruvian',
            147 => 'Polish',
            148 => 'Portuguese',
            149 => 'Qatari',
            150 => 'Romanian',
            151 => 'Russian',
            152 => 'Rwandan',
            153 => 'Saint Lucian',
            154 => 'Salvadoran',
            155 => 'Samoan',
            156 => 'San Marinese',
            157 => 'Sao Tomean',
            158 => 'Saudi',
            159 => 'Senegalese',
            160 => 'Serbian',
            161 => 'Seychellois',
            162 => 'Sierra Leonean',
            163 => 'Singaporean',
            164 => 'Slovakian',
            165 => 'Slovenian',
            166 => 'Solomon Islander',
            167 => 'Somali',
            168 => 'South African',
            169 => 'South Korean',
            170 => 'Spanish',
            171 => 'Sri Lankan',
            172 => 'Sudanese',
            173 => 'Surinamer',
            174 => 'Swazi',
            175 => 'Swedish',
            176 => 'Swiss',
            177 => 'Syrian',
            178 => 'Taiwanese',
            179 => 'Tajik',
            180 => 'Tanzanian',
            181 => 'Thai',
            182 => 'Tobagonian',
            183 => 'Togolese',
            184 => 'Tongan',
            185 => 'Trinidadian',
            186 => 'Tunisian',
            187 => 'Turkish',
            188 => 'Tuvaluan',
            189 => 'Ugandan',
            190 => 'Ukrainian',
            191 => 'Uruguayan',
            192 => 'Uzbekistani',
            193 => 'Venezuelan',
            194 => 'Vietnamese',
            195 => 'Yemenite',
            196 => 'Zambian',
            197 => 'Zimbabwean'
        ],
        'nationalityISO' => [
            0 => 'GBR', // British
            1 => 'GBR', // English
            2 => 'GBR', // Manx
            3 => 'GBR', // Northern Irish
            4 => 'GBR', // Scottish
            5 => 'GBR', // Welsh
            6 => 'Other', // Other
            7 => 'AFG', // Afghan
            8 => 'ALB', // Albanian
            9 => 'DZA', // Algerian
            10 => 'USA', // American
            11 => 'AND', // Andorran
            12 => 'AGO', // Angolan
            13 => 'ATG', // Antiguans
            14 => 'ARG', // Argentinean
            15 => 'ARM', // Armenian
            16 => 'AUS', // Australian
            17 => 'AUT', // Austrian
            18 => 'AZE', // Azerbaijani
            19 => 'BHS', // Bahamian
            20 => 'BHR', // Bahraini
            21 => 'BGD', // Bangladeshi
            22 => 'BRB', // Barbadian
            23 => 'ATG', // Barbudans (part of Antigua and Barbuda)
            24 => 'BWA', // Batswana
            25 => 'BLR', // Belarusian
            26 => 'BEL', // Belgian
            27 => 'BLZ', // Belizean
            28 => 'BEN', // Beninese
            29 => 'BMU', // Bermudian
            30 => 'BTN', // Bhutanese
            31 => 'BOL', // Bolivian
            32 => 'BIH', // Bosnian
            33 => 'BRA', // Brazilian
            34 => 'BRN', // Bruneian
            35 => 'BGR', // Bulgarian
            36 => 'BFA', // Burkinabe
            37 => 'MMR', // Burmese
            38 => 'BDI', // Burundian
            39 => 'KHM', // Cambodian
            40 => 'CMR', // Cameroonian
            41 => 'CAN', // Canadian
            42 => 'CPV', // Cape Verdean
            43 => 'CAF', // Central African
            44 => 'TCD', // Chadian
            45 => 'CHL', // Chilean
            46 => 'CHN', // Chinese
            47 => 'COL', // Colombian
            48 => 'COM', // Comoran
            49 => 'COG', // Congolese (Republic of the Congo)
            50 => 'CRI', // Costa Rican
            51 => 'HRV', // Croatian
            52 => 'CUB', // Cuban
            53 => 'CYP', // Cypriot
            54 => 'CZE', // Czech
            55 => 'DNK', // Danish
            56 => 'DJI', // Djibouti
            57 => 'DOM', // Dominican
            58 => 'NLD', // Dutch
            59 => 'TLS', // East Timorese
            60 => 'ECU', // Ecuadorean
            61 => 'EGY', // Egyptian
            62 => 'ARE', // Emirian
            63 => 'GNQ', // Equatorial Guinean
            64 => 'ERI', // Eritrean
            65 => 'EST', // Estonian
            66 => 'ETH', // Ethiopian
            67 => 'FJI', // Fijian
            68 => 'PHL', // Filipino
            69 => 'FIN', // Finnish
            70 => 'FRA', // French
            71 => 'GAB', // Gabonese
            72 => 'GMB', // Gambian
            73 => 'GEO', // Georgian
            74 => 'DEU', // German
            75 => 'GHA', // Ghanaian
            76 => 'GRC', // Greek
            77 => 'GRD', // Grenadian
            78 => 'GTM', // Guatemalan
            79 => 'GNB', // Guinea-Bissauan
            80 => 'GIN', // Guinean
            81 => 'GUY', // Guyanese
            82 => 'HTI', // Haitian
            83 => 'BIH', // Herzegovinian
            84 => 'HND', // Honduran
            85 => 'HUN', // Hungarian
            86 => 'ISL', // Icelander
            87 => 'KIR', // I-Kiribati
            88 => 'IND', // Indian
            89 => 'IDN', // Indonesian
            90 => 'IRN', // Iranian
            91 => 'IRQ', // Iraqi
            92 => 'IRL', // Irish
            93 => 'ISR', // Israeli
            94 => 'ITA', // Italian
            95 => 'CIV', // Ivorian
            96 => 'JAM', // Jamaican
            97 => 'JPN', // Japanese
            98 => 'JOR', // Jordanian
            99 => 'KAZ', // Kazakhstani
            100 => 'KEN', // Kenyan
            101 => 'KNA', // Kittian and Nevisian,
            102 => 'KWT', // Kuwaiti
            103 => 'KGZ', // Kyrgyz
            104 => 'LAO', // Laotian
            105 => 'LVA', // Latvian
            106 => 'LBN', // Lebanese
            107 => 'LBR', // Liberian
            108 => 'LBY', // Libyan
            109 => 'LIE', // Liechtensteiner
            110 => 'LTU', // Lithuanian
            111 => 'LUX', // Luxembourger
            112 => 'MKD', // Macedonian
            113 => 'MDG', // Malagasy
            114 => 'MWI', // Malawian
            115 => 'MYS', // Malaysian
            116 => 'MDV', // Maldivan
            117 => 'MLI', // Malian
            118 => 'MLT', // Maltese
            119 => 'MHL', // Marshallese
            120 => 'MRT', // Mauritanian
            121 => 'MUS', // Mauritian
            122 => 'MEX', // Mexican
            123 => 'FSM', // Micronesian
            124 => 'MDA', // Moldovan
            125 => 'MCO', // Monacan
            126 => 'MNG', // Mongolian
            127 => 'MAR', // Moroccan
            128 => 'LSO', // Mosotho
            129 => 'BWA', // Motswana
            130 => 'MOZ', // Mozambican
            131 => 'NAM', // Namibian
            132 => 'NRU', // Nauruan
            133 => 'NPL', // Nepalese
            134 => 'NZL', // New Zealander
            135 => 'NIC', // Nicaraguan
            136 => 'NGA', // Nigerian
            137 => 'NER', // Nigerien
            138 => 'PRK', // North Korean
            139 => 'NOR', // Norwegian
            140 => 'OMN', // Omani
            141 => 'PAK', // Pakistani
            142 => 'PLW', // Palauan
            143 => 'PAN', // Panamanian
            144 => 'PNG', // Papua New Guinean
            145 => 'PRY', // Paraguayan
            146 => 'PER', // Peruvian
            147 => 'POL', // Polish
            148 => 'PRT', // Portuguese
            149 => 'QAT', // Qatari
            150 => 'ROU', // Romanian
            151 => 'RUS', // Russian
            152 => 'RWA', // Rwandan
            153 => 'LCA', // Saint Lucian
            154 => 'SLV', // Salvadoran
            155 => 'WSM', // Samoan
            156 => 'SMR', // San Marinese
            157 => 'STP', // Sao Tomean
            158 => 'SAU', // Saudi
            159 => 'SEN', // Senegalese
            160 => 'SRB', // Serbian
            161 => 'SYC', // Seychellois
            162 => 'SLE', // Sierra Leonean
            163 => 'SGP', // Singaporean
            164 => 'SVK', // Slovakian
            165 => 'SVN', // Slovenian
            166 => 'SLB', // Solomon Islander
            167 => 'SOM', // Somali
            168 => 'ZAF', // South African
            169 => 'KOR', // South Korean
            170 => 'ESP', // Spanish
            171 => 'LKA', // Sri Lankan
            172 => 'SDN', // Sudanese
            173 => 'SUR', // Surinamer
            174 => 'SWZ', // Swazi
            175 => 'SWE', // Swedish
            176 => 'CHE', // Swiss
            177 => 'SYR', // Syrian
            178 => 'TWN', // Taiwanese
            179 => 'TJK', // Tajik
            180 => 'TZA', // Tanzanian
            181 => 'THA', // Thai
            182 => 'TTO', // Tobagonian
            183 => 'TGO', // Togolese
            184 => 'TON', // Tongan
            185 => 'TTO', // Trinidadian
            186 => 'TUN', // Tunisian
            187 => 'TUR', // Turkish
            188 => 'TUV', // Tuvaluan
            189 => 'UGA', // Ugandan
            190 => 'UKR', // Ukrainian
            191 => 'URY', // Uruguayan
            192 => 'UZB', // Uzbekistani
            193 => 'VEN', // Venezuelan
            194 => 'VNM', // Vietnamese
            195 => 'YEM', // Yemenite
            196 => 'ZMB', // Zambian
            197 => 'ZWE', // Zimbabwean
        ],
        'iso_2' => [
            'GB' => 'United Kingdom',
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua And Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia And Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote D\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island & Mcdonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic Of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle Of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States Of',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory, Occupied',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts And Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre And Miquelon',
            'VC' => 'Saint Vincent And Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome And Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia And Sandwich Isl.',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard And Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad And Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks And Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'US' => 'United States',
            'UM' => 'United States Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Vietnam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis And Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe'
        ],
        'iso_2_int' => [
            "X",
            "AF",
            "AX",
            "AL",
            "DZ",
            "AS",
            "AD",
            "AO",
            "AI",
            "AQ",
            "AG",
            "AR",
            "AM",
            "AW",
            "AU",
            "AT",
            "AZ",
            "BS",
            "BH",
            "BD",
            "BB",
            "BY",
            "BE",
            "BZ",
            "BJ",
            "BM",
            "BT",
            "BO",
            "BA",
            "BW",
            "BV",
            "BR",
            "IO",
            "BN",
            "BG",
            "BF",
            "BI",
            "KH",
            "CM",
            "CA",
            "CV",
            "KY",
            "CF",
            "TD",
            "CL",
            "CN",
            "CX",
            "CC",
            "CO",
            "KM",
            "CG",
            "CD",
            "CK",
            "CR",
            "CI",
            "HR",
            "CU",
            "CY",
            "CZ",
            "DK",
            "DJ",
            "DM",
            "DO",
            "EC",
            "EG",
            "SV",
            "GQ",
            "ER",
            "EE",
            "ET",
            "FK",
            "FO",
            "FJ",
            "FI",
            "FR",
            "GF",
            "PF",
            "TF",
            "GA",
            "GM",
            "GE",
            "DE",
            "GH",
            "GI",
            "GR",
            "GL",
            "GD",
            "GP",
            "GU",
            "GT",
            "GG",
            "GN",
            "GW",
            "GY",
            "HT",
            "HM",
            "VA",
            "HN",
            "HK",
            "HU",
            "IS",
            "IN",
            "ID",
            "IR",
            "IQ",
            "IE",
            "IM",
            "IL",
            "IT",
            "JM",
            "JP",
            "JE",
            "JO",
            "KZ",
            "KE",
            "KI",
            "KR",
            "KW",
            "KG",
            "LA",
            "LV",
            "LB",
            "LS",
            "LR",
            "LY",
            "LI",
            "LT",
            "LU",
            "MO",
            "MK",
            "MG",
            "MW",
            "MY",
            "MV",
            "ML",
            "MT",
            "MH",
            "MQ",
            "MR",
            "MU",
            "YT",
            "MX",
            "FM",
            "MD",
            "MC",
            "MN",
            "ME",
            "MS",
            "MA",
            "MZ",
            "MM",
            "NA",
            "NR",
            "NP",
            "NL",
            "AN",
            "NC",
            "NZ",
            "NI",
            "NE",
            "NG",
            "NU",
            "NF",
            "MP",
            "NO",
            "OM",
            "PK",
            "PW",
            "PS",
            "PA",
            "PG",
            "PY",
            "PE",
            "PH",
            "PN",
            "PL",
            "PT",
            "PR",
            "QA",
            "RE",
            "RO",
            "RU",
            "RW",
            "BL",
            "SH",
            "KN",
            "LC",
            "MF",
            "PM",
            "VC",
            "WS",
            "SM",
            "ST",
            "SA",
            "SN",
            "RS",
            "SC",
            "SL",
            "SG",
            "SK",
            "SI",
            "SB",
            "SO",
            "ZA",
            "GS",
            "ES",
            "LK",
            "SD",
            "SR",
            "SJ",
            "SZ",
            "SE",
            "CH",
            "SY",
            "TW",
            "TJ",
            "TZ",
            "TH",
            "TL",
            "TG",
            "TK",
            "TO",
            "TT",
            "TN",
            "TR",
            "TM",
            "TC",
            "TV",
            "UG",
            "UA",
            "AE",
            "GB",
            "US",
            "UM",
            "UY",
            "UZ",
            "VU",
            "VE",
            "VN",
            "VG",
            "VI",
            "WF",
            "EH",
            "YE",
            "ZM",
            "ZW",
        ]
    ],
    'address' => [
        'residency_status' => [
            0 => 'OwnerOccupierMortgaged',
            1 => 'OwnerOccupierUnencumbered',
            2 => 'TenantPrivate',
            3 => 'TenantLocalAuthorityHousingAssociation',
            4 => 'LivingWithFamilyFriends'
        ],
        'residency_status_public' => [
            0 => 'Owner Occupier - Mortgaged',
            1 => 'Owner Occupier - Unencumbered',
            2 => 'Tenant - Private',
            3 => 'Tenant - Local Authority / Housing Association',
            4 => 'Living with family/friends'
        ],
        'types' => [
            0 => 'Home',
            1 => 'Business',
            2 => 'Other',
            3 => 'Registered',
            4 => 'Correspondence'
        ],
        'country' => [
            238  => 'United Kingdom',
            1 => 'Afghanistan',
            2 => 'Aland Islands',
            3 => 'Albania',
            4 => 'Algeria',
            5 => 'American Samoa',
            6 => 'Andorra',
            7 => 'Angola',
            8 => 'Anguilla',
            9 => 'Antarctica',
            10 => 'Antigua and Barbuda',
            11 => 'Argentina',
            12 => 'Armenia',
            13 => 'Aruba',
            14 => 'Australia',
            15 => 'Austria',
            16 => 'Azerbaijan',
            17  => 'Bahamas',
            18  => 'Bahrain',
            19  => 'Bangladesh',
            20  => 'Barbados',
            21  => 'Belarus',
            22  => 'Belgium',
            23  => 'Belize',
            24  => 'Benin',
            25  => 'Bermuda',
            26  => 'Bhutan',
            27  => 'Bolivia',
            28  => 'Bonaire, Sint Eustatius and Saba',
            29  => 'Bosnia and Herzegovina',
            30  => 'Botswana',
            31  => 'Bouvet Island',
            32  => 'Brazil',
            33  => 'British Indian Ocean Territory',
            34  => 'Brunei Darussalam',
            35  => 'Bulgaria',
            36  => 'Burkina Faso',
            37  => 'Burundi',
            38  => 'Cambodia',
            39  => 'Cameroon',
            40  => 'Canada',
            41  => 'Cape Verde',
            42  => 'Cayman Islands',
            43  => 'Central African Republic',
            44  => 'Chad',
            45  => 'Chile',
            46  => 'China',
            47  => 'Christmas Island',
            48  => 'Cocos (Keeling) Islands',
            49  => 'Colombia',
            50  => 'Comoros',
            51  => 'Congo',
            52  => 'Congo, Democratic Republic of the Congo',
            53  => 'Cook Islands',
            54  => 'Costa Rica',
            55  => 'Cote D"Ivoire',
            56  => 'Croatia',
            57  => 'Cuba',
            58  => 'Curacao',
            59  => 'Cyprus',
            60  => 'Czech Republic',
            61  => 'Denmark',
            62  => 'Djibouti',
            63  => 'Dominica',
            64  => 'Dominican Republic',
            65  => 'Ecuador',
            66  => 'Egypt',
            67  => 'El Salvador',
            68  => 'Equatorial Guinea',
            69  => 'Eritrea',
            70  => 'Estonia',
            71  => 'Ethiopia',
            72  => 'Falkland Islands (Malvinas)',
            73  => 'Faroe Islands',
            74  => 'Fiji',
            75  => 'Finland',
            76  => 'France',
            77  => 'French Guiana',
            78  => 'French Polynesia',
            79  => 'French Southern Territories',
            80  => 'Gabon',
            81  => 'Gambia',
            82  => 'Georgia',
            83  => 'Germany',
            84  => 'Ghana',
            85  => 'Gibraltar',
            86  => 'Greece',
            87  => 'Greenland',
            88  => 'Grenada',
            89  => 'Guadeloupe',
            90  => 'Guam',
            91  => 'Guatemala',
            92  => 'Guernsey',
            93  => 'Guinea',
            94  => 'Guinea-Bissau',
            95  => 'Guyana',
            96  => 'Haiti',
            97  => 'Heard Island and McDonald Islands',
            98  => 'Holy See (Vatican City State)',
            99  => 'Honduras',
            100  => 'Hong Kong',
            101  => 'Hungary',
            102  => 'Iceland',
            103  => 'India',
            104  => 'Indonesia',
            105  => 'Iran, Islamic Republic of',
            106  => 'Iraq',
            107  => 'Ireland',
            108  => 'Isle of Man',
            109  => 'Israel',
            110  => 'Italy',
            111  => 'Jamaica',
            112  => 'Japan',
            113  => 'Jersey',
            114  => 'Jordan',
            115  => 'Kazakhstan',
            116  => 'Kenya',
            117  => 'Kiribati',
            118  => 'Korea, Democratic People"s Republic of',
            119  => 'Korea, Republic of',
            120  => 'Kosovo',
            121  => 'Kuwait',
            122  => 'Kyrgyzstan',
            123  => 'Lao People"s Democratic Republic',
            124  => 'Latvia',
            125  => 'Lebanon',
            126  => 'Lesotho',
            127  => 'Liberia',
            128  => 'Libyan Arab Jamahiriya',
            129  => 'Liechtenstein',
            130  => 'Lithuania',
            131  => 'Luxembourg',
            132  => 'Macao',
            133  => 'Macedonia, the Former Yugoslav Republic of',
            134  => 'Madagascar',
            135  => 'Malawi',
            136  => 'Malaysia',
            137  => 'Maldives',
            138  => 'Mali',
            139  => 'Malta',
            140  => 'Marshall Islands',
            141  => 'Martinique',
            142  => 'Mauritania',
            143  => 'Mauritius',
            144  => 'Mayotte',
            145  => 'Mexico',
            146  => 'Micronesia, Federated States of',
            147  => 'Moldova, Republic of',
            148  => 'Monaco',
            149  => 'Mongolia',
            150  => 'Montenegro',
            151  => 'Montserrat',
            152  => 'Morocco',
            153  => 'Mozambique',
            154  => 'Myanmar',
            155  => 'Namibia',
            156  => 'Nauru',
            157  => 'Nepal',
            158  => 'Netherlands',
            159  => 'Netherlands Antilles',
            160  => 'New Caledonia',
            161  => 'New Zealand',
            162  => 'Nicaragua',
            163  => 'Niger',
            164  => 'Nigeria',
            165  => 'Niue',
            166  => 'Norfolk Island',
            167  => 'Northern Mariana Islands',
            168  => 'Norway',
            169  => 'Oman',
            170  => 'Pakistan',
            171  => 'Palau',
            172  => 'Palestinian Territory, Occupied',
            173  => 'Panama',
            174  => 'Papua New Guinea',
            175  => 'Paraguay',
            176  => 'Peru',
            177  => 'Philippines',
            178  => 'Pitcairn',
            179  => 'Poland',
            180  => 'Portugal',
            181  => 'Puerto Rico',
            182  => 'Qatar',
            183  => 'Reunion',
            184  => 'Romania',
            185  => 'Russian Federation',
            186  => 'Rwanda',
            187  => 'Saint Barthelemy',
            188  => 'Saint Helena',
            189  => 'Saint Kitts and Nevis',
            190  => 'Saint Lucia',
            191  => 'Saint Martin',
            192  => 'Saint Pierre and Miquelon',
            193  => 'Saint Vincent and the Grenadines',
            194  => 'Samoa',
            195  => 'San Marino',
            196  => 'Sao Tome and Principe',
            197  => 'Saudi Arabia',
            198  => 'Senegal',
            199  => 'Serbia',
            200  => 'Serbia and Montenegro',
            201  => 'Seychelles',
            202  => 'Sierra Leone',
            203  => 'Singapore',
            204  => 'St Martin',
            205  => 'Slovakia',
            206  => 'Slovenia',
            207  => 'Solomon Islands',
            208  => 'Somalia',
            209  => 'South Africa',
            210  => 'South Georgia and the South Sandwich Islands',
            211  => 'South Sudan',
            212  => 'Spain',
            213  => 'Sri Lanka',
            214  => 'Sudan',
            215  => 'Suriname',
            216  => 'Svalbard and Jan Mayen',
            217  => 'Swaziland',
            218  => 'Sweden',
            219  => 'Switzerland',
            220  => 'Syrian Arab Republic',
            221  => 'Taiwan, Province of China',
            222  => 'Tajikistan',
            223  => 'Tanzania, United Republic of',
            224  => 'Thailand',
            225  => 'Timor-Leste',
            226  => 'Togo',
            227  => 'Tokelau',
            228  => 'Tonga',
            229  => 'Trinidad and Tobago',
            230  => 'Tunisia',
            231  => 'Turkey',
            232  => 'Turkmenistan',
            233  => 'Turks and Caicos Islands',
            234  => 'Tuvalu',
            235  => 'Uganda',
            236  => 'Ukraine',
            237  => 'United Arab Emirates',
            239  => 'United States',
            240  => 'United States Minor Outlying Islands',
            241  => 'Uruguay',
            242  => 'Uzbekistan',
            243  => 'Vanuatu',
            244  => 'Venezuela',
            245  => 'Vietnam',
            246  => 'Virgin Islands, British',
            247  => 'Virgin Islands, U.s.',
            248  => 'Wallis and Futuna',
            249  => 'Western Sahara',
            250  => 'Yemen',
            251  => 'Zambia',
            252  => 'Zimbabwe'
        ]
    ],
    'dependent' => [
        'relationship_type' => [
            0 => 'Son',
            1 => 'Daughter',
            2 => 'Partner',
            3 => 'Husband',
            4 => 'Wife',
            5 => 'Other'
        ],
    ],
    'employment' => [
        'employment_status' => [
            0 => 'Unknown',
            1 => 'Self Employed',
            2 => 'Company Director',
            3 => 'Retired',
            4 => 'Unemployed',
            5 => 'Houseperson',
            6 => 'Student',
            7 => 'Maternity Leave',
            8 => 'Long Term Illness',
            9 => 'Contract Worker',
            10 => 'Employed',
            11 => 'Carer Of a Child Under 16',
            12 => 'Carer Of a Person Over 16',
            13 => 'Other'
        ],
    ],
    'incomes' => [
        'income_type' => [
            0 => 'Salary',
            1 => 'Bonus',
            2 => 'Car Allowance',
            3 => 'Overtime',
            4 => 'P11D',
            5 => 'Self-Employment Annual Profit',
            6 => 'Regular Pension Income',
            7 => 'State Pension Income',
            8 => 'Taxable State Benefits',
            9 => 'Non-taxable State Benefits',
            10 => 'Rental Income',
            11 => 'Other Income'
        ],
        'frequency' => [
            0 => 'Monthly',
            1 => 'Weekly',
            2 => 'Fortnightly',
            3 => 'FourWeekly',
            4 => 'Quarterly',
            5 => 'HalfYearly',
            6 => 'Annually',
            7 => 'OneOff'
        ],
        'frequency_public' => [
            0 => 'Monthly',
            1 => 'Weekly',
            2 => 'Once every 2 weeks',
            3 => 'Every 4 weeks',
            4 => 'Quarterly',
            5 => 'Every 6 months',
            6 => 'Annual',
            7 => 'One-off'
        ],
        'per_year_frequency' => [
            0 => 12,
            1 => 52,
            2 => 26,
            3 => 13,
            4 => 4,
            5 => 2,
            6 => 1,
            7 => 1 //?
        ]
    ],
    'expenditures' => [
        'basic_essential_expenditure' => [
            0 => 'Rent',
            1 => 'Council Tax',
            2 => 'Gas',
            3 => 'Electricity',
            4 => 'Water',
            5 => 'Telephone/Mobile',
            6 => 'Food & Personal Care',
            7 => 'Car/Travelling Expenses',
            8 => 'Housekeeping',
            9 => 'Ground Rent/Service charge',
            10 => 'Building Insurance',
            11 => 'Combined Utilities',
            12 => 'Maintenance/Alimony',
            13 => 'Other (Basic Essential)'
        ],
        'basic_quality_of_living_expenditure' => [
            14 => 'Clothing',
            15 => 'Furniture/Appliances/Repairs',
            16 => 'TV/Satellite/Internet/Basic Recreation',
            17 => 'Pension',
            18 => 'School Fee/Childcare',
            19 => 'Other (Basic Quality of Living)',
        ],
        'non_essential_outgoings_expenditure' => [
            20 => 'Gym',
            21 => 'Holidays',
            22 => 'Entertainment',
            23 => 'Life/General Assurance Premium',
            24 => 'Investments',
            25 => 'Protection Products',
            26 => 'Other Assets',
            27 => 'Other (Non-Essential)',
        ],
        'liability_expenditure' => [
            28 => 'Personal Loans',
            29 => 'Credit Cards',
            30 => 'Mortgage',
            31 => 'Other (Liability)',
        ],
        'lump_sum_expenditure' => [
            32 => 'Lump sum expenditure',
        ]
    ],
    'assets' => [
        'categories' => [
            1 => 'fixed_assets',
            2 => 'savings',
            3 => 'investments',
            4 => 'pensions'
        ],
        'types' => [
            0 => 'Cash',
            1 => 'Collectibles',
            2 => 'HolidayHome',
            3 => 'HomeContents',
            4 => 'InvestmentProperty',
            5 => 'MainResidence',
            6 => 'MotorVehicles',
            7 => 'NonIncomeProducingRealEstate',
            8 => 'OverseasProperty',
            9 => 'OwnBusiness',
            10 => 'RentalOrOtherProperty',
            11 => 'Investments',
            12 => 'Other',
            13 => 'Boat',
            14 => 'BuyToLetProperty'
        ],
        'types_public' => [
            0 => 'Cash',
            1 => 'Collectibles',
            2 => 'Holiday Home',
            3 => 'Home Contents',
            4 => 'Investment Property',
            5 => 'Main Residence',
            6 => 'Motor Vehicles',
            7 => 'Non Income-Producing Real Estate',
            8 => 'Overseas Property',
            9 => 'Own Business',
            10 => 'Rental Or Other Property',
            11 => 'Investments',
            12 => 'Other',
            13 => 'Boat',
            14 => 'Buy-to-Let Property'
        ],
        'types_public_no_cash_invest' => [
            1 => 'Collectibles',
            2 => 'Holiday Home',
            3 => 'Home Contents',
            4 => 'Investment Property',
            5 => 'Main Residence',
            6 => 'Motor Vehicles',
            7 => 'Non Income-Producing Real Estate',
            8 => 'Overseas Property',
            9 => 'Own Business',
            10 => 'Rental Or Other Property',
            12 => 'Other',
            13 => 'Boat',
            14 => 'Buy-to-Let Property'
        ],
        'account_types' => [
            0 => 'Current Account',
            1 => 'Savings Accounts',
            2 => 'Fixed Term Cash Bond',
            3 => 'NS&I Premium Bonds',
            4 => 'NS&I Savings Certificates',
            5 => 'Cash ISA',
            6 => 'Cash LISA',
            7 => 'Other cash based account',
        ],
        'investment_account_types' => [
            0 => 'Direct Equities',
            1 => 'Discretionary Management Service',
//            2 => 'General Investment Account', - as per Tom Cassidy, take off dropdown for FF.
            3 => 'ISA Stocks & Shares',
            4 => 'Onshore Bond',
            5 => 'Offshore Bond',
            6 => 'Seed Enterprise Investment Scheme',
            7 => 'Structured Product Income',
            8 => 'Structure Product Growth',
            9 => 'Venture Capital Trust',
            11 => 'Collectives',
            10 => 'Other Investment',
            12 => 'Other Investment (Tax Free)',//put through into IO as "Other Investment"
        ],
        'db_pension_statuses' => [
            0 => 'Active',
            1 => 'Deferred',
            2 => 'In payment',
        ],
        'dc_pension_types' => [
            0 => 'Occupational',
            1 => 'Personal Pension'
        ],
        'pension_crystallised_statuses' => [
            0 => 'Uncrystallised',
            1 => 'Fully Crystallised',
            2 => 'Part Crystallised'
        ],
        'pension_fund_types' => [
          0 => 'Active',
          1 => 'Passive',
          2 => 'WP - Fixed',
          3 => 'WP - Variable',
          4 => 'WP - Smoothed',
          5 => 'WP - Conventional',
          6 => 'Target Dated',
          7 => 'Investment Trust',
          8 => 'Shares',
          9 => 'Cash',
          10 => 'Discretionary Managed'
        ],
        'frequency' => [
            0 => 'Monthly',
            1 => 'Weekly',
            2 => 'Fortnightly',
            3 => 'FourWeekly',
            4 => 'Quarterly',
            5 => 'HalfYearly',
            6 => 'Annually',
            7 => 'OneOff'
        ],
        'frequency_public' => [
            0 => 'Monthly',
            1 => 'Weekly',
            2 => 'Once every 2 weeks',
            3 => 'Every 4 months',
            4 => 'Quarterly',
            5 => 'Every 6 months',
            6 => 'Annually',
            7 => 'One-off'
        ],
        'chosen' => [
            0 => 'Standard pension/standard tax free cash',
            1 => 'Reduced pension/Max tax free cash',
            2 => 'Other',
        ],
    ],
    'liabilities' => [
        'types' => [
            0 => 'MainResidence',
            1 => 'CreditStoreCards',
            2 => 'PersonalLoans',
            3 => 'MortgageRepaymentVehicle',
            4 => 'StudentLoans',
            5 => 'CarLoan',
            6 => 'HirePurchase',
            7 => 'MaintenanceAlimony',
            8 => 'OtherStructuredLoans',
            9 => 'OtherMortgages',
            10 => 'Other'
        ],
        'types_public' => [
            0 => 'Main Residence',
            1 => 'Credit/Store Cards',
            2 => 'Personal Loans',
            3 => 'Mortgage Repayment Vehicle',
            4 => 'Student Loans',
            5 => 'Car Loan',
            6 => 'Hire Purchase',
            7 => 'Maintenance Alimony',
            8 => 'Other Structured Loans',
            9 => 'Other Mortgages',
            10 => 'Other',
            11 => 'Mortgage',
        ],
        'repayment_or_interest' => [
            0 => 'Repayment',
            1 => 'Interest Only'
        ]
    ],
    'pension_objectives' => [
        'income_option' => [
            0 => 'Cash',
            1 => 'Annuity',
            2 => 'Income Drawdown',
        ],
        'lifetime_allowance_protection' => [
            0 => 'Primary',
            1 => 'Enhanced',
            2 => 'Fixed 2012',
            3 => 'Fixed 2014',
            4 => 'Fixed 2016',
            5 => 'Individual 2014',
            6 => 'Individual 2016'
        ],
        'if_experience_self_select' => [
            0 => 'I have no knowledge and/or experience',
            1 => 'I have some knowledge and/or experience',
            2 => 'I have good knowledge and/or experience',
        ],
        'if_experience_lifestyle' => [
            0 => 'I have no knowledge and/or experience',
            1 => 'I have some knowledge and/or experience',
            2 => 'I have good knowledge and/or experience',
        ],
        'if_experience_advisory' => [
            0 => 'I have no knowledge and/or experience',
            1 => 'I have some knowledge and/or experience',
            2 => 'I have good knowledge and/or experience',
        ],
        'if_experience_discretionary' => [
            0 => 'I have no knowledge and/or experience',
            1 => 'I have some knowledge and/or experience',
            2 => 'I have good knowledge and/or experience',
        ],
        'preferred_option' => [
            0 => 'Self-select investments',
            1 => 'Default lifestyle strategy',
            2 => 'Advised periodically',
            3 => 'To have my pension investments selected for me and be managed on a discretionary basis',
        ],
        'retirement_vs_legacy' => [
            0 => 'Strongly Prefer to use to fund retirement',
            1 => 'Prefer to use to fund retirement',
            2 => 'No preference',
            3 => 'Prefer to pass on as legacy',
            4 => 'Strongly Prefer to pass on as legacy',
        ],
        'proportion_of_total_funds' => [
            0 => '75% or more',
            1 => '51-75%',
            2 => '25-50%',
            3 => 'less than 25%',
        ],
        'spouse_lump_sum_death' => [
            0 => '100% of your pension income',
            1 => '67% of your pension income',
            2 => '50% of your pension income',
            3 => 'Other',
        ],
        'tax_free_lump_sum_preference' => [
            0 => 'Maximum tax-free cash',
            1 => 'Some tax-free cash',
            2 => 'Forego for tax-efficiency',
            3 => 'Do not need',
        ],
        'lump_sum_death_benefits' => [
            0 => 'Maximum',
            1 => 'Some',
            2 => 'Not a primary objective',
            3 => 'Not important',
        ],
    ],
    'strategy_report_recommendations' => [
        'report_version' => [
            0 => 'v1',
            1 => 'v2'
        ],
        'retirement_status' => [
            0 => 'Employed',
            1 => 'Retired',
            2 => 'Retiring',
            3 => 'Flex Retirement'
        ],
        'objective_type' => [
            0 => 'Considering Retirement',
            1 => 'Retiring',
            2 => 'Accumulating Wealth',
            3 => 'Retired'
        ],
        'topic' => [
            0 => [
                'name' => 'Legacy Planning',
                'icon' => '/adviser-hub/strategy-report/objective-icons/legacy_planning.svg',
                'objectives' => [
                    0 => 'You wish to ensure that your assets are directed in accordance with your wishes in the event of your death.',
                    1 => 'You wish to ensure that your chosen beneficiary(ies) benefit in the event of your death.',
                    2 => 'You wish to reduce the amount of Inheritance Tax payable.',
                    99 => 'Other'
                ],
                'what_for' => [
                    0 => 'So that that more of the assets that you have built up during your lifetime are able to be passed onto your children/grandchildren/other chosen beneficiaries when you die.',
                    1 => 'To give you peace of mind that your dependants/family will be financially secure when you are no longer around.',
                    99 => 'Other'
                ]
            ],
            1 => [
                'name' => 'Tax Efficiency',
                'icon' => '/adviser-hub/strategy-report/objective-icons/tax_efficiency.svg',
                'objectives' => [
                    0 => 'You wish to reduce the amount of tax you pay in relation to your existing savings/investments.',
                    1 => 'You wish to reduce the amount of income tax that you are paying.',
                    2 => 'You wish to reduce the amount of Inheritance Tax payable.',
                    3 => 'You wish to establish a tax efficient withdrawal strategy',
                    99 => 'Other'
                ],
                'what_for' => [
                    0 => 'To ensure that more of the assets that you have built up during your lifetime are able to be passed onto your chose beneficiary(ies) when you die.',
                    1 => 'To build up your wealth more quickly.',
                    2 => 'To reach your financial goals more quickly (e.g. for a specific purpose)',
                    3 => 'To retire earlier than expected/planned',
                    4 => 'To give you the option to retire earlier than expected/planned.',
                    5 => 'To help you fund future expenditure more easily.',
                    6 => 'So that you retain more of your money.',
                    7 => 'To improve the likelihood of your money not running out during retirement.',
                    99 => 'Other'
                ]
            ],
            2 => [
                'name' => 'Short-Term Cash Needs',
                'icon' => '/adviser-hub/strategy-report/objective-icons/short-term-cash-needs.svg',
                'objectives' => [
                    0 => 'It is important that you retain access to a proportion of your capital.',
                    1 => 'You wish to retain £XX',
                    99 => 'Other'
                ],
                'what_for' => [
                    0 => 'To cover any expenditure that cannot be met from income over the next XX years',
                    1 => 'To fund your income requirements in the first XX years of retirement',
                    2 => 'In case of emergencies.',
                    3 => 'To fund planned spending on holidays/a new car/gifts to children/gifts to grandchildren',
                    99 => 'Other'
                ]
            ],
            3 => [
                'name' => 'Ensure Income Needs Are Met',
                'icon' => '/adviser-hub/strategy-report/objective-icons/ensure_income_needs_are_met.svg',
                'objectives' => [
                    0 => 'You require an additional income of £XX per annum.',
                    1 => 'You require an income of £XX per annum.',
                    99 => 'Other'
                ],
                'what_for' => [
                    0 => 'To cover your basic living expenses.',
                    1 => 'To cover your day-to-day income needs.',
                    2 => 'To maintain your lifestyle (eating out/concerts/hobbies etc).',
                    3 => 'To fund additional holidays.',
                    4 => 'To assist children/grandchildren (e.g. university/school fees etc).',
                    5 => 'To top up your income and give you the standard of living you require',
                    99 => 'Other'
                ]
            ],
            4 => [
                'name' => 'Capital Growth',
                'icon' => '/adviser-hub/strategy-report/objective-icons/capital_growth.svg',
                'objectives' => [
                    0 => 'You are looking for your capital to grow in value.',
                    99 => 'Other'
                ],
                'what_for' => [
                    0 => 'So that you can pass on more assets to your children and/or grandchildren.',
                    1 => 'So that you can cover any ad-hoc lump sum expenditure as and when you wish.',
                    2 => 'So that you can retire earlier than planned.',
                    3 => 'So that you can maintain your current lifestyle throughout your retirement.',
                    4 => 'So as to improve the likelihood of your money not running out during retirement.',
                    5 => 'As you are concerned about inflation eroding the value of your assets over time.',
                    6 => 'So that it can maintain its purchasing power due to the eroding effects of inflation.',
                    99 => 'Other'
                ]
            ],
            5 => [
                'name' => 'Simplify Approach',
                'icon' => '/adviser-hub/strategy-report/objective-icons/simplify_approach.svg',
                'objectives' => [
                    0 => 'You wish to ease the burden of the administration/management of your savings and investments.',
                    1 => 'You wish to consolidate your savings/investments/pensions.',
                    99 => 'Other'
                ],
                'what_for' => [
                    0 => 'To ensure that you have a clear, and easy to understand plan in place.',
                    1 => 'So that it is easier for your spouse/partner to deal with the family finances in the event of your death.',
                    2 => 'To give you more time to enjoy your retirement.',
                    3 => 'To reduce the risks to which you are exposed',
                    4 => 'To give you peace of mind.',
                    99 => 'Other'
                ]
            ],
            6 => [
                'name' => 'Flexibility',
                'icon' => '/adviser-hub/strategy-report/objective-icons/flexibility.svg',
                'objectives' => [
                    0 => 'You wish for your financial strategy to be flexible.',
                    99 => 'Other'
                ],
                'what_for' => [
                    0 => 'So that it is able to adapt to your changing needs/objectives in the run up to and during retirement.',
                    99 => 'Other'
                ]
            ],
            7 => [
                'name' => 'Advice Service',
                'icon' => '/adviser-hub/strategy-report/objective-icons/advice_service.svg',
                'objectives' => [
                    0 => 'Investment Planning is a complex area. In light of this you feel that you require expert advice and guidance in order to.',
                    1 => 'Retirement Planning is a complex area with many considerations. In light of this you feel that you require expert advice and guidance in order to.',
                    99 => 'Other'
                ],
                'what_for' => [
                    0 => 'Reduce the risks to which you are exposed.',
                    1 => 'Improve tax efficiency.',
                    2 => 'Avoid costly and common mistakes.',
                    3 => 'Make it easier to reach your financial goals.',
                    4 => 'Simplify the administration of your finances.',
                    5 => 'Navigate through the various options available to you',
                    6 => 'Ensure that you are operating within and taking advantage of ever-changing legislation',
                    7 => 'Give you peace of mind',
                    99 => 'Other'
                ]
            ],
            8 => [
                'name' => 'Repayment of Liabilities',
                'icon' => '/adviser-hub/strategy-report/objective-icons/repayment_of_liabilities.svg',
                'objectives' => [
                    0 => 'A key objective for you is the immediate repayment of your outstanding liabilities.',
                    1 => 'A key objective for you is the repayment of your outstanding liabilities within the next XX years.',
                    2 => 'A key objective for you is the repayment of your outstanding liabilities by age XX.',
                    99 => 'Other'
                ],
                'what_for' => [
                    0 => 'As you wish to be debt free as you enter retirement.',
                    1 => 'To free up disposable income',
                    2 => 'As no longer having debt will provide you with peace of mind.',
                    99 => 'Other'
                ]
            ],
            9 => [
                'name' => 'Pension Planning',
                'icon' => '/adviser-hub/strategy-report/objective-icons/pension_planning.svg',
                'objectives' => [
                    0 => 'You wish to increase your pension provision.',
                    99 => 'Other'
                ],
                'what_for' => [
                    0 => 'To reduce the amount of income tax that you pay.',
                    1 => 'To build up your wealth more quickly.',
                    2 => 'To make use of the Annual Allowance.',
                    3 => 'To increase the funds available to you in retirement',
                    4 => 'To benefit from tax relief',
                    5 => 'To allow the prospect of earlier retirement.',
                    6 => 'To ensure that you have sufficient funds to sustain your lifestyle in retirement.',
                    7 => 'Allow you to pass assets on to your chosen beneficiaries in the event of your death.',
                    8 => 'Assist in Inheritance Tax planning.',
                    99 => 'Other'
                ]
            ],
            99 => 'Other'
        ],
        'recom_objective_type' => [
            0 => 'Secondary Objective',
            1 => 'Primary Objective'
        ],
        'call_to_action_types' => [
            0 => 'To efficiently reduce your over-exposure to XXX shares',
            1 => 'To efficiently reduce your over-exposure to cash deposits',
            2 => 'To avoid a strategy that suffers from inactive management (static asset allocation)',
            3 => 'An advice-based service can assist you in meeting your financial goals',
            4 => 'To improve the tax efficiency of your current plan.',
            5 => 'The consolidation of numerous pension pots can benefit you.',
            6 => 'The consolidation of numerous ISAs can benefit you.',
            7 => 'To create a tax efficient withdrawal strategy.',
            8 => 'To reduce the risks to which you are currently exposed.',
            9 => 'We can reduce your current level of administration.',
            10 => 'Diversification can help you reduce the risks to which you are exposed.',
            11 => 'To meet your income objectives',
            12 => 'To meet your growth objectives',
            13 => 'To align your strategy to your risk profile',
            14 => 'To counter the eroding effects of inflation over the medium to longer term',
            99 => 'Other'
        ]
    ],
    'relation_to_c2' => [
        0 => 'Partner'
    ],
    'investment_recommendation' => [
        'fee_basis' => [
            0 => 'Standard',
            1 => 'Discounted',
            2 => 'Friends and Family',
            3 => 'Staff',
            4 => 'JP Morgan Staff Terms',
            5 => 'Standard Chartered Staff Terms',
            6 => 'Saga Terms',
            7 => 'Staff'
        ],
        'report_type' => [
            0 => 'Growth Only',
            1 => 'Income & Growth',
            2 => 'Income Only'
        ],
        'frequency' => [
            0 => 'Monthly',
            1 => 'Weekly',
            2 => 'Fortnightly',
            3 => 'FourWeekly',
            4 => 'Quarterly',
            5 => 'HalfYearly',
            6 => 'Annually'
        ],
        'frequency_public' => [
            0 => 'Monthly',
            1 => 'Weekly',
            2 => 'Once every 2 weeks',
            3 => 'Every 4 months',
            4 => 'Quarterly',
            5 => 'Every 6 months',
            6 => 'Annually'
        ]
    ],
    'investment_recommendation_items' => [
        'types' => [
            0 => 'bond_element',
            1 => 'growth_element',
            2 => 'cash_element',
            3 => 'treasured_stock_transfers'
        ],
        'types_public' => [
            0 => 'Bond Element',
            1 => 'Growth Element',
            2 => 'Cash Element',
            3 => 'Treasured Stock Transfers'
        ],
        'descriptions' => [
            0 => 'General Investment Account',
            1 => 'Stocks and Shares ISA (transfer)',
            2 => 'Stocks and Shares ISA (new subscription)',
        ]
    ],
    'pension_recommendation' => [
        'fee_basis' => [
            0 => 'standard',
            1 => 'discounted',
            2 => 'friends_and_family',
            3 => 'staff',
            4 => 'jp_morgan_staff_terms',
            5 => 'standard_chartered_staff_terms',
            6 => 'saga_terms',
            7 => 'staff'
        ],
        'fee_basis_public' => [
            0 => 'Standard',
            1 => 'Discounted',
            2 => 'Friends and Family',
            3 => 'Staff',
            4 => 'JP Morgan Staff Terms',
            5 => 'Standard Chartered Staff Terms',
            6 => 'Saga Terms',
            7 => 'Staff'
        ],
        'report_type' => [
            0 => 'Growth Only',
            1 => 'Retirement Income Only'
        ],
        'employment_status' => [
            0 => 'Employed',
            1 => 'Self-employed',
            2 => 'Unemployed',
            3 => 'Retired'
        ],
        'workplace_pension_type' => [
            0 => 'Defined Contribution',
            1 => 'Defined Benefit',
            2 => 'Hybrid'
        ],
        'pension_review_transfer' => [
            0 => 'No',
            1 => 'Yes retiring soon',
            2 => 'Yes leaving employer',
            3 => 'Yes other'
        ],
        'retirement_option' => [
            0 => 'Income Drawdown',
            1 => 'Annuity',
            2 => 'Cash sum'
        ],
        'policy_type' => [
            0 => 'Defined Benefit Pension',
            1 => 'Defined Contribution Pension'
        ],
        'new_contribution_type' => [
            0 => 'Lump Sum',
            1 => 'Regular'
        ],
        'new_contribution_paid_by' => [
            0 => 'Client',
            1 => 'Employer',
            2 => '3rd Party'
        ],
        'frequency' => [
            0 => 'Monthly',
            1 => 'Weekly',
            2 => 'Fortnightly',
            3 => 'FourWeekly',
            4 => 'Quarterly',
            5 => 'HalfYearly',
            6 => 'Annually'
        ],
        'frequency_public' => [
            0 => 'Monthly',
            1 => 'Weekly',
            2 => 'Once every 2 weeks',
            3 => 'Every 4 months',
            4 => 'Quarterly',
            5 => 'Every 6 months',
            6 => 'Annually'
        ],
        'item_type' => [
            0 => 'Cash',
            1 => 'Income',
            2 => 'Growth'
        ],
        'loa_submitted' => [
            0 => 'Yes',
            1 => 'No',
            2 => 'Have Authority'
        ]
    ],

    'risk_assessment' => [
        'cash' => [
            0 => 'Liquidity Funds',
            1 => 'Deposit Accounts',
            2 => 'National Savings'
        ],
        'bonds' => [
            0 => 'Corporate Bonds',
            1 => 'Government Bonds (Gilts)',
            2 => 'Bond Funds ie OEICs',
            3 => 'Fixed Rate Deposits'
        ],
        'equities' => [
            0 => 'Shares/Unit Trusts/OEICs',
            1 => 'Investment Trusts',
            2 => 'Derivatives'
        ],
        'insurance' => [
            0 => 'With Profit Bonds',
            1 => 'Investment Bonds',
            2 => 'Guaranteed Stockmarket Bonds'
        ],
        'short_term_volatility' => [
            0 => '5%',
            1 => '10%',
            2 => '20%',
            3 => '30%',
            4 => '40%',
            5 => '50% or more'
        ],
        'retirement_options' => [
            0 => 'Annuities',
            1 => 'Income Drawdown',
            2 => 'Phased Retirement'
        ],
        'type' => [
            'INVESTMENT_TYPE' => 0,
            'PENSION_TYPE' => 1
        ],
        'assessment_result' => [
            'NOT_SUITABLE' => 0,
            'CAUTIOUS' => 1,
            'BALANCED' => 2,
            'ADVENTUROUS' => 3
        ],
        'assessment_result_public' => [
            0 => 'Not suitable',
            1 => 'Cautious',
            2 => 'Balanced',
            3 => 'Adventurous'
        ]
    ]
];
