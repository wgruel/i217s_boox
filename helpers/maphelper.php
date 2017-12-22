<?php

class MapHelper {

  public static function geoCode($address){
    // set default values for lat and lng
    $location['lat'] = 0.0;
    $location['lng'] = 0.0;

    // define maps url
    // + add address to the API call
    // NOTE: address is urlencoded as we need to
    // encode special characters (e.g. transform "Nobelstr. 20, Stuttgart"
    // to "obelstr.+20%2C+Stuttgart")
    $maps_url = 'https://' .
        'maps.googleapis.com/' .
        'maps/api/geocode/json' .
        '?address=' . urlencode($address);
    // call the URL and read the result that we get - treat it like a file
    $maps_json = file_get_contents($maps_url);
    // treat the result as a JSON object
    $maps_array = json_decode($maps_json, true);
    // the API may return an error, so only set
    // values if everything went fine...
    if ($maps_array['status'] == "OK"){
      $location['lat'] = $maps_array['results'][0]['geometry']['location']['lat'];
      $location['lng'] = $maps_array['results'][0]['geometry']['location']['lng'];
    }
    else {
      // here, we can decide if we want to do something with the error ...
      // for now, I just create a JS-console.log for illustration purposes
      echo "<script>console.log('geoCodingAPI did not return a valid result');</script>";
    }
    return $location;
  }

}

?>
