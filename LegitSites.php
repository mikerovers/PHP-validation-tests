<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 15-05-17
 * Time: 17:19
 */

require('model/Website.php');
require('model/User.php');

// Data setup
$data = json_decode($_GET["format"]);
$userData = $data->users;
$users = [];
$websitedata = $data->websites;
$websites = [];

// Make objects from the posted data.
foreach ($userData as $user) {
    // Cast user to object.
    $u = new User((array) $user);
    array_push($users, $u);

    // Get all websites from user.
    foreach ($websitedata as $website) {
        if ($website->owner === $u->id) {
            $website->ownerObject = $u;
            $w = new Website((array) $website);
            array_push($websites, $w);
        }
    }
}

// Loop through the validation methods.
$validatedWebsites = [];
foreach ($websites as $website) {
    if ($website->ownerObject->validate() && $website->validate()) {
        array_push($validatedWebsites, $website->id);
    }
}
$out = json_encode(array_values($validatedWebsites));
echo $out;
return $out;





