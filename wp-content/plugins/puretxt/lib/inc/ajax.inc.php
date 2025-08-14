<?php

/* 
 * Ajax handler for the PureTxt Plugin
 *
 * J. Cooper 01/15/2020
 *
 */

if(!session_id()) {
	session_start();
}

add_action("wp_ajax_verify_api_key", "verify_api_key");
add_action("wp_ajax_nopriv_verify_api_key", "verify_api_key");

function verify_api_key() {
	global $pt;
	//Verify API KEY, Then verify user hash
	$response = $pt->verifyAPIKey($_POST['api_key']);
	echo $response;
	exit;
}

add_action("wp_ajax_verify_user_hash", "verify_user_hash");
add_action("wp_ajax_nopriv_verify_user_hash", "verify_user_hash");

function verify_user_hash() {
	global $pt;
	//Verify API KEY, Then verify user hash
	$response = $pt->verifyUserHash($_POST['api_key'], $_POST['user_hash']);
	echo $response;
	exit;
}


add_action("wp_ajax_get_source_origin", "get_source_origin");
add_action("wp_ajax_nopriv_get_source_origin", "get_source_origin");

function get_source_origin() {
	global $pt;
	$response = $pt->getSourceOrigins($_POST['source']);
	echo json_encode($response);
	exit;
}


add_action("wp_ajax_get_table_columns", "get_table_columns");
add_action("wp_ajax_nopriv_get_table_columns", "get_table_columns");

function get_table_columns() {
	global $pt;
	$response = $pt->getTableColumns($_POST['db_table']);
	echo json_encode($response);
	exit;
}

add_action("wp_ajax_generate_subscriber_list", "generate_subscriber_list");
add_action("wp_ajax_nopriv_generate_subscriber_list", "generate_subscriber_list");

function generate_subscriber_list() {
	global $pt;
	$response = $pt->generateSubscribersList($_POST);
	echo json_encode($response);
	exit;
}