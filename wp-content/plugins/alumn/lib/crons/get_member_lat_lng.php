<?php
if($_SERVER['DOCUMENT_ROOT'] == '') {
	$_SERVER['DOCUMENT_ROOT'] = '/home/bytz5rmw6jgl/public_html';
}
//echo $_SERVER['DOCUMENT_ROOT'];
include_once ($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');
$host = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
$pdo = new PDO($host, DB_USER, DB_PASSWORD);


$sql = "SELECT id, city, state, zipcode, lat, lng FROM alumni WHERE 1 AND lat IS NULL AND lng IS NULL AND city IS NOT NULL ";
$members = $wpdb->get_results($sql);

if(!empty($members)) {
    echo count($members) . ' members found to update<br>';

    foreach($members as $member) {
        //$address = str_replace(' ', '+', $member->address) . '+' . $member->city . '+' . $member->state . '+' . $member->zipcode;
        $address = $member->city . '+' . $member->state . '+' . $member->zipcode;
        //echo '<pre>'; print_r($member); echo '</pre>';
        echo $address . '<br>';
        //exit;
        $url = 'https://api.opencagedata.com/geocode/v1/json?q=' . $address .'&key=c931450fbb2f436f8e93bf7213b90d6f';
        $response = json_decode(file_get_contents($url), true);
        //echo '<pre>'; print_r($response); echo '</pre>';
        $lat = $response['results'][0]['geometry']['lat'];
        $lng = $response['results'][0]['geometry']['lng'];
        $last_update = date("Y-m-d");
        echo "LAT: $lat : LNG: $lng<BR>";
        if($lat != '' && $lng != '') {
            $wpdb->update( 
                'alumni', 
                array( 
                    'lat' => $lat,
                    'lng' => $lng,
                    'last_updated' => $last_update
                ), 
                array( 'id' => $member->id ), 
                array(
                    '%s',
                    '%s',
                    '%s'
                ), 
                array( '%d' ) 
            );
        }
        //exit;
        sleep(1);
    }
}
?>