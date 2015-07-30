<?php

    require "aps/2/runtime.php";    

    /**
    * @type("http://myweatherdemo.com/operations/subscription_service/1.0")
    * @implements("http://aps-standard.org/types/core/resource/1.0")
    */
    
    class company extends \APS\ResourceBase
    
    {

        /**
         * @link("http://myweatherdemo.com/operations/application/1.0")
         * @required
         */
        public $application;
        
        /**
         * @link("http://aps-standard.org/types/core/account/1.0")
         * @required
         */
        public $account;
        
        /**
         * @type(string)
         * @title("Company identifier in MyWeatherDemo")
         */
        public $company_id;

        /**
         * @type(string)
         * @title("Login to MyWeatherDemo interface")
         */
        public $username;

        /**
         * @type(string)
         * @title("Password for MyWeatherDemo user")
         */
        public $password;

        const BASE_URL = "http://www.myweatherdemo.com/api/company/";
        
        public function provision(){
            
            $request = array(
                    'country' => $this->account->addressPostal->countryName,
                    'city' => $this->account->addressPostal->locality,
                    'name' => $this->account->companyName,
            );
            
            $response = $this->send_curl_request('POST', self::BASE_URL, $request);

            $this->company_id = $response->{'id'};
            $this->username = $response->{'username'};
            $this->password = $response->{'password'};
        }

        public function unprovision(){

            $url = self::BASE_URL . $this->company_id;
            $response = $this->send_curl_request('DELETE', $url);

        }

        /**
        * @verb(GET)
        * @path("/getTemperature")
        */
        public function getTemperature(){

            // to get current temperature we need to send GET request providing company_id
            $url = self::BASE_URL . $this->company_id;
            $response = $this->send_curl_request('GET', $url);

            // returning both fahrenheit and celsuis to the caller, in our case - company.js
            $temperature = array();
            $temperature['fahrenheit'] = $response->{'fahrenheit'};
            $temperature['celsius'] = $response->{'celsius'};
 
            // APS PHP runtime will automatically execute json_encode for $temperature
            return $temperature; 
            
        }

        private function send_curl_request($verb, $url, $payload = ''){

            $token = $this->application->provider_token;

            $headers = array(
                    'Content-type: application/json',
                    'x-provider-token: '. $token
            );

            $ch = curl_init();
            
            curl_setopt_array($ch, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_CUSTOMREQUEST => $verb,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($payload)
            ));
            
            $response = json_decode(curl_exec($ch));
            
            curl_close($ch);

            return $response;
        }
    }
?>
