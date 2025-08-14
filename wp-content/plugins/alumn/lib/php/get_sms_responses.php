<?php
$path = $_SERVER['DOCUMENT_ROOT'];
if(strpos($_SERVER['REQUEST_URI'], 'dev') !== FALSE) {
	$path .= '/dev';
}
//echo "P: $path";
require( $path . '/wp-load.php' );

$url = 'https://puretxt.com/api/v1/messages/?key=867ee14b4869d33eb41e44036537e58d&type=incoming';
$response = json_decode(file_get_contents($url), true);
//echo "RESP: <pre>"; print_r($response); echo "</pre>";
$mc = 0;
foreach($response['data'] as $message) {
	//echo "MSG: <pre>"; print_r($message); echo "</pre>";
	//Get alumn id based on phone number
	//$phone = preg_replace("/[^0-9]/", "", $message['from_number']);
	$send_text = false;
	$phone = str_replace('+1', '', $message['from_number']);
	$sql = "SELECT id, first_name, last_name, user_id, email, phone, marketing_notes FROM alumni WHERE 1 AND REPLACE(REPLACE(REPLACE(REPLACE(phone, ' ', ''), '-', ''), ')', ''), '(', '') = '$phone'";
	//echo $sql . "<BR>";
	$al = $wpdb->get_row($sql);
//echo '<b>ALUM: ' . $al->first_name . ' ' . $al->last_name . ' @ ' . $al->email . ' (' . $al->id . ')</b><br>';
	//If they have notes, do nothing...
	if($al->marketing_notes != '') {
		echo "SKIPPING...<BR>";
		continue;
	}
	$to_phone = str_replace('+1', '', $message['from_number']);
	$add_alt_email = false;
	//echo '<pre>'; print_r($al); echo '</pre>';
	//Who is it..
	
	if( $al->user_id != '') {
		//echo '<font color=green>USER HAS CLAIMED ACCOUNT</font><br>';
		//echo "SKIPPING...<BR>";
		$skipped_claimed++;
		continue;
	}
	//wrong numbers
	if(strpos(strtolower($message['verb']), 'wrong number') !== FALSE ) {
		//echo '<font color=red>Need to remove phone number ' . $message['from_number'] . '?</font><br>';
		//Update Alum with no phone, put into note..
		if($al->phone != '') {
			//echo 'Updating wrong mobile <b>ALUM: ' . $al->first_name . ' ' . $al->last_name . ' @ ' . $al->email . ' (' . $al->id . ')</b><br>';
			/*
			$wpdb->update( 
				'alumni', 
				array( 
					'phone' => '',
					'marketing_notes' => '',
				), 
				array( 'id' => $al->id ), 
				array( 
					'%s',
					'%s'
				), 
				array( '%d' ) 
			);
			exit;
			*/
		}
	}
	elseif(hasEmail(strtolower($message['verb'])) !== FALSE) {
		$new_email = hasEmail(strtolower($message['verb']));
		//echo '<font color=green>Send an email - FROM: ' . $message['from_number'] . ' - MESSAGE: ' . $message['verb']  . ' - NEW EMAIL: ' . $new_email. '</font><br>';
		if(trim($al->email) == '') { //No email in the DB currently
			//echo "<font color=green>ENTER NEW EMAIL</font><BR>";
			$add_alt_email = true;
		}
		elseif(strtolower(trim($al->email)) != strtolower(trim($new_email))) { //New email is not the current, what to do ....
			//echo "<font color=green>ENTER NEW EMAIL AS ALT????</font><BR>";
			$add_alt_email = true;
		}
		else { //current email and new are same, do nothing
			//echo "<font color=red><B>THEY ARE THE SAME....  REC: " . $al->email ." - NEW: " .  $new_email . "  -- DO NOTHING</B></font><BR>";
		}
		//$send_text = true;
	}
	elseif(hasPhone(strtolower($message['verb'])) !== FALSE) {
echo 'Updating new phonee <b>ALUM: ' . $al->first_name . ' ' . $al->last_name . ' @ ' . $al->email . ' (' . $al->id . ')</b><br>';
//echo "<font color=green>Has new phone....</font><BR>";
		$current_phone = preg_replace("/[^0-9]/", "", $al->phone);
		$new_phone = hasPhone(strtolower($message['verb']));
		$to_phone = str_replace('+1', '', $new_phone);
//echo '<font color=green>PHONE: ' . $current_phone  . ' - NEW EMAIL: ' . $new_phone. '</font><br>';
		
		if(strtolower(trim($current_phone)) != strtolower(trim($new_phone))) { //New phone is not the current, what to do ....
			/*
			$wpdb->update( 
				'alumni', 
				array( 
					'alt_phone' => $new_phone,
					'marketing_notes' => '',
				), 
				array( 'id' => $al->id ), 
				array( 
					'%s',
					'%s'
				), 
				array( '%d' ) 
			);
			*/

		}
		$send_text = true;
	}
	else {
		//echo '<font color=blue>Do what? - FROM:' . $message['from_number'] . ' - MESSAGE: ' . $message['verb']  . '</font><br>';
		$others[$mc]['message'] = $message['verb'];
		$others[$mc]['phone'] = $message['from_number'];
		$others[$mc]['alum_id'] = $al->id;
		$others[$mc]['alum_name'] = $al->first_name . ' ' . $al->last_name; 
	}
	
	if($add_alt_email == true) {
echo 'Updating alt email <b>ALUM: ' . $al->first_name . ' ' . $al->last_name . ' @ ' . $al->email . ' (' . $al->id . ')</b><br>';
		$invite_hash = base64_encode(time() . ':' . $al->id . ':new_email=' . $new_email);
		$mail_link = 'https://www.thealum.net/claim-profile/?claim=1&hash=' . $invite_hash . '&r=sms_campaign';
		//$to_email = $new_email;
		$to_email = 'judsonc75@gmail.com';
		$msg = 'Hello ' . $al->first_name . ',<br><br>';
		$msg .= "<p>We recently received an updated email address for you through our text campaign.  Please click the link below to claim your profile and update your information.</p>";
		$msg .= "<p>" . $mail_link . "</p>";
		$mail = $alum->sendAlumMail(array('to' => $to_email, 'message' => $msg, 'subject' => "Please join TheAlum.net"));
		$wpdb->update( 
			'alumni', 
			array( 
				'alt_email' => $new_email,
				'marketing_notes' => '',
			), 
			array( 'id' => $al->id ), 
			array( 
				'%s',
				'%s'
			), 
			array( '%d' ) 
		);
		exit;

		$email_sent++;
	}
	if($send_text == true) {
		//Send claim link ....
		/*
		$invite_hash = base64_encode(time() . ':' . $al->id . ':new_phone=' . $new_email);
		$link = 'https://www.thealum.net/invite-members/?claim=1&hash=' . $invite_hash . '&r=sms_campaign';
		$sms_msg = 'Hello ' . $al->first_name . ', to join theAlum.net. Go to ' . $link . ' to claim your profile';
		$url = 'https://puretxt.com/api/v1/message/?key=867ee14b4869d33eb41e44036537e58d&message=' . urlencode($sms_msg) . '&to=' . $to_phone;
		$postdata = http_build_query(array());
		
		$opts = array('http' =>
		    array(
		        'method'  => 'POST',
		        'header'  => 'Content-type: application/x-www-form-urlencoded',
		        'content' => $postdata
		    )
		);
		
		$context  = stream_context_create($opts);
		
		$results['sms'] = file_get_contents($url, false, $context);
		*/
		$sms_sent++;	
	}
	$mc++;
}

