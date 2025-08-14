<?php
//Get WP config
//print_r($_SERVER);
if($_SERVER['DOCUMENT_ROOT'] == '') {
	$_SERVER['DOCUMENT_ROOT'] = '/home/bytz5rmw6jgl/public_html';
}
include_once ($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');
$host = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
$pdo = new PDO($host, DB_USER, DB_PASSWORD);

?>