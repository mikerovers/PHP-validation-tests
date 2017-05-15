<?php

/**
 * Created by PhpStorm.
 * User: mike
 * Date: 15-05-17
 * Time: 17:14
 */
class Website
{
    public $id;
    public $owner;
    public $url;
    public $upgradedAt;
    public $ownerObject;

    // List of banned words.
    // Could also be downloaded from database.
    public $bannedWords = [
        "minecraft",
        "mine",
        "craft"
    ];

    function __construct($data) {
        foreach($data as $key => $val) {
            if(property_exists(__CLASS__,$key)) {
                $this->$key = $val;
            }
        }
    }

    // Validate all the current validation methods.
    public function validate() {
        // All must pass to make the validation a success.
        if($this->validateDateUrl() && $this->validateUpgradeDate() && $this->validateBannedWords()) {
            return true;
        }

        return false;
    }

    // Validate the time between account creation upgrade moment.
    private function validateUpgradeDate() {
        $dateCreated = new DateTime($this->ownerObject->createdAt);
        $dateUpgraded = new DateTime($this->upgradedAt);

        if($dateUpgraded->getTimestamp() - $dateCreated->getTimestamp() === 1)
            return false;

        return true;
    }

    // Check if the url contains a banned word.
    private function validateBannedWords() {
        foreach ($this->bannedWords as $word) {
            if(strstr($this->url, $word) !== false)
                return false;
        }

        return true;
    }

    // Validate the url.
    private function validateDateUrl() {
        // Filter all illegal characters.
        $urll = filter_var($this->url, FILTER_SANITIZE_URL);

        // If url is valid, return true.
        if(!filter_var($urll, FILTER_VALIDATE_URL) === false) {
            return true;
        } else {
            return false;
        }
    }
}