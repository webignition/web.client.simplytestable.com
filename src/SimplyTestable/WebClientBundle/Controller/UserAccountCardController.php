<?php

namespace SimplyTestable\WebClientBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

class UserAccountCardController extends AbstractUserAccountController {
    
    public function indexAction() {
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        $userSummary = $this->getUserService()->getSummary($this->getUser())->getContentObject();
        
//        var_dump($userSummary->stripe_customer->active_card);
//        exit();

//        $plan = $this->getUserService()->getPlanSummary($this->getUser())->getContentObject();
//        if ($plan->name == 'basic') {
//            return $this->redirect($this->generateUrl('user_account_index', array(), true));
//        }
//        
//        $card = $this->getUserService()->getCardSummary($this->getUser())->getContentObject();      

        $currentYear = date('Y');
        
        $viewData = array_merge(array(
            'public_site' => $this->container->getParameter('public_site'),
            'user' => $this->getUser(),
            'user_summary' => $userSummary,
            'stripe_publishable_key' => $this->container->getParameter('stripe_publishable_key'),
            'countries' => $this->getCountries(),
            'is_logged_in' => true,
            'expiry_year_start' => $currentYear,
            'expiry_year_end' => $currentYear + 10
        ), $this->getViewFlashValues(array(
            'user_account_card_exception_message',
            'user_account_card_exception_param',
            'user_account_card_exception_code'
        )));

        $this->setTemplate('SimplyTestableWebClientBundle:User/Account:card.html.twig');
        return $this->sendResponse($viewData);
    }
    
    
    
    /**
     * 
     * @param array $keys
     * @return array
     */
    private function getViewFlashValues($keys) {
        $flashValues = array();
        
        foreach ($keys as $key) {
            $flashValues[$key] = $this->getFlash($key);
        }
        
        return $flashValues;
    }

    public function associateAction($stripe_card_token) {        
        if (($notLoggedInResponse = $this->getNotLoggedInResponse()) instanceof Response) {
            return $notLoggedInResponse;
        }
        
        try {
            $this->getUserAccountCardService()->associate($this->getUser(), $stripe_card_token);
            return $this->redirect($this->generateUrl('user_account_index', array(), true));
        } catch (\SimplyTestable\WebClientBundle\Exception\UserAccountCardException $userAccountCardException) {
            if ($this->isJsonResponseRequired()) {
                return $this->sendResponse(array(
                    'user_account_card_exception_message' => $userAccountCardException->getMessage(),
                    'user_account_card_exception_param' => $userAccountCardException->getParam(),
                    'user_account_card_exception_code' => $userAccountCardException->getCode()
                ));
            } else {
                $this->get('session')->setFlash('user_account_card_exception_message', $userAccountCardException->getMessage());
                $this->get('session')->setFlash('user_account_card_exception_param', $userAccountCardException->getParam());
                $this->get('session')->setFlash('user_account_card_exception_code', $userAccountCardException->getCode());                
                return $this->redirect($this->generateUrl('user_account_card', array(), true));
            }
        }
    }

