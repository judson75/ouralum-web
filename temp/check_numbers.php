<?php

include_once $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php';

//Get phones from the OLD table
$sql = "SELECT id, phone, alt_phone, first_name, last_name FROM alumni_backup02122019 WHERE phone != '' OR alt_phone != ''";
$users = $wpdb->get_results($sql);
$uc = 0;
$uu = 0;
foreach ( $users as $user ) {
//echo 'USER:<pre>'; print_r($user); echo '</pre>';
	//See whats in the current table and META
	$sql2 = "SELECT a.phone, a.alt_phone, m.meta_value AS mphone, a.first_name, a.last_name FROM alumni a LEFT JOIN wp_usermeta m ON (m.user_id = a.user_id AND m.meta_key LIKE '%phone%' AND m.meta_key NOT LIKE '%privacy%' AND m.meta_value != '' AND m.meta_value != 0 ) WHERE a.id = '".$user->id."'";
	$curr = $wpdb->get_row($sql2);
//echo 'CURR:<pre>'; print_r($curr); echo '</pre>';
	if(
	   ($user->phone != '' OR $user->alt_phone != '')
	   AND ($curr->phone == '' AND $curr->mphone == '' AND $curr->alt_phone == '')
	   AND ($user->first_name == $curr->first_name AND $user->last_name == $curr->last_name )) {
//echo '<font color=red>This should be updated</font><br>';

		 $wpdb->update( 
        	'alumni', 
        	array( 
        		'phone' => $user->phone,
        		'alt_phone' => $user->alt_phone 
        	), 
        	array( 'id' => $user->id ), 
        	array( 
        		'%s',	// value1
        		'%s'	// value2
        	), 
        	array( '%d' ) 
         );

		 $uu++;
	}
	else {
	//	 echo '<font color=green>This should NOT be updated</font><br>';
	}
	
	if($curr->phone != '' OR $curr->mphone != '' OR $curr->alt_phone != '') {
		 echo '<font color=green>This MAY BE NEED updated</font><br>';
	}
	$uc++;
}

echo $uc . ' users looked at<BR>';
echo $uu . ' users updated<BR>';
exit;

/*
//$sql = "SELECT * FROM wp_users";
$sql = "SELECT * FROM wp_usermeta WHERE meta_key LIKE '%phone%' AND meta_key NOT LIKE '%privacy%' AND meta_value != '' AND meta_value != 0";
$users = $wpdb->get_results($sql);

foreach ( $users as $user ) {
	//echo '<pre>'; print_r($user); echo '</pre>';
	$user_array[$user->user_id][$user->meta_key] = $user->meta_value;
	//Get default number
	$sql = "SELECT phone, first_name, last_name FROM alumni WHERE user_id = '" . $user->user_id . "'";
	$phone = $wpdb->get_row($sql);
	//echo '<pre>'; print_r($phone); echo '</pre>';
	$user_array[$user->user_id]['phone'] = $phone->phone;
	$user_array[$user->user_id]['first_name'] = $phone->first_name;
	$user_array[$user->user_id]['last_name'] = $phone->last_name;
}


echo '<pre>'; print_r($user_array); echo '</pre>';
exit;


foreach($user_array as $user_id => $user) {
	//Format numbers to match...
	$this_phone = preg_replace("/[^0-9]/", "", $user['phone']); 
	$this_mobile = preg_replace("/[^0-9]/", "", $user['_alum_mobile_phone']); 
	$this_home = preg_replace("/[^0-9]/", "", $user['_alum_home_phone']);
	//echo "ALUMNI PHONE: $this_phone - MOBILE: $this_mobile - HOME: $this_home<br>";
	
	//If mobile and phone are the same, do nothing? Delete meta field?
	echo '<b>' . $user['first_name'] . ' ' . $user['last_name'] . ' (' . $user_id . ')</b><br>';
//exit;
	if($this_mobile == '') {
		echo '<h1><font color=red>NO MOBILE PHONE</font></h1>';
	}
	if($this_phone == '' && $this_mobile != '') {
		echo '<font color=blue>Copy Mobile to Alumni phone, no Alumni phone</font><br>';
		
		$wpdb->update( 
			'alumni', 
			array( 
				'phone' => $this_mobile,
			), 
			array( 'user_id' => $user_id), 
			array( 
				'%s'
			), 
			array( '%d' ) 
		);
		
		delete_user_meta( $user_id, '_alum_mobile_phone'); 
	}
	elseif($this_phone == $this_mobile) {
		echo '<font color=green>Mobile is same as Alumni phone</font><br>';
		delete_user_meta( $user_id, '_alum_mobile_phone'); 
	}
	elseif($this_phone == $this_home) {
		echo '<font color=blue>Alumni phone is same as home. Copy mobile to phone</font><br>';
		$wpdb->update( 
			'alumni', 
			array( 
				'phone' => $this_mobile,
			), 
			array( 'user_id' => $user_id), 
			array( 
				'%s'
			), 
			array( '%d' ) 
		);
		
		delete_user_meta( $user_id, '_alum_mobile_phone'); 
	}
	elseif($this_home != '') {
		echo '<font color=purple>Alumni phone is not same as home or mobile. Home is not empty</font><br>';
		$wpdb->update( 
			'alumni', 
			array( 
				'phone' => $this_mobile,
			), 
			array( 'user_id' => $user_id), 
			array( 
				'%s'
			), 
			array( '%d' ) 
		);
		
		delete_user_meta( $user_id, '_alum_mobile_phone'); 
	}
	else {
		echo '<font color=red>Mobile is NOT same as Alumni phone. Copy phone to home, and mobile to phone</font><br>';
		$wpdb->update( 
			'alumni', 
			array( 
				'phone' => $this_mobile,
			), 
			array( 'user_id' => $user_id), 
			array( 
				'%s'
			), 
			array( '%d' ) 
		);
		
		update_user_meta( $user_id, '_alum_home_phone', $this_phone);
		
		delete_user_meta( $user_id, '_alum_mobile_phone'); 
	}
	
}
*/
?>