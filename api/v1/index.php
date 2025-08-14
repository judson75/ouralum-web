<?php

header('Access-Control-Allow-Origin: *');

/*

    OurAlum API

    This script provides a RESTful API interface for a web application

*/





/**

 * Deliver HTTP Response

 * @param string $format The desired HTTP response content type: [json, html, xml]

 * @param string $api_response The desired HTTP response data

 * @return void

 **/



/* Include wordpress base -- this will pull in TX plugin functions */

require_once '../lib/inc/core.inc.php';

require_once '../lib/class/api.class.php';

$api = new alumAPI();



function deliver_response($format, $api_response)
{

	// Define HTTP responses

	$http_response_code = array(

		200 => 'OK',

		400 => 'Bad Request',

		401 => 'Unauthorized',

		403 => 'Forbidden',

		404 => 'Not Found'

	);

	// Set HTTP Response

	header('HTTP/1.1 ' . $api_response['status'] . ' ' . $http_response_code[$api_response['status']]);



	// Process different content types

	if (strcasecmp($format, 'json') == 0) {



		// Set HTTP Response Content Type

		header('Content-Type: application/json; charset=utf-8');


		$json_response = json_encode($api_response);

		// if (json_last_error() !== JSON_ERROR_NONE) {
		// 	echo 'JSON encoding error: ' . json_last_error_msg();
		// } else {
		// 	echo 'JSON encoding successful';
		// }


		// Deliver formatted data

		echo $json_response;
	} elseif (strcasecmp($format, 'xml') == 0) {



		// Set HTTP Response Content Type

		header('Content-Type: application/xml; charset=utf-8');



		// Format data into an XML response (This is only good at handling string data, not arrays)

		$xml_response = '<?xml version="1.0" encoding="UTF-8"?>' . "\n" .

			'<response>' . "\n" .

			"\t" . '<code>' . $api_response['code'] . '</code>' . "\n" .

			"\t" . '<data>' . $api_response['data'] . '</data>' . "\n" .

			'</response>';



		// Deliver formatted data

		echo $xml_response;
	} else {



		// Set HTTP Response Content Type (This is only good at handling string data, not arrays)

		header('Content-Type: text/html; charset=utf-8');

		// Deliver formatted data

		echo $api_response['data'];
	}



	// End script process

	exit;
}



// Define whether an HTTPS connection is required

$HTTPS_required = FALSE;



// Define whether user authentication is required

$authentication_required = FALSE;



// Define API response codes and their related HTTP response

$api_response_code = array(

	0 => array('HTTP Response' => 400, 'Message' => 'Unknown Error'),

	1 => array('HTTP Response' => 200, 'Message' => 'Success'),

	2 => array('HTTP Response' => 403, 'Message' => 'HTTPS Required'),

	3 => array('HTTP Response' => 401, 'Message' => 'Authentication Required'),

	4 => array('HTTP Response' => 401, 'Message' => 'Authentication Failed'),

	5 => array('HTTP Response' => 404, 'Message' => 'Invalid Request'),

	6 => array('HTTP Response' => 400, 'Message' => 'Invalid Response Format')

);

//print_r($_REQUEST);

// Set default HTTP response of 'ok'

$response['code'] = 0;

$response['status'] = 404;

$response['data'] = NULL;



// --- Step 2: Authorization



// Optionally require connections to be made via HTTPS

if ($HTTPS_required && $_SERVER['HTTPS'] != 'on') {

	$response['code'] = 2;

	$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

	$response['data'] = $api_response_code[$response['code']]['Message'];



	// Return Response to browser. This will exit the script.

	deliver_response($_GET['format'], $response);
}



// Optionally require user authentication

if ($authentication_required) {



	if (empty($_POST['username']) || empty($_POST['password'])) {

		$response['code'] = 3;

		$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

		$response['data'] = $api_response_code[$response['code']]['Message'];



		// Return Response to browser

		deliver_response($_GET['format'], $response);
	}



	// Return an error response if user fails authentication. This is a very simplistic example

	// that should be modified for security in a production environment

	elseif ($_POST['username'] != 'foo' && $_POST['password'] != 'bar') {

		$response['code'] = 4;

		$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

		$response['data'] = $api_response_code[$response['code']]['Message'];



		// Return Response to browser

		deliver_response($_REQUEST['format'], $response);
	}
}



// --- Step 3: Process Request

//print_r($_REQUEST);

if ($_REQUEST['format'] == '') {

	$_REQUEST['format'] = 'json';
}

// Method 

//$method = $_REQUEST['method'];

$method = strtolower($_SERVER['REQUEST_METHOD']);

$action = $_REQUEST['action'];



//print_r($_SERVER);

//echo "METHOD: " . $method . "<BR>";

//exit;


//capture incoming requests and log payload and response
$log = "URL: https://ouralum.com/api/v1/" . $_REQUEST['action'] . " \r\n";
$log .= "METHOD: " . $_SERVER['REQUEST_METHOD'] . "\r\n";
$log .= "POST: \r\n";
$log .= print_r($_POST, true);
$log .= "\r\n";
$log .= "INPUT:\r\n";
$log .= print_r(file_get_contents('php://input'), true);
$log .= "\r\n";
$log .= "***************************************\r\n";
$fp = fopen('public_html/api/lib/logs/api_' . $_SERVER['REQUEST_METHOD'] . '_' . $_REQUEST['action'] . '_log_' . date("Y_m_d") . '.txt', 'a');
fwrite($fp, $log);
fclose($fp);



switch ($method) {

	case 'hello':

		$response['code'] = 1;

		$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

		$response['data'] = 'Hello World';

		break;

	case 'get':

		switch ($action) {

			case 'member_count_home':

				$response = $api->getMemberCountHome();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'alumns':

				$response = $api->getAlumns();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'alumn':

				$response = $api->getAlumn();


				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				// print_r($response);

				break;

			case 'member_profile':

				$response = $api->getProfile();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'photos':

				$response = $api->getPhotos();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'posts':

				$response = $api->getPosts();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'members':

				$response = $api->getMembers();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'jobs':

				$response = $api->getJobs();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'job':

				$response = $api->getJob();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'search_site':

				$response = $api->searchSite();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'user':

				$response = $api->getUser();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'states':

				$response = $api->getStateList();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'occupations':

				$response = $api->getOccupationList();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'occupation_categories':

				$response = $api->getOccupationCatList();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'get_group_percentage':

				$response = $api->getGroupPercentage();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;
		}

		break;

	case 'post':

		switch ($action) {

			case 'login':

				$response = $api->login();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'token':

				$response = $api->postToken();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'member_invite':

				$response = $api->sendMemberInvite();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'submit_photo':

				$response = $api->submitGroupPhoto();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'invite_list':

				$response = $api->getInviteList();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'send_invite_list':

				$response = $api->sendInviteList();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'group_contact':

				$response = $api->sendGroupContact();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'profile':

				$response = $api->saveProfile();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'notification':

				$response = $api->sendNotification();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'claim_profile':

				$response = $api->claim_profile();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

				break;

			case 'register':
				$response = $api->register();

				$response['status'] = $api_response_code[$response['code']]['HTTP Response'];
		}

		break;

	default:

		$response['code'] = 0;

		$response['status'] = $api_response_code[$response['code']]['HTTP Response'];

		$response['data'] = 'No Method Sent';

		break;
}



// Return Response to browser

deliver_response($_REQUEST['format'], $response);