    private function getCountries() {
        return array(
            "AF" => "Afghanistan",
            "AL" => "Albania",
            "DZ" => "Algeria",
            "AS" => "American Samoa",
            "AD" => "Andorra",
            "AO" => "Angola",
            "AI" => "Anguilla",
            "AQ" => "Antarctica",
            "AG" => "Antigua and Barbuda",
            "AR" => "Argentina",
            "AM" => "Armenia",
            "AW" => "Aruba",
            "AU" => "Australia",
            "AT" => "Austria",
            "AZ" => "Azerbaijan",
            "BH" => "Bahrain",
            "BD" => "Bangladesh",
            "BB" => "Barbados",
            "BY" => "Belarus",
            "BE" => "Belgium",
            "BZ" => "Belize",
            "BJ" => "Benin",
            "BM" => "Bermuda",
            "BT" => "Bhutan",
            "BO" => "Bolivia",
            "BA" => "Bosnia and Herzegovina",
            "BW" => "Botswana",
            "BV" => "Bouvet Island",
            "BR" => "Brazil",
            "IO" => "British Indian Ocean Territory",
            "VG" => "British Virgin Islands",
            "BN" => "Brunei",
            "BG" => "Bulgaria",
            "BF" => "Burkina Faso",
            "BI" => "Burundi",
            "CI" => "Côte d'Ivoire",
            "KH" => "Cambodia",
            "CM" => "Cameroon",
            "CA" => "Canada",
            "CV" => "Cape Verde",
            "KY" => "Cayman Islands",
            "CF" => "Central African Republic",
            "TD" => "Chad",
            "CL" => "Chile",
            "CN" => "China",
            "CX" => "Christmas Island",
            "CC" => "Cocos (Keeling) Islands",
            "CO" => "Colombia",
            "KM" => "Comoros",
            "CG" => "Congo",
            "CK" => "Cook Islands",
            "CR" => "Costa Rica",
            "HR" => "Croatia",
            "CU" => "Cuba",
            "CY" => "Cyprus",
            "CZ" => "Czech Republic",
            "CD" => "Democratic Republic of the Congo",
            "DK" => "Denmark",
            "DJ" => "Djibouti",
            "DM" => "Dominica",
            "DO" => "Dominican Republic",
            "TP" => "East Timor",
            "EC" => "Ecuador",
            "EG" => "Egypt",
            "SV" => "El Salvador",
            "GQ" => "Equatorial Guinea",
            "ER" => "Eritrea",
            "EE" => "Estonia",
            "ET" => "Ethiopia",
            "FO" => "Faeroe Islands",
            "FK" => "Falkland Islands",
            "FJ" => "Fiji",
            "FI" => "Finland",
            "MK" => "Former Yugoslav Republic of Macedonia",
            "FR" => "France",
            "FX" => "France, Metropolitan",
            "GF" => "French Guiana",
            "PF" => "French Polynesia",
            "TF" => "French Southern Territories",
            "GA" => "Gabon",
            "GE" => "Georgia",
            "DE" => "Germany",
            "GH" => "Ghana",
            "GI" => "Gibraltar",
            "GR" => "Greece",
            "GL" => "Greenland",
            "GD" => "Grenada",
            "GP" => "Guadeloupe",
            "GU" => "Guam",
            "GT" => "Guatemala",
            "GN" => "Guinea",
            "GW" => "Guinea-Bissau",
            "GY" => "Guyana",
            "HT" => "Haiti",
            "HM" => "Heard and Mc Donald Islands",
            "HN" => "Honduras",
            "HK" => "Hong Kong",
            "HU" => "Hungary",
            "IS" => "Iceland",
            "IN" => "India",
            "ID" => "Indonesia",
            "IR" => "Iran",
            "IQ" => "Iraq",
            "IE" => "Ireland",
            "IL" => "Israel",
            "IT" => "Italy",
            "JM" => "Jamaica",
            "JP" => "Japan",
            "JO" => "Jordan",
            "KZ" => "Kazakhstan",
            "KE" => "Kenya",
            "KI" => "Kiribati",
            "KW" => "Kuwait",
            "KG" => "Kyrgyzstan",
            "LA" => "Laos",
            "LV" => "Latvia",
            "LB" => "Lebanon",
            "LS" => "Lesotho",
            "LR" => "Liberia",
            "LY" => "Libya",
            "LI" => "Liechtenstein",
            "LT" => "Lithuania",
            "LU" => "Luxembourg",
            "MO" => "Macau",
            "MG" => "Madagascar",
            "MW" => "Malawi",
            "MY" => "Malaysia",
            "MV" => "Maldives",
            "ML" => "Mali",
            "MT" => "Mayotte",
            "MH" => "Marshall Islands",
            "MQ" => "Martinique",
            "MR" => "Mauritania",
            "MU" => "Mauritius",
            "MX" => "Mexico",
            "FM" => "Micronesia",
            "MD" => "Moldova",
            "MC" => "Monaco",
            "MN" => "Mongolia",
            "ME" => "Montenegro",
            "MS" => "Montserrat",
            "MA" => "Morocco",
            "MZ" => "Mozambique",
            "MM" => "Myanmar",
            "NA" => "Namibia",
            "NR" => "Nauru",
            "NP" => "Nepal",
            "NL" => "Netherlands",
            "AN" => "Netherlands Antilles",
            "NC" => "New Caledonia",
            "NZ" => "New Zealand",
            "NI" => "Nicaragua",
            "NE" => "Niger",
            "NG" => "Nigeria",
            "NU" => "Niue",
            "NF" => "Norfolk Island",
            "KP" => "North Korea",
            "MP" => "Northern Marianas",
            "NO" => "Norway",
            "OM" => "Oman",
            "PK" => "Pakistan",
            "PW" => "Palau",
            "PA" => "Panama",
            "PG" => "Papua New Guinea",
            "PY" => "Paraguay",
            "PE" => "Peru",
            "PH" => "Philippines",
            "PN" => "Pitcairn Islands",
            "PL" => "Poland",
            "PT" => "Portugal",
            "PR" => "Puerto Rico",
            "QA" => "Qatar",
            "RE" => "Reunion",
            "RO" => "Romania",
            "RU" => "Russia",
            "RW" => "Rwanda",
            "ST" => "São Tomé and Príncipe",
            "SH" => "Saint Helena",
            "PM" => "St. Pierre and Miquelon",
            "KN" => "Saint Kitts and Nevis",
            "LC" => "Saint Lucia",
            "VC" => "Saint Vincent and the Grenadines",
            "WS" => "Samoa",
            "SM" => "San Marino",
            "SA" => "Saudi Arabia",
            "SN" => "Senegal",
            "RS" => "Serbia",
            "SC" => "Seychelles",
            "SL" => "Sierra Leone",
            "SG" => "Singapore",
            "SK" => "Slovakia",
            "SI" => "Slovenia",
            "SB" => "Solomon Islands",
            "SO" => "Somalia",
            "ZA" => "South Africa",
            "GS" => "South Georgia and the South Sandwich Islands",
            "KR" => "South Korea",
            "ES" => "Spain",
            "LK" => "Sri Lanka",
            "SD" => "Sudan",
            "SR" => "Suriname",
            "SJ" => "Svalbard and Jan Mayen Islands",
            "SZ" => "Swaziland",
            "SE" => "Sweden",
            "CH" => "Switzerland",
            "SY" => "Syria",
            "TW" => "Taiwan",
            "TJ" => "Tajikistan",
            "TZ" => "Tanzania",
            "TH" => "Thailand",
            "BS" => "The Bahamas",
            "GM" => "The Gambia",
            "TG" => "Togo",
            "TK" => "Tokelau",
            "TO" => "Tonga",
            "TT" => "Trinidad and Tobago",
            "TN" => "Tunisia",
            "TR" => "Turkey",
            "TM" => "Turkmenistan",
            "TC" => "Turks and Caicos Islands",
            "TV" => "Tuvalu",
            "VI" => "US Virgin Islands",
            "UG" => "Uganda",
            "UA" => "Ukraine",
            "AE" => "United Arab Emirates",
            "GB" => "United Kingdom",
            "US" => "United States",
            "UM" => "United States Minor Outlying Islands",
            "UY" => "Uruguay",
            "UZ" => "Uzbekistan",
            "VU" => "Vanuatu",
            "VA" => "Vatican City",
            "VE" => "Venezuela",
            "VN" => "Vietnam",
            "WF" => "Wallis and Futuna Islands",
            "EH" => "Western Sahara",
            "YE" => "Yemen",
            "ZM" => "Zambia",
            "ZW" => "Zimbabwe",
        );
    }

    /**
     * 
     * @return \SimplyTestable\WebClientBundle\Services\UserAccountCardService
     */
    protected function getUserAccountCardService() {
        return $this->get('simplytestable.services.useraccountcardservice');
    }    

}