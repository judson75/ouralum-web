<?php

$path = $_SERVER['DOCUMENT_ROOT'];
if(strpos($_SERVER['REQUEST_URI'], 'dev') !== FALSE) {
	$path .= '/dev';
}
//echo "P: $path";
require( $path . '/wp-load.php' );

$group = $_GET['group'];

if($group == '') {
	echo "Must send group id";
	exit;
}

$sql = "SELECT a.first_name, a.last_name, a.phone FROM alumni a, alum_groups ag WHERE 1 AND a.phone != '' AND ag.alum_id = a.id AND ag.group_id = '" . $group . "'";
$results = $wpdb->get_results($sql);

echo '<pre>'; print_r($results); echo '</pre>';

if(!empty($results)) {
	$list = array ('first_name', 'last_name', 'phone');
	
	foreach($results as $i => $result) {
		$list[$i] = array($result->first_name, $result->last_name, $result->phone);
	}
	
	$fp = fopen('file.csv', 'wb');

	foreach ($list as $fields) {
		fputcsv($fp, $fields);
	}

	fclose($fp);
}

?>