<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function getGoogleGeoLocation($addressData)
    {
        $ci =& get_instance();
        
        $googleAddress = str_replace(' ', '+', $addressData['address']."+".$addressData['city']."+".$addressData['state']."+".$addressData['zip']);

        $json = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='. $googleAddress . '&key=AIzaSyCJYyN9VpSHoekJjR8zbW-xZH-istregZg');

        $result = json_decode($json);
        $latLongArr = array(
                        "lat" => $result->results[0]->geometry->location->lat,
                        "lng" => $result->results[0]->geometry->location->lng
                    );
        
        return $latLongArr;
    }