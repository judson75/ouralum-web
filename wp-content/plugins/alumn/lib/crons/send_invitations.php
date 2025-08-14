<?php
/*
 * Process invitation list sent from API
 * @ J Cooper 01/14/2020
 * 
 * 
 */

if($_SERVER['DOCUMENT_ROOT'] == '') {
	$_SERVER['DOCUMENT_ROOT'] = '/home/bytz5rmw6jgl/public_html';
}
//echo $_SERVER['DOCUMENT_ROOT'];
include_once ($_SERVER['DOCUMENT_ROOT'] . '/wp-config.php');
$host = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHARSET;
$pdo = new PDO($host, DB_USER, DB_PASSWORD);

//Read files ...
$dir = alum_plugin_path . 'lib/tmp/invites';
if ($handle = opendir($dir)) {
   // echo "Directory handle: $handle\n<br>";
    //echo "Entries:\n<br>";
    while (false !== ($entry = readdir($handle))) {
    	if($entry != '.' && $entry != '..') {
//echo "$entry\n<br>";
    		$fc = file_get_contents(alum_plugin_path . 'lib/tmp/invites/' . $entry);
    		//echo $fc . '<br>';
    		$lines = explode("\r\n", $fc);
//echo '<pre>'; print_r($lines); echo '</pre>';
    		foreach($lines as $line) {
    			$s = explode(': ', $line);
				if($s[0] == 'member_id') $alum_id = $s[1];
				if($s[0] == 'user_id') $sender_id = $s[1];
				if($s[0] == 'phone') $phone = $s[1];
				if($s[0] == 'email') $email = $s[1];
    		}
//echo "$alum_id, $sender_id, $phone, $email<BR>";			
			send_invite($alum_id, $sender_id, $phone, $email);
			//delete file
			if (file_exists(alum_plugin_path . 'lib/tmp/invites/' . $entry)) {
			   //echo 'The file ' . alum_plugin_path . 'lib/tmp/invites/' . $entry . ' exists<BR>';
			   unlink(alum_plugin_path . 'lib/tmp/invites/' . $entry);
			} 
    	}
    }

    closedir($handle);
}

$to_email = 'judsonc75@gmail.com';
$msg = "Send invitations script has run<br>";
//$mail = $alum->sendAlumMail(array('to' => $to_email, 'message' => $msg, 'subject' => "Send invitations script has run"));

    				
function send_invite($alum_id, $sender_id, $phone, $email) {
	global $wpdb, $alum;
//echo "$alum_id, $sender_id, $phone, $email <BR>";
	//Get sender info
	$sender = $alum->getUserData($sender_id);
	//Get alumni info 
	$sql = "SELECT first_name, last_name, middle_name, initiation_date, phone, email FROM alumni WHERE id = '$alum_id'";
	$alumni = $wpdb->get_row($sql);
	
	//Get group info 
	$sql = "SELECT g.id, g.group_name, g.group_slug FROM groups g, alum_groups ag WHERE ag.alum_id = '$alum_id' AND ag.group_id = g.id";
	$group = $wpdb->get_row($sql);
	
//echo $sql . "<BR>";
//echo "G<pre>"; print_r($group); echo "</pre>"; 
//exit;	
	
	//Add alt phone and alt email if not same as submitted
	if($alumni->phone != $phone) {
		$wpdb->update( 
			'alumni', 
			array( 
				'alt_phone' => $phone,
			), 
			array( 'id' => $alum_id ), 
			array( 
				'%s',
			), 
			array( '%d' ) 
		);

	}
	if($alumni->email != $email) {
		$wpdb->update( 
			'alumni', 
			array( 
				'alt_email' => $email,
			), 
			array( 'id' => $alum_id ), 
			array( 
				'%s',
			), 
			array( '%d' ) 
		);

	}
	//Create a link 
	$invite_hash = base64_encode(time() . ':' . $alum_id);
	$wpdb->update( 
		'alumni', 
		array( 
			'invite_hash' => $invite_hash,
		), 
		array( 'id' => $alum_id ), 
		array( 
			'%s',
		), 
		array( '%d' ) 
	);
	
	//Check Sent
	$sql = "SELECT id FROM invites_sent WHERE alum_id = '$alum_id' AND sender_id = '$sender_id' AND group_id = '" . $group->id . "'";
	$isent = $wpdb->get_row($sql);
	
	if(empty($isent)) {
		$link = 'https://www.ouralum.com/claim-profile/?claim=1&hash=' . $invite_hash . '';

		//email/text alum a link to claim profile
		$msg = 'Hello ' . $alumni->first_name . ',<br><br>';
		$msg .= "<p>" . $sender->first_name . " " . $sender->last_name . " is requesting that you help update our Kappa Sigma alumni database.  Please click the link below or go to www.OurAlum.com to claim your profile and update your information.</p>";
		$msg .= "<p>" . $link . "&r=email_invite&utc=our_alum_app</p>";
		//echo $msg;
		$sms_msg = 'Hello ' . $alumni->first_name . ', ' . $sender->first_name . ' ' . $sender->last_name . ' has sent you an invitation to join OurAlum. Go to ' . $link . '&r=sms_invite&utc=our_alum_app to claim your profile';
		$wpdb->insert( 
			'invites_sent', 
				array( 
					'sender_id' => $sender_id,
					'group_id' => $group->id,
					'alum_id' => $alum_id,
					'date_sent' => current_time('mysql')
				), 
				array( 
					'%d',
					'%d',
					'%d', 
					'%s'
				) 
			);
//$wpdb->show_errors();
//$wpdb->print_error();
		$to_phone = preg_replace("/[^0-9]/", "", $phone);
//$to_phone = '6015198072';
		//$to_phone = '6015066335';
		$to_email = $email;
//$to_email = 'judsonc75@gmail.com';
		//$to_email = 'mikejr@malouf.law';
		$mail = $alum->sendAlumMail(array('to' => $to_email, 'message' => $msg, 'subject' => "Someone has invited you to join OurAlum.com"));
		//Text..
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
		$results['resp'] = 'success';
		echo json_encode($results);
	}
	else {
		echo "INVITATIoN SENT ALREADY";
	}
//	exit;

}

?>