echo $sms_sent . ' texts sent<br>';
echo $email_sent . ' emails sent<br>';
echo $skipped_claimed . ' skipped due to claiming profile<br>';

//output others
if(!empty($others)) {
	echo '<table width="700" cellpadding="5" border="1">';
	foreach($others as $other) {
		echo '<tr><td>' . $other['alum_name'] . ' (' . $other['alum_id'] . ')</td><td>' . $other['phone']. '</td><td>' . $other['message']. '</td></tr>';
	
	}
	echo '</table>';
}

function formatNumber($data) {
	if(  preg_match( '/^\+\d(\d{3})(\d{3})(\d{4})$/', $data,  $matches ) ){
		$result = $matches[1] . '-' .$matches[2] . '-' . $matches[3];
		return $result;
	}
}

function hasPhone($str) {
	$str = preg_replace("/[^0-9]/", "", $str);
	$regex = '/([0-9]{10})/';
	//echo "STR: " . $str . "<BR>";
	
	if(preg_match($regex, $str, $matches)){
		//print_r($matches);
	     return $phone = $matches[0];
	}
	else {
		return false;
	}
}

function hasEmail($str) {
	//$str = str_replace(array(' '), array(''), $email_is[0]);
	$regex = '/[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})/'; 
	if (preg_match($regex, $str, $email_is)) {
 		//echo $email_is[0] . " is a valid email. We can accept it.";
		return $email_is[0] ;
	} 
	else { 
 		//echo $email . " is an invalid email. Please try again.";
		return false;
	}
}

?>
