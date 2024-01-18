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
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Viet Nam',
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
        'types' => [
            0 => 'Home',
            1 => 'Business',
            2 => 'Other',
            3 => 'Registered',
            4 => 'Correspondence'
        ]
    ]



];
