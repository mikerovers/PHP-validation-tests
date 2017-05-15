<?php

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 15-05-17
 * Time: 17:11
 */

class User
{
    public $id;
    public $ip;
    public $name;
    public $address;
    public $zipcode;
    public $country;
    public $createdAt;

    function __construct($data)
    {
        foreach($data as $key => $val) {
            if(property_exists(__CLASS__,$key)) {
                $this->$key = $val;
            }
        }
    }

    public function validate() {
        // Validate all the current validation methods.
        // All must pass to make the validation a success.
        if($this->validateZipcode() && $this->validateIp())
            return true;

        return false;
    }

    // Validate the zipcode from the user.
    private function validateZipcode() {
        $numbers = str_split(substr($this->zipcode, 0, 4), 1);

        if((int)max($numbers)-(int)min($numbers) == (count($numbers)-1)) {
            return false;
        }

        return true;
    }

    private function validateIp() {
        // If url is not valid, return false.
        if(filter_var($this->ip, FILTER_VALIDATE_IP) === false) {
            return false;
        }

        // Check if the user his/her ip address is a proxy.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,"http://check.getipintel.net/check.php?ip=$this->ip&contact=mike@mike.mike");
        $content = curl_exec($ch);

        if($content >= 0.90)
            return false;

        return true;

    }
}