<?php

$path = $_SERVER['DOCUMENT_ROOT'];
if(strpos($_SERVER['REQUEST_URI'], 'dev') !== FALSE) {
	$path .= '/dev';
}
//echo "P: $path";
require( $path . '/wp-load.php' );

$sql = "SELECT id, city, state, zipcode FROM alumni WHERE 1 AND lat IS NULL AND lng IS NULL";
$results = $wpdb->get_results($sql);
if(!empty($results)) {
	$locations = '';
	foreach($results as $result) {
		//echo '<pre>'; print_r($result); echo '</pre>';
		//$url = 'https://maps.googleapis.com/maps/api/geocode/json?address='. urlencode($result->city) .','. $result->state .'';
		$country = 'US' ;
		$address = '';
		$address .= urlencode($result->city .",". $result->state . " " . $country);
		echo "ADDRESS: " . $address . "<br>";
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=". $address ."&key=AIzaSyCHMwJQGzC5DG7A2gJhD9Wno2JIXwKKiMU";
		//echo "URL: $url<BR>";
		$resp = file_get_contents($url);
		$location = json_decode($resp, true);
		//echo '<pre>'; print_r($location); echo '</pre>';
		//echo "RESP: " . $resp;
		if($location['status'] == 'OK') {
			$lat = $location['results'][0]['geometry']['location']['lat'];
			$lng = $location['results'][0]['geometry']['location']['lng'];
			$wpdb->update( 
				'alumni', 
				array( 
					'lat' => $lat,
					'lng' => $lng
				), 
				array( 'ID' => $result->id ), 
				array( 
					'%s',
					'%s'
				), 
				array( '%d' ) 
			);
			//exit;
		}
		else {
			//print_r($location);
			if($location['error_message'] == 'You have exceeded your daily request quota for this API.' || $location['status'] == 'REQUEST_DENIED') {
				echo $location['error_message'] . "<br>";
				break;
			}
			else {
				echo $location['status'] . "<br>";
				continue;
			}
		}
	}
}
echo "$updated locations updated"; 


?>