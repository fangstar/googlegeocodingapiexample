<?php

//
$addr = "1600 Pennsylvania Avenue, 20500";

$result = geocode($addr);

print_r($result);

echo "The full address is: ".  $result['formatted_address'] 
." with latitude: ".$result['lat'] ." and longitude: ".$result['lon']." \n";

// function to geocode address, it will return false if unable to geocode address
function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address={$address}";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){
 
        // get the important data
		$street_number = NULL;
		$street_name = NULL;
		$city_name = NULL;
		$county_name = NULL;
		$state_name = NULL;
		$state_abbr = NULL;
		$country_name = NULL;
		$country_abbr = NULL;
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
        
		$address_components = $resp['results'][0]['address_components'];
		foreach ($address_components as $component) {
			$type = $component['types'][0];
			if ($type == 'street_number')
				$street_number = $component['short_name'];
			else if ($type == 'route')
				$street_name = $component['short_name'];
			else if ($type == 'locality')
				$city_name = $component['short_name'];
			else if ($type == 'administrative_area_level_2')
				$county_name = $component['short_name'];
			else if ($type == 'administrative_area_level_1') {
				$state_name = $component['long_name'];
				$state_abbr = $component['short_name'];
			}
			else if ($type == 'country') {
				$country_name = $component['long_name'];
				$country_abbr = $component['short_name'];
			}
			else if ($type == 'postal_code')
				$postal_code = $component['short_name'];
		}
		
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();   

			$data_arr['street_number'] = $street_number;
			$data_arr['street_name'] = $street_name;
			$data_arr['city_name'] = $city_name;
			$data_arr['county_name'] = $county_name;
			$data_arr['state_name'] = $state_name;
			$data_arr['state_abbr'] = $state_abbr;
			$data_arr['country_name'] = $country_name;
			$data_arr['country_abbr'] = $country_abbr;
			$data_arr['lat'] = $lati;
			$data_arr['lon'] = $longi; 
			$data_arr['formatted_address'] = $formatted_address;
          
            return $data_arr;
             
        }else{
            return false;
        }
         
    }else{
        return false;
    }
}

?>

