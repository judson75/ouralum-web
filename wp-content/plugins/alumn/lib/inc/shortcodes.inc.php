<?php
if (!session_id()) {
	session_start();
}
    
function alum_login_func( $atts ) {
	global $wpdb;
    $a = shortcode_atts( array(
        'foo' => 'something',
        'bar' => 'something else',
    ), $atts );
	$output = '';
	//if logged in forward to dashboard
	if(is_user_logged_in()) {
		$url = get_bloginfo('url') . '/profile';
	    echo '<script>window.location = "' . $url . '"</script>';
		exit;
	}
	//print_r($_SESSION);
	/* Form */
	if(isset($_SESSION['alum_message'])) {
		$output .= '<div class="alert alert-' . $_SESSION['alum_message_type']  . '">' . $_SESSION['alum_message'] . '</div>';
		unset($_SESSION['alum_message']);
		unset($_SESSION['alum_message_type']);
	}
	if(isset($_GET['lost_password'])) {
		$output .= '<form method="post" id="lost-password-form">';
		$output .= '<p>Enter your email address below. We will send you a link to reset your password</p>';
		$output .= '<input type="hidden" name="reset_user" value="1">';
		$output .= '<div class="form-group">';
		$output .= '<label for="login_email"></label>';
		$output .= '<input type="text" class="form-input" placeholder="Email Address" name="user_login" id="login_email" value="">';
		$output .= '</div>';
		$output .= '<div class="form-group">';
		$output .= '<button type="button" class="lostPassBtn btn btn-primary">Send Me Reset Link</button>';
		$output .= '</div>';
		$output .= '<form>';

	}
	elseif(isset($_GET['reset_password'])) {
		$ut = $_GET['ut'];
		$b = base64_decode($ut);
		$bs = explode('::', $b);
		$email = trim($bs[0]);
		$hash = trim($bs[1]);
		//echo "HAS: $hash<br>";
		//echo "Email: $email";
		$sql = "SELECT user_activation_key FROM wp_users WHERE user_email = '$email'";
		//echo "SQL: $sql<BR>";
		$h = $wpdb->get_row($sql);
		//print_r($h);
		if($h->user_activation_key != $hash) {
			$output .= 'We\'re Sorry. The link you accessed is not valid';
		}
		else {
			$output .= '<p>Enter your new password below</p>';
			$output .= '<form method="post" id="new-password-form">';
			$output .= '<input type="hidden" name="reset_password" value="1">';
			$output .= '<input type="hidden" name="user_login" value="' . $email. '">';
			$output .= '<div class="form-group">';
			$output .= '<label for="password"></label>';
			$output .= '<input type="password" class="form-input" placeholder="Password" name="password" id="password" value="">';
			$output .= '</div>';
			$output .= '<div class="form-group">';
			$output .= '<button type="button" class="resetPassBtn">Reset Password</button>';
			$output .= '</div>';
			$output .= '<form>';
		}
	}
	else {
		$output .= '<form method="post" id="login-form">';
		$output .= '<input type="hidden" name="login_user" value="1">';
		$output .= '<div class="form-group">';
		$output .= '<label for="login_email"></label>';
		$output .= '<input type="text" class="form-input" placeholder="Email Address" name="user_login" id="login_email" value="">';
		$output .= '</div>';
		$output .= '<div class="form-group">';
		$output .= '<label for="login_password"></label>';
		$output .= '<input type="password" class="form-input" placeholder="Password" name="user_password" id="login_password" value="">';
		$output .= '</div>';
		$output .= '<div class="form-group">';
		$output .= '<input id="box1" type="checkbox" class="checkbox" name="remember_me" value="1" /><label for="box1">Remember Me</label>';
		$output .= '</div>';
		$output .= '<div class="form-group">';
		$output .= '<button type="button" class="btn btn-primary loginBtn">Login</button>';
		$output .= '</div>';
		$output .= '<a href="' . get_bloginfo('url') . '/login/?lost_password" style="float: right;margin-right: 0; margin-top: -50px; font-size: 12px;">Lost Password</a>';
		//$output .= '<h4>Not a member? <a href="' . get_bloginfo('url') . '/register">Click here to register</a></h4>';
		//$output .= '<h4><a href="' . get_bloginf('url') . '/claim-profile">See if you are in our database</a></h4>';
		$output .= '<form>';
	}
    return $output;
}
add_shortcode('alum_login', 'alum_login_func');

function alum_register_func( $atts ) {
	global $alum, $wpdb;
    $a = shortcode_atts( array(
        'foo' => 'something',
        'bar' => 'something else',
    ), $atts );
	$output = '';
 	if(is_user_logged_in()) {
		$url = get_bloginfo('url') . '/profile';
	    echo '<script>window.location = "' . $url . '"</script>';
		exit;
	}
	/* Form */
	//echo 'S:<pre>'; print_r($_SESSION); echo '</pre>'; 
	//echo 'P:<pre>'; print_r($_POST); echo '</pre>'; 
	if(isset($_SESSION['alum_user_register']) && !isset($_POST['alum_user_register'])) {
		$_POST['alum_user_register'] = $_SESSION['alum_user_register'];
	}
	if(isset($_SESSION['alum_user_register']) && !isset($_POST['claim_profile'])) {
		$_POST['claim_profile'] = $_SESSION['alum_user_register'];
	}
	if(isset($_SESSION['alum_profile_id']) && !isset($_POST['alum_profile_id']) && $_POST['alum_profile_id'] != '') {
		$_POST['alum_profile_id'] = $_SESSION['alum_profile_id'];
	}

	if(isset($_SESSION['alum_message'])) {
		$output .= '<div class="alert alert-' . $_SESSION['alum_message_type']  . '">' . $_SESSION['alum_message'] . '</div>';
		unset($_SESSION['alum_message']);
		unset($_SESSION['alum_message_type']);
	}
//echo '<pre>'; print_r($_SESSION); echo '</pre>';
//echo '<pre>'; print_r($_POST); echo '</pre>';
	
	if($_POST['alum_profile_id'] != '') {
		$sql = "SELECT email FROM alumni WHERE id = '" . $_POST['alum_profile_id']. "' LIMIT 1";
		$p = $wpdb->get_row($sql);
//echo '<pre>'; print_r($p); echo '</pre>';
	}
	// 
	$colleges = $alum->getColleges();
	$frats = $alum->getFraternitys();
	$output .= '<form method="post" id="register-form">';
	$output .= '<input type="hidden" name="register_user" value="1">';
	if(isset($_POST['alum_user_register'])) {
		$output .= '<input type="hidden" name="claim_profile" value="' . $_POST['claim_profile']. '">';
		$output .= '<input type="hidden" name="alum_profile_id" value="' . $_POST['alum_profile_id']. '">';	
	}
	if($_SESSION['alum_new_email'] != '') {
		$output .= '<input type="hidden" name="update_email" value="' . $_SESSION['alum_new_email'] . '">';
		$p->email = $_SESSION['alum_new_email'];
	}
	if($_SESSION['alum_new_phone'] != '') {
		$output .= '<input type="hidden" name="update_phone" value="' . $_SESSION['alum_new_phone'] . '">';
	}
	$output .= '<div class="form-group">';
	$output .= '<label for="login_email"></label>';
	$output .= '<input type="text" class="form-input" placeholder="Email Address" name="user_login" id="login_email" value="' . $p->email . '">';
	$output .= '</div>';
	//skip these fields if claiming listing
	if(!isset($_POST['alum_user_register'])) {
		$output .= '<div class="form-group">';
		$output .= '<label for="first_name"></label>';
		$output .= '<input type="text" class="form-input" placeholder="First Name" name="first_name" id="first_name" value="">';
		$output .= '</div>';

		$output .= '<div class="form-group">';
		$output .= '<label for="middle_name"></label>';
		$output .= '<input type="text" class="form-input" placeholder="Middle Name" name="middle_name" id="middle_name" value="">';
		$output .= '</div>';

		$output .= '<div class="form-group">';
		$output .= '<label for="last_name"></label>';
		$output .= '<input type="text" class="form-input" placeholder="Last Name" name="last_name" id="last_name" value="">
		<div class="helper">Last name only, do not enter suffix</div>';
		$output .= '</div>';
	}

	$output .= '<div class="form-group">';
	$output .= '<label for="login_password"></label>';
	$output .= '<input type="password" class="form-input" placeholder="Password" name="user_password" id="login_password" value="">';
	$output .= '</div>';
	$output .= '<div class="form-group">';
	$output .= '<label for="login_password2"></label>';
	$output .= '<input type="password" class="form-input" placeholder="Password Again" name="user_password2" id="login_password2" value="">';
	$output .= '</div>';
	//skip these fields if claiming listing
	if(!isset($_POST['alum_user_register'])) {
		$output .= '<div class="form-group">';
		$output .= '<label for="college_name"></label>';
		$output .= '<select class="form-input" name="college_name" id="college_name">';
		$output .= '<option value="">Choose College</option>';
		foreach($colleges as $college) {
			$output .= '<option value="' . $college . '">' . $college . '</option>';
		}
		$output .= '</select>';
		$output .= '</div>';
		$output .= '<div class="form-group">';
		$output .= '<label for="frat_name"></label>';
		$output .= '<select class="form-input" name="frat_name" id="frat_name">';
		$output .= '<option value="">Choose Fraternity/Sorority</option>';
		foreach($frats as $frat) {
			$output .= '<option value="' . $frat->fraternity . '">' . $frat->fraternity . '</option>';
		}
		$output .= '</select>';
		$output .= '</div>';

		$output .= '<div class="form-group">';
		$output .= '<label for="last_name"></label>';
		$output .= '<input type="text" class="form-input" placeholder="Initiation Year" name="initiation_date" id="initiation_date" value="" maxlength="4">';
		$output .= '</div>';
	}
	/*
	$output .= '<div class="form-group">';
	$output .= '<label for="last_name"></label>';
	$output .= '<input type="text" class="input" placeholder="Initiation Date" name="initiation_date" id="init_date" value="">';
	$output .= '</div>';
	*/
	$output .= '<div class="form-group">';
	$output .= '<button type="button" class="btn btn-primary registerBtn">Register Me</button>';
	$output .= '</div>';
	$output .= '<h4>Already a member? <a href="' . get_bloginfo('url') . '/login">Click here to login</a></h4>';
	$output .= '<hr>';
	$output .= '<h3><a href="' . get_bloginfo('url') . '/claim-profile">See if you are in our database</a></h3>';
	$output .= '<form>';
	
    return $output;
}
add_shortcode( 'alum_register', 'alum_register_func' );

function alum_claim_profile_func() {
	global $wpdb, $alum;
	if($_REQUEST['claim'] == 1 && $_REQUEST['hash'] != '') {
		$pt = 'Claim Your Profile';
		$hash = base64_decode($_REQUEST['hash']);
		$hs = explode(':', $hash);
		//print_r($hs);
		$timestamp = $hs[0]; //later we time limit them
		$alum_id = $hs[1];
		$_SESSION['alum_profile_id'] = $alum_id;
		$_SESSION['alum_user_register'] = true;
		if(count($hs) > 2) {
			//third param is new phone or new email
			$tp = explode('=', $hs[2]);
			if($tp[0] == 'new_email') {
				$_SESSION['alum_update_email'] = true;
				$_SESSION['alum_new_email'] = $tp[1];
			}
			elseif($tp[0] == 'new_phone') {
				$_SESSION['alum_update_phone'] = true;
				$_SESSION['alum_new_phone'] = $tp[1];
			}
		}
		$sql = "SELECT first_name, last_name, middle_name, pledge_class, fraternity, initiation_date, address, city, state, zipcode, email, user_id, college FROM alumni WHERE id = '$alum_id'";
		$profile = $wpdb->get_row($sql);

	}

	$output = '';
	$output .= '<form action="#" method="post" id="claim-form">';
	if($_SESSION['alum_new_email'] != '') {
		$output .= '<input type="hidden" name="update_email" value="' . $_SESSION['alum_new_email']. '">';
	}
	if($_SESSION['alum_new_phone'] != '') {
		$output .= '<input type="hidden" name="update_phone" value="' . $_SESSION['alum_new_phone']. '">';
	}
	$output .= '<div class="form-group">
						<label>First Name:</label><br>
						<input name="first_name" placeholder="" required="" type="text" class="form-input" value="' . $profile->first_name . '">
					</div>
					<div class="form-group">
						<label>Middle Initial:</label><br>
						<input name="middle_name" placeholder="" required="" type="text" class="form-input" value="' . $profile->middle_name . '">
					</div>
					<div class="form-group">
						<label>Last Name:</label><br>
						<input name="last_name" placeholder="" required="" type="text" class="form-input" value=" ' . $profile->last_name . '">
						<small>Last name only, do not enter suffix</small>
					</div>
					<div class="form-group">	
						<label>Initiation Year (1999)</label><br>
						<input name="initiation_date" placeholder="" required="" type="text" class="form-input" maxlength="4">
					</div>
					<div class="form-group">
				  		<button type="button" class="sendClaimProfile" style="margin: 0;">Find My Profile</button>
				  	</div>
				</form>';
	return $output;
}

add_shortcode( 'alum_claim_profile', 'alum_claim_profile_func' );

function alum_profile_found_func() {
	global $wpdb, $alum;
	//echo '<pre>'; print_r($_SESSION); echo '</pre>'; 
	$id = $_SESSION['alum_profile_id'];
	$output = '';
	//
	
	if($id != '') {
		$sql = "SELECT first_name, last_name, middle_name, pledge_class, fraternity, initiation_date, address, city, state, zipcode, email, user_id, college FROM alumni WHERE id = '$id'";
		$profile = $wpdb->get_row($sql);
		//print_r($profile);
		$output .= '<h3>We Found A Matching Profile</h3>';
		$output .= '<p></p>';
		$output .= '<h4>' . $profile->first_name . ' ' . $profile->middle_name . ' ' . $profile->last_name. '</h4>';
		//$output .= '<p>' . $profile->first_name . ' ' . $profile->middle_name . ' ' . $profile->las_name. '<br>';
		if($profile->pledge_class != '') {
			$output .= '<p>Pledge Class: <b>' . $profile->pledge_class . '</b></p>';
		}
		$output .= '<p>Initiation Date: <b>' . date("M n, Y", strtotime($profile->initiation_date)) . '</b></p>';
		$output .= '<form id="found-profile-form" method="post" action="' . get_bloginfo('url') . '/register">';
		$output .= '<input type="hidden" name="alum_profile_id" value="' . $_SESSION['alum_profile_id'] . '">';	
		if($_SESSION['alum_user_register'] == true) {
			$output .= '<input type="hidden" name="alum_user_register" value="true">';
		}
		if(isset($_SESSION['alum_user_data'])) {
			$output .= '<input type="hidden" name="register_user" value="1">';
			foreach($_SESSION['alum_user_data'] as $f => $v) {
				$output .= '<input type="hidden" name="' . $f. '" value="' . $v . '">';
			}
		}
		if($_SESSION['alum_new_email'] != '') {
			$output .= '<input type="hidden" name="update_email" value="' . $_SESSION['alum_new_email']. '">';
		}
		if($_SESSION['alum_new_phone'] != '') {
			$output .= '<input type="hidden" name="update_phone" value="' . $_SESSION['alum_new_phone']. '">';
		}
		$output .= '<div class="form-group">';
		$output .= '<input id="box1" type="radio" class="checkbox" name="claim_profile" value="1" /><label for="box1">This is me, <b><u>Claim it</u></b></label>';
		$output .= '</div>';
		$output .= '<div class="form-group">';
		$output .= '<input id="box2" type="radio" class="checkbox" name="claim_profile" value="0" /><label for="box2">This is not me, <b><u>proceed without claiming</u></b></label>';
		$output .= '</div>';
		$output .= '<div class="form-group">';
		$output .= '<button type="button" class="btn btn-primary pr123" style="margin: 0;">Proceed to Register</button>';
		$output .= '</div>';
		//$output .= '<button type="button" class="btn btn-sm btn-primary claimFoundProfileBtn" style="display: inline-block; margin: 0;">This is me, Claim it</button> <button type="button" class="btn btn-sm btn-danger dontclaimFoundProfileBtn" style="display: inline-block; margin: 0;">This is not me, proceed without claiming</button>';
		$output .= '</form>';
	}
	return $output;
}
add_shortcode('alum_profile_found', 'alum_profile_found_func');

function alum_profile_func() {
	global $alum, $wpdb;
	//print_r($_GET);
	$is_owner = false;
	$non_member = false;
	$current_user = wp_get_current_user();
	$output = '';
	$profile_user_name = get_query_var('user_name');
	if($_GET['gid'] != '') {
		$output .= '<input type="hidden" name="group_id" value="' . $_GET['gid'] . '">';
	}
	//echo '<pre>'; print_r($current_user);echo '</pre>';
	//if viewing profile, get user ID
	//echo $profile_user_name;
	if(isset($profile_user_name) && $profile_user_name != '') {
		$user_name = trim($profile_user_name, '/');
		//echo $user_name;
		if(is_numeric($user_name)) { //This profile does not belong to member
			$alumni_id = $user_name;
			//echo "AID: $alumni_id";
		}
		else {
			$user_name = trim($profile_user_name, '/');
			//echo "USER: $user_name";
			//$user = get_user_by('user_nicename', trim($user_name));
			$user = $wpdb->get_row( $wpdb->prepare(
				"SELECT `ID`, display_name, user_nicename, user_email FROM $wpdb->users WHERE `user_nicename` = %s", $user_name
			));
			//echo 'User is ' . $user->ID . '';
			if($user->ID != '') {
				$user_id = $user->ID;
			}
			if($user->ID == $current_user->ID) {
				$is_owner = true;
			}
			//$current_user->data->user_nicename
			$user_nicename = $user->user_nicename;
			$user_displayname = $user->display_name;
			//title_callback($user_displayname);
		}
	}
	else {
		//user is viewing their profile
		if($current_user->ID == '' || $current_user->ID == 0) {
			//Redirect them out... or display nothing
			$output = '<h3>Profile Unavailable</h3>';
			return  $output;
		}
		$user_id = $current_user->ID;
		$is_owner = true;
		$user_nicename = $current_user->data->user_nicename;
		$user_displayname = $current_user->data->display_name;
	}
	//echo "UN: $user_nicename<BR>";
	//print_r($_FILES);
	//echo "USER ID: $user_id";
	//Get user data
	if($user_id != '') {
		$user_data = $alum->getUserData($user_id);
	}
	elseif($alumni_id != '') {
		//echo "AID3: $alumni_id";
		//$user_data = $alum->getUserDataByID($alumni_id);
		//echo 'UDATA:<pre>'; print_r($user_data);echo '</pre>';
		$sql = "SELECT * FROM alumni WHERE id = '$alumni_id' LIMIT 1";
		//echo $sql;
		$user_data = $wpdb->get_row($sql);
		$avatar = get_user_meta($user_data->user_id, '_alum_avatar', true);
		if($avatar != '') {
			$user_data->avatar = $avatar;
		}

		//echo 'UDATA:<pre>'; print_r($user_data);echo '</pre>';
		$non_member = true;
	}
	if($user_displayname == '') {
		$user_displayname = $user_data->first_name;
		if($user_data->middle_name !== '') {
			$user_displayname .= ' ' . substr($user_data->middle_name, 0, 1) . '.';
		}
		$user_displayname .= ' ' . $user_data->last_name;
	}
	
	//Get User Settings
	if($non_member != true) {
		$user_settings = $alum->getSettings($user_id);
	}
	//echo '<pre>'; print_r($user_data);echo '</pre>';
	//echo '<pre>'; print_r($user_settings);echo '</pre>'; 
	$view = get_query_var('view');
	if($_GET['view'] == 'settings' || $view == 'settings') {
		$email_setting = ($user_settings['email'] != 1) ? '' : ' checked';
		$phone_setting = ($user_settings['phone'] != 1) ? '' : ' checked';
		$address_setting = ($user_settings['address'] != 1) ? '' : ' checked';
		$city_setting = ($user_settings['city'] != 1) ? '' : ' checked';
		$state_setting = ($user_settings['state'] != 1) ? '' : ' checked';
		$zipcode_setting = ($user_settings['zipcode'] != 1) ? '' : ' checked';
		$optin_setting = ($user_settings['optin'] != 1) ? '' : ' checked';
		$blank_password = '';
		$event_notifications_setting = ($user_settings['event_notifications_setting'] != 1) ? '' : ' checked';
		//echo "EVENT: " .  $event_notifications_setting. "<BR>";
		$group_messages_notifications_setting = ($user_settings['group_messages_notifications_setting'] != 1) ? '' : ' checked';
		$pledge_messages_notifications_setting = ($user_settings['pledge_messages_notifications_setting'] != 1) ? '' : ' checked';
		$output .= '<div class="wrapper" style="margin-top: 35px;">';
		$output .= '<h2>Edit Settings</h3>';
		$output .= '<p>You can update your settings below</p>';
		$output .= '<form id="settings-update-frm" method="post" enctype="multipart/form-data">';
		$output .= '<input type="hidden" name="user_id" value="' . $user_id . '">';
		$output .= '<input type="hidden" name="save_settings" value="1">';
		$output .= '<div class="form-group"><label for="settings_password">Password:</label> <input type="password" id="settings_password" name="password" class="input" value="'.$blank_password.'" readonly autocomplete="off"><br><small>Leave blank to keep your password. <b>Changing it will force you to log back in.</b></small></div>';
		$output .= '<hr>';
		$output .= '<h3>Privacy</h3>';
		$output .= '<table id="member-privacy-settings">';
		$output .= '<thead><tr><th>Setting</th><th>Hide/Show</th></tr></thead>';
		$output .= '<tbody>';
		$output .= '<tr><td>Show Email</td><td align="center">
					  <div class="switch">
			            <input id="cmn-toggle-1" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" name="email_privacy" value="1" ' . $email_setting . '>
			            <label for="cmn-toggle-1"></label>
			          </div>
			        </td></tr>';
		$output .= '<tr><td>Show Phone Number</td><td align="center">
					  <div class="switch">
			            <input id="cmn-toggle-2" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" name="phone_privacy" value="1" ' . $phone_setting . '>
			            <label for="cmn-toggle-2"></label>
			          </div>
			        </td></tr>';
		$output .= '<tr><td>Show Street Address</td><td align="center">
					  <div class="switch">
			            <input id="cmn-toggle-3" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" name="address_privacy" value="1" ' . $address_setting . '>
			            <label for="cmn-toggle-3"></label>
			          </div>
			        </td></tr>';
		$output .= '<tr><td>Show City</td><td align="center">
					  <div class="switch">
			            <input id="cmn-toggle-4" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" name="city_privacy" value="1" ' . $city_setting . '>
			            <label for="cmn-toggle-4"></label>
			          </div>
			        </td></tr>';
		$output .= '<tr><td>Show State</td><td align="center">
					  <div class="switch">
			            <input id="cmn-toggle-5" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" name="state_privacy" value="1" ' . $state_setting . '>
			            <label for="cmn-toggle-5"></label>
			          </div>
			        </td></tr>';
		$output .= '<tr><td>Show Zipcode</td><td align="center">
					  <div class="switch">
			            <input id="cmn-toggle-6" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" name="zipcode_privacy" value="1" ' . $zipcode_setting . '>
			            <label for="cmn-toggle-6"></label>
			          </div>
			        </td></tr>';
		$output .= '</tbody>';
		$output .= '</table>';
		
		$output .= '<hr>';
		$output .= '<h3>Notifications</h3>';
		$output .= '<table id="member-privacy-settings">';
		$output .= '<thead><tr><th>Notification</th><th>Off/On</th></tr></thead>';
		$output .= '<tbody>';
		$output .= '<tr><td>Receive Event Notifications</td><td align="center">
					  <div class="switch">
			            <input id="cmn-toggle-7" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" name="event_notifications" value="1" ' . $event_notifications_setting . '>
			            <label for="cmn-toggle-7"></label>
			          </div>
			        </td></tr>';
		$output .= '<tr><td>Receive Newsletter</td><td align="center">
					  <div class="switch">
			            <input id="cmn-toggle-8" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" name="optin" value="1" ' . $optin_setting . '>
			            <label for="cmn-toggle-8"></label>
			          </div>
			        </td></tr>';
		$output .= '<tr><td>Receive Group Messages</td><td align="center">
					  <div class="switch">
			            <input id="cmn-toggle-9" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" name="group_messages_notifications" value="1" ' . $group_messages_notifications_setting . '>
			            <label for="cmn-toggle-9"></label>
			          </div>
			        </td></tr>';
		$output .= '<tr><td>Receive Pledge Class Messages</td><td align="center">
					  <div class="switch">
			            <input id="cmn-toggle-10" class="cmn-toggle cmn-toggle-round-flat" type="checkbox" name="pledge_messages_notifications" value="1" ' . $pledge_messages_notifications_setting . '>
			            <label for="cmn-toggle-10"></label>
			          </div>
			        </td></tr>';
		$output .= '</tbody>';
		$output .= '</table>';
		$output .= '<div class="form-group"><button type="submit" class="updateSettingsBtn btn btn-primary btn-sm ">Save Changes</button> <button type="button" class="cancelUserDataBtn btn btn-sm btn-danger">Cancel</button></div>';
		$output .= '</form>';
		$output .= '</div>';
		
		
	}
	elseif($_GET['view'] == 'edit' || $view == 'edit') {
		$output .= '<div class="wrapper" style="margin-top: 35px;">';
		$output .= '<h2>Edit Profile</h3>';
		$output .= '<div id="edit-profile-wrapper">';
		$output .= '<input type="hidden" name="user_id" value="' . $user_id . '">';
		
		$output .= '<input type="hidden" name="group_id" value="' . $_GET['group_id'] . '">';
		
		$output .= '<fieldset><legend>Personal Info</legend>';
		$output .= '<div class="form-group"><label>Nickname:</label><input name="display_name" value="' . $user_displayname . '" class="edit-user-data form-input" placeholder="Display Name"></div>';
		//$output .= '<div class="form-group"><label>Username:</label><input name="user_name" value="' . $user_nicename . '" class="edit-user-data form-input" placeholder="@username">
		//				<div class="info-alert">This is the @username for your public profile. Your public profile can be viewed at ' . get_bloginfo('url') . '/m/username.</div>
		//			</div>';
		$output .= '<div class="form-group"><label>Birthdate:</label><input type="date" name="birthdate" value="' . $user_data->birthdate . '" id="birthdate" class="edit-user-data form-input" placeholder=""></div>';
		$output .= '<div class="form-group"><label>Spouse Name:</label><input name="spouse_name" value="' . $user_data->spouse_name . '" class="edit-user-data form-input" placeholder="Spouse Name"></div>';

		$output .= '<div class="form-group"><label>Email Address:</label><input name="user_email" value="' . $user_data->email . '" class="edit-user-data form-input" placeholder="Email Address">
						<div class="info-alert">Changing your email address will force you to log back in using the new email address</div>
					</div>';
		//$output .= '<div class="form-group"><label>Phone Number:</label><input name="phone" value="' . $user_data->phone . '" class="edit-user-data form-input" placeholder="Phone Number"></div>';
		$output .= '<div class="form-group"><label>Mobile Phone:</label><input name="mobile_phone" value="' . $user_data->mobile_phone . '" class="edit-user-data form-input" placeholder=""></div>';
		$output .= '<div class="form-group"><label>Home Phone:</label><input name="home_phone" value="' . $user_data->home_phone . '" class="edit-user-data form-input" placeholder=""></div>';
		$output .= '<div class="form-group"><label>Home Address:</label><input name="address" value="' . $user_data->address . '" class="edit-user-data form-input" placeholder="Street Address"></div>';
		$output .= '<div class="form-group"><label>City:</label><input name="city" value="' . $user_data->city . '" class="edit-user-data form-input" placeholder="City"></div>';
		$output .= '<div class="form-group"><label>State:</label><select name="state" class="edit-user-data form-input">';
		$output .= '<option value="">Choose State</option>';
		foreach($alum->states as $state_abbr=>$state_name) {
			$output .= '<option value="' . $state_abbr. '"';
			if($user_data->state == $state_abbr) {
				$output .= ' selected';
			}
			$output .= '>' . $state_name. '</option>';
		}
		$output .= '</select></div>';
		$output .= '<div class="form-group"><label>Zip Code:</label><input name="zipcode" value="' . $user_data->zipcode . '" class="edit-user-data form-input" placeholder="Zip Code"></div>';
	    
	
		$output .= '</fieldset>';
		
					
		$output .= '<fieldset><legend>Business Info</legend>';
		$output .= '<div class="form-group"><label>Occupation:</label>';
		//				<input name="occupation" value="' . $user_data->occupation . '" class="edit-user-data form-input" placeholder="Occupation">
		$output .= '<select name="occupation" class="form-input">
					<option value="">Choose Occupation</option>';
		ksort($alum->professions);
		foreach($alum->professions as $profession=>$profession2) {
			$output .= '<option value="' . $profession. '"';
			if($user_data->occupation == $profession) {
				$output .= ' selected';
			}
			$output .= '>' . $profession. '</option>';
		}				
		$output .= '</select></div>';
		//print_r($user_data);
		$occ2_disp = ($user_data->occupation2 != '') ? 'block' : 'none';
		//echo $occ2_disp;
		$output .= '<div class="form-group occ2" data-display="' . $occ2_disp. '"><label>Occupation Category:</label>';
		//				<input name="occupation" value="' . $user_data->occupation . '" class="edit-user-data form-input" placeholder="Occupation">
		$output .= '<select name="occupation2" class="form-input">
					<option value="">Choose Occupation Category</option>';
		if($user_data->occupation2 != '') {			
			foreach($alum->professions as $profession=>$profession2) {
				if($user_data->occupation == $profession) {
					foreach($profession2 as $profession2_title) {
						$output .= '<option value="' . $profession2_title. '"';
						if($user_data->occupation2 == $profession2_title) {
							$output .= ' selected';
						}
						$output .= '>' . $profession2_title . '</option>';
					}
				}
			}
		}				
		$output .= '</select></div>';
		
		$output .= '<div class="form-group"><label>Occupational Description:</label><textarea name="occupation_description" class="edit-user-data form-input" placeholder="Describe your occupation">' . $user_data->occupation_description . '</textarea></div>';


		$output .= '<div class="form-group"><label>Name of Company:</label><input name="employer_name" value="' . $user_data->employer_name . '" class="edit-user-data form-input" placeholder="Employer Name"></div>';
		$output .= '<div class="form-group"><label>Company Address:</label><input name="employer_address" value="' . $user_data->employer_address . '" class="edit-user-data form-input" placeholder="Employer Address"></div>';
		$output .= '<div class="form-group"><label>Work Phone:</label><input name="work_phone" value="' . $user_data->work_phone . '" class="edit-user-data form-input" placeholder=""></div>';
		$output .= '</fieldset>';			
		$output .= '<a name="promote_business"></a>';
		$output .= '<fieldset><legend>Promote Your Business</legend>';
		
		$output .= '<div class="form-group">
						<label for="link_url">Upload Ad:</label>
						<input name="ad" id="ad" type="file" />
					</div>';
		
		$output .= '<div class="form-group"><label for="">Are You Hiring:</label>
						<input id="box1" type="radio" class="checkbox" name="are_you_hiring" value="Yes"';
		if($user_data->are_you_hiring == 'Yes') {
			$output .= ' checked';
		}				
		$output .= '/><label for="box1">Yes</label>
						<input id="box2" type="radio" class="checkbox" name="are_you_hiring" value="No"';
		if($user_data->are_you_hiring == 'No') {
			$output .= ' checked';
		}				
		$output .= '/><label for="box2">No</label>
					</div>';
		
		$output .= '<div class="form-group"><label for="hiring_position">Describe position you are hiring:</label>
						<textarea name="hiring_position" id="hiring_position" class="edit-user-data form-input" placeholder="Describe position">' . $user_data->hiring_position . '</textarea>	
					</div>';
		$output .= '<div class="form-group"><label for="seeking_employment">Are Seeking Employment:</label>
						<input id="box3" type="radio" class="checkbox" name="seeking_employment" value="Yes"';
		if($user_data->seeking_employment == 'Yes') {
			$output .= ' checked';
		}				
		$output .= '/><label for="box3">Yes</label>
						<input id="box4" type="radio" class="checkbox" name="seeking_employment" value="No"';
		if($user_data->seeking_employment == 'No') {
			$output .= ' checked';
		}				
		$output .= '/><label for="box4">No</label>
					</div>';
		$output .= '<div class="form-group"><label for="type_employment_seeking">Describe Type of Job you are seeking:</label>
						<textarea name="type_employment_seeking" id="type_employment_seeking" class="edit-user-data form-input" placeholder="Describe Type of Job">' . $user_data->type_employment_seeking . '</textarea>	
					</div>';


		$output .= '</fieldset>';
		
		$output .= '<div class="form-group"><button type="button" class="updateUserDataBtn btn btn-primary btn-sm ">Save Changes</button> <button type="button" class="cancelUserDataBtn btn btn-sm btn-danger">Cancel</button></div>';
		
		$output .= '</div>';
		$output .= '</div>';
	}
	else {

		$output .= '<div class="profile-header wrapper">';
		$output .= '<div class="profile-avatar-wrapper">';
		$output .= '<div class="profile-avatar">';
		if($user_data->avatar != '' && file_exists(alum_plugin_path . 'uploads/avatars/'. $user_data->avatar)) {
			$output .= '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' .alum_plugin_url. 'uploads/avatars/'. $user_data->avatar . '&h=120&w=120&zc=1" alt="' . $user_displayname . '">';
		}
		else {
			$output .= '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . get_template_directory_uri(). '/lib/images/no-image-icon.png&h=120&w=120&zc=1" alt="' . $user_displayname . '">';
		}
		$output .= '</div>';
		if($is_owner == true) {
			$output .= '<span class="edit-button editAvatarBtn"><i class="fas fa-edit" aria-hidden="true"></i> Edit</span>';
		}
		$output .= '</div>';
		if($is_owner == true) {
			$output .= '<form id="avatar-update-frm" method="post" enctype="multipart/form-data"><input name="avatar" id="user_avatar" type="file" /></form>';
			$output .= '<div class="profile-image-upload">
							<i class="fa fa-camera" aria-hidden="true"></i>
							CLICK HERE TO UPLOAD IMAGE
							<button type="button" class="btn btn-sm btn-danger cancelAvatarUpdateBtn">Cancel</button>
						</div>';
		}
		//else {
		//	$output .= '</div>';
		//}
		$output .= '<h2 class="profile-displayname"><span class="displayname-display">' . $user_displayname . '</span>';
		$output .= '<div class="profile-meta">';
		if($user_data->occupation != '') {
			$output .= '<div class="profile-occupation">' . $user_data->occupation . '';
			if($user_data->employer_name != '') {
				$output .= ' &bull; ' . $user_data->employer_name;
			}
			$output .= '</div>';
		}
		
		$output .= '</div>';
		$output .= '</div>';
		$output .= '<div class="clearfix"></div>';
		$output .= '<div id="profile-wrapper" class="wrapper">';
		$output .= '<div id="profile-right">';
		//invire or date claimed...
		//print_r($user_data);
		//$user_data->date_joined;
		$output .= '<div id="prof-status-box">';
		if($user_data->user_id != '') {
			$output .= 'Profile claimed';
			if($user_data->date_joined != '') {
				$output .= " " . date("m/d/Y", strtotime($user_data->date_joined));
			}
		}
		else {
			$output .= '<button type="button" class="btn btn-primary btn-sm sipopup" data-toggle="modal" data-target="#send_invite_modal" data-id="' . $user_data->id . '" data-group="" data-name="" data-email="" data-phone="">Send Invite</button>';
		}
		$output .= '</div>';
		$output .= '<input type="hidden" name="user_id" value="' . $current_user->ID . '">';
		/*
		if($is_owner == true) {
			$output .= '<ul id="profile-counts">';
			$output .= '<li><i class="fa fa-users" aria-hidden="true"></i><span class="profile-number">3</span>Alumni Groups</li>';
			$output .= '<li><i class="fa fa-users" aria-hidden="true"></i><span class="profile-number">65</span>Members in your Groups</li>';
			$output .= '</ul>';
		}
		*/
		$output .= '<div id="profile-data">';
		if(isset($_SESSION['alum_message'])) {
			$output .= $_SESSION['alum_message'];
			unset($_SESSION['alum_message']);
		}
		$output .= '</h2>';
		//if($user_data->occupation != '') {
		
		//}
		/*
		$output .= '<div class="profile-alumni">' . $user_data->fraternity . ' - ' . $user_data->college . '</div>';
		if($user_data->pledge_class != '') {
			$output .= '<span class="profile-pledge-class">Pledge Class: ' . $user_data->pledge_class . '</span>';
		}
		
		if($user_data->initiation_date != '') {
			$output .= ' - <span class="profile-init-date">Initiation Date: ' . date("M n, Y", strtotime($user_data->initiation_date)) . '</span>';
		}
		*/
		$output .= '<div class="clearfix"></div>';
		$output .= '<div id="profile-data-sub">';
		if($is_owner == true) {
			$output .= '<a href="' . get_bloginfo('url') . '/profile/settings" class="editSettings"><i class="fa fa-cog" aria-hidden="true"></i> <small>Update Settings</small></a>';
		}
		$output .= '<h3>';
		
		if($user_data->salutation != '') {
			$output .= $user_data->salutation . ' ';
		}
		$output .= '' . $user_data->first_name . ' ' . $user_data->middle_name . ' ' . $user_data->last_name;
		if($user_data->suffix != '') {
			$output .= ', ' . $user_data->suffix;
		}
		$output .= '</h3>';
		if($user_nicename != '') {
		//	$output .= '<span class="profile-username">@' . $user_nicename . '</span>';
		}
	   	//Hidden data
	   	$output .= '<hr>';
	   	//echo "HERE: " . $user_settings['email'] . " - " .  $is_owner . "<br>";
	   	if($user_settings['email'] != 0 || $user_settings['email'] == '' ||  $is_owner == true) {
			$output .= '<div class="profile-email">' . $user_data->email . '';
			if($user_settings['email'] != 1 && $is_owner == true && $user_data->email != '') {
				$output .= '<i class="fa fa-lock hiddenSetting" aria-hidden="true"></i>';
			}
			$output .= '</div>';
		}
		//$user_settings['phone'] = 0;
		//echo "NM: $non_member<BR>";
		//echo "PHONE: " . $user_settings['phone'] . "<BR>";
		//echo "IS OWN: " . $is_owner . "<BR>";
		//echo '<pre>'; print_r($user_settings); echo '</pre>';
		if($is_owner == true) {
			
		}
	//	$output .= '<div class="profile-address">';
		
		//$user_settings['address'] = 0;
		
		//if($user_data->city != '') {
		$address = '';
	//	$output .= '<div class="profile-address">';
		if(($user_settings['city'] == 1 || $is_owner == true || $non_member == 1) && $user_data->city != '') {
			$address .= $user_data->city;
		}
		if(($user_settings['state'] == 1 || $is_owner == true || $non_member == 1) && $user_data->state != '') {
			if($address != '') {
				$address .= ', ';
			}
			$address .= $user_data->state;
		}
		if(($user_settings['zipcode'] == 1 || $is_owner == true || $non_member == 1) && $user_data->zipcode != '') {
			if($address != '') {
				$address .= ' ';
			}
			$address .= $user_data->zipcode;
		}
		if(($user_settings['city'] != 1 || $user_settings['state'] != 1 || $user_settings['zipcode'] != 1) && $is_owner == true && $address != '') {
			$address .= '<i class="fa fa-lock hiddenSetting" aria-hidden="true"></i>';
		}
		//echo "ADDRESS: $address-1";
		if( trim($address) !== '') {
			$output .= '<div class="profile-address">';
			if(($user_settings['address'] == 1 || $is_owner == true) && $non_member != 1) {
				$output .= '<div class="profile-street">' . $user_data->address . '';
				if($user_settings['address'] != 1 && $is_owner == true && $user_data->address != '') {
					$output .= '<i class="fa fa-lock hiddenSetting" aria-hidden="true"></i>';
				}
				$output .= '</div>';
			}
			$output .= $address;
			$output .= '</div>';
		}
		
		//}
	//	$output .= '</div>';
		//if($user_settings['phone'] == 1 || $is_owner == 1 || $non_member == 1) {
		if($user_settings['phone'] != 0 || $user_settings['phone'] == '' ||  $is_owner == true) {
			$output .= '<div class="profile-phone">' . $user_data->phone . '';
			if($user_settings['phone'] == '0' && $is_owner == true && $user_data->phone != '') {
				$output .= '<i class="fa fa-lock hiddenSetting" aria-hidden="true"></i>';
			}
			$output .= '</div>';
		}
		if($user_data->birthdate != '') {
			$output .= '<div id="profile-birthdate">Bithdate: ' . date("n/d/Y", strtotime($user_data->birthdate)) . '<br></div>';
		}
		if($user_data->spouse_name != '') {
			$output .= '<div id="profile-spouse">';
			$output .= '<h4>Married to ' . $user_data->spouse_name . '</h4>';		
			$output .= '</div>';
		}
		/* Begin Occupation Section */
	
		if($user_data->employer_name != '') {
			$output .= '<div id="profile-work">';
			$output .= '<fieldset><legend>Business Info</legend>';
			$output .= $user_data->employer_name . '<br>';
			if($user_data->occupation != '') {
				$output .= $user_data->occupation;
				if($user_data->occupation2 != '') {
					$output .= ' - ' . $user_data->occupation2;
				}
				$output .= '<br>';
			}
			if( $user_data->occupation_description != '') {
				$output .=  '<p>' . stripslashes($user_data->occupation_description) . '</p>';

			}
			if($user_data->employer_address != '') $output .= $user_data->employer_address . '<br>';
			if($user_data->work_phone != '') $output .= $user_data->work_phone . '<br>';
			
			$output .= '</fieldset>';
			$output .= '</div>';
		}
		
		if($user_data->are_you_hiring != '' || $user_data->seeking_employment != '') {
			$output .= '<div id="profile-promote">';
			$output .= '<fieldset><legend>Business Promotion</legend>';
			if($user_data->are_you_hiring == 'Yes') {
				$output .= '<b>I am hiring:</b>';
				$output .= '<p>' . $user_data->hiring_position . '</p>';
			}
			
			if($user_data->seeking_employment == 'Yes') {
				$output .= '<b>I am seeking employment:</b>';
				$output .= '<p>' . $user_data->type_employment_seeking . '</p>';
			}


			$output .= '</fieldset>';
			$output .= '</div>';
		}
		
		
		if($is_owner == true) {
			
	   // [dump_fields] => 
	   // [date_joined] => 
	   	
	   		$output .= '<span class="edit-button editDetailsBtn"><i class="fas fa-edit" aria-hidden="true"></i> Update Your Profile</span>';
	   	}
	   	
	   	$output .= '</div>';
		$output .= '</div>';
		
		$output .= '</div>';
	
		$output .= '<div id="profile-left">';
		//Get Groups
		$sql = "SELECT g.*, a.pledge_class, a.initiation_date FROM groups g, alum_groups ag, alumni a WHERE 1 AND a.user_id = '$user_id' AND a.id = ag.alum_id AND ag.status = 1 AND g.id = ag.group_id";
		//echo '<!--' .  $sql . '-->' . "\r\n";
		$groups = $wpdb->get_results($sql);
		//echo '<!--'; print_r($groups); echo '-->';
		if(!empty($groups)) {
			$output .= '<div id="profile-groups">';
			$output .= '<h3>My Alumni Connections</h3>';
		
			foreach($groups as $group) {
				$output .= '<div class="profile-alumni">
								<a href="' . get_bloginfo('url') . '/alum/' . $group->group_slug. '/">' . $group->group_name . '</a>';
				if($group->pledge_class != '') {
					$output .= '<span class="profile-pledge-class">Pledge Class: ' . $group->pledge_class . '</span> - ';
				}

				if($group->initiation_date != '') {
					$output .= '<span class="profile-init-date">Initiation Date: ' . date("M j, Y", strtotime($group->initiation_date)) . '</span>';
				}				
				$output .= '</div>';
			}
			$output .= '</div>';
		}
		
		//Timeline
		//print_r($user_data);
		if($non_member != true) {
			$output .= $alum->buildProfileTimeline($user_id);
			//$output .= $alum->buildProfileTimeline($user_data->id);
		}
		$output .= '</div><div class="clearfix"></div>';
		$output .= '</div>';
		/* Send Invite Modal */
		$output .= '<!-- Modal -->
				<div class="modal fade" id="send_invite_modal" role="dialog">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
								<h4>Send Invite</h4>
							</div>
							<div class="modal-body">
								<form method="post" id="invite-member-frm" style="max-width: 600px;">
									<input type="hidden" name="alumn_id" value="">
									<input type="hidden" name="group_id" value="">
									<input type="hidden" name="sender_id" value="' . $current_user->ID . '">
								<div class="form-group">
									<label for="member_email">Email Address:</label>
									<input class="form-input" id="member_email" name="member_email" placeholder="Enter Email Address" type="text" value="">
								</div>
								
								<div class="form-group">
									<label for="member_phone">Phone:</label>
									<input class="form-input" id="member_phone" name="member_phone" placeholder="Enter Phone" type="text" value="">
								</div>
								
								<button type="button" class="btn btn-primary sendInviteBtn">Send Invite</button>
								</form>
							</div>
						</div>
					</div>
				</div>';
	}
	
	return $output;
}

add_shortcode( 'alum_profile', 'alum_profile_func' );

function alum_members_func() {
	global $wpdb, $alum;
	$output = '';
	//print_r($_GET);
	if ( is_user_logged_in() ) {
		$current_user = wp_get_current_user();
		//Get user data
		$user_data = $alum->getUserData($current_user->ID);
		//print_r($user_data);
		if(empty($user_data) && $_GET['search'] == '') {
			$output .= '<h3>We\'re Sorry</h3>';
			$output .= '<p>It looks like you are not a member of any alumni groups</p>';
		}
		else {
			//get members
			$user_college = $user_data->college;
			$user_pledge_class = $user_data->pledge_class;
			$user_fraternity = $user_data->fraternity;
			$sql = "SELECT id, first_name, last_name, middle_name, pledge_class, fraternity, initiation_date, address, city, state, zipcode, email, user_id, last_updated, date_joined FROM alumni WHERE 1 ";
			if($_GET['search'] != '') {
				$_GET['search'] = sanitize_text_field($_GET['search']);
				$sql .= " AND (college LIKE '%" . strtolower($_GET['search']) . "%' OR first_name LIKE '%" . strtolower($_GET['search']) . "%' OR last_name LIKE '%" . strtolower($_GET['search']) . "%' OR middle_name LIKE '%" . strtolower($_GET['search']) . "%' OR pledge_class LIKE '%" . strtolower($_GET['search']) . "%' )";
			}
			else {
				$sql .= " AND college = '$user_college' AND fraternity = $user_fraternity";
			}
			$sql .= " AND id != 2203";
			$members = $wpdb->get_results($sql);
			if(!empty($members)) {
				//echo '<pre>'; print_r($members); echo '</pre>';
				//$output .= 'MEMBER SEARCH - NAME - ALUMNI GROUP - COLLEGE';
				$output .= '<div>';
				$output .= '<table class="table" id="members_table" >';
				$output .= '<thead><tr><th>Member Name</th><th>Init. Date</th><th>Last Updated</th></tr></thead>';
				$output .= '<tbody>';
				foreach($members as $member) {
					$last_updated = '';
					if($member->user_id != null) {
						$last_updated = date("m/d/y", strtotime($member->last_updated));
					}
					$output .= '<tr><td>';
					$output .= '<span style="display: none;">' . $member->last_name . '</span>';
					//use user nicename
					if($member->user_id != '') {
						$user_info = get_userdata($member->user_id);
						$output .= '<a href="' . get_bloginfo('url'). '/m/' . $user_info->user_nicename . '">'; 
					}
					//use alumni id?!?!
					else {
						$output .= '<a href="' . get_bloginfo('url'). '/m/' . $member->id . '">'; 
					}
					$output .= $member->first_name;
					if($member->middle_name != '') {
						$output .= ' ' . substr($member->middle_name, 0, 1) . '.';
					}
					$output .= ' ' . $member->last_name;
					//if($member->user_id != '') { 
						$output .= '</a>';
					//}
					$output .= '</td><td>' . date("m/d/y", strtotime($member->initiation_date)) . '</td><td>' . $last_updated . '</td></tr>';
				}
				$output .= '</thead>';
				$output .= '</table>';
				$output .= '</div>';
			}
			else {
				$output .= '<h3>We\'re Sorry</h3>';
				if($_GET['search'] != '') {
					$output .= '<p>Your search returned no results</p>';
				}
				else {
					$output .= '<p>It looks like you are not a member of any alumni groups</p>';
				}
			}
		}
	}
	else {
		$output .= '<h3>You must be logged in to access members</h3>';
	}
	return $output;
}
add_shortcode( 'alum_members', 'alum_members_func' );

function alums_page_func() {
	global $wpdb, $alum;
	$output = '';
	//$groups = trim($_GET['user_name'], '/');
	$sql = "SELECT g.*, COUNT(a.id) AS member_count FROM groups g, alum_groups a WHERE 1 AND g.id = a.group_id AND a.status = 1 AND g.status = 1 GROUP BY g.id";
	$groups = $wpdb->get_results($sql);
	if(!empty($groups)) {
		$output .= '<div class="alums-list">';
		foreach($groups as $group) {
			$output .= '<div class="alums-page-list-left"><a href="' . get_bloginfo('url') . '/alum/' . $group->group_slug . '">';
			if($group->group_logo != '') {
				$output .= '<img src="' . get_bloginfo('url') . '/images/alum_group_images/' . $group->group_logo . '" height="120" width="120"> ';
			}
			else {
				$words = explode(' ', $group->group_name);
				$acronym = '';
				foreach ($words as $w) {
					$acronym .= $w[0];
					if(strlen($acronym) == 3) {
						break;
					}
				}
				$output .= '<div class="group-avatar">' . $acronym . '</div>';
			}
			$output .= $group->group_name . '</a></div><div class="alums-page-list-right">' . $group->member_count . ' members</div><div class="clr"></div>';
		}
		$output .= '</div>';
	}
	else {
		//no results
	}
	return $output;
}
add_shortcode( 'alums_page', 'alums_page_func');

function alum_page_func() {
	global $wpdb, $alum;
	$output = '';
	$alum_slug = get_query_var('alum_slug');
	$section = get_query_var('section');
	$sub_section = get_query_var('sub_section');
	$current_user = wp_get_current_user();
	//print_r($alum_slug);
	if($alum_slug != '') {
		//get data by slug
		$alum_slug = sanitize_title($alum_slug);
		
		$sql = "SELECT * FROM groups WHERE 1 AND group_slug = '$alum_slug'";
		$group = $wpdb->get_row($sql);
		$output .= '<div id="alum-header" class="wrapper">';
		
		if($group->group_logo != '') {
			$output .= '<div class="alum-img"><img src="' . get_bloginfo('url') . '/images/alum_group_images/' . $group->group_logo . '" style=" "></div>';
		}
		$output .= '<div class="alum-title"><h2>' . $group->group_name . '</h2></div>';
		$output .= '</div><div class="clr"></div>';
		
		//Get Category
		$sql = "SELECT cat_id FROM groups WHERE id = '" . $group->id . "' LIMIT 1";
		$cat = $wpdb->get_row($sql);

		///Ids logged in, and a memeber
		if(is_user_logged_in()) {
			if($alum->is_group_member($group->id) || $current_user->ID == 1 || current_user_can('administrator')) {
				if($section == 'edit-description') {
					$output .= '<div class="group-content wrapper">';
					$output .= '<span class="back-button"><a href="'.get_bloginfo('url') . '/alum/' . $group->group_slug.'"><i class="fa fa-chevron-left"></i> Back to ' . $group->group_name . '</a></span>';
					$output .= '<h3>Group Description</h3>';
					$output .= '<p>Enter your group description below</p>';
					$output .= '<form method="post" id="group-description-form">';
					$output .= '<input type="hidden" name="update_desc" value="1">';
					$output .= '<input type="hidden" name="group_id" value="' . $group->id . '">';
					$output .= '<input type="hidden" name="user_id" value="' . $current_user->ID . '">';
					$output .= '<input type="hidden" name="slug" value="' . $alum_slug . '">';
					$output .= '<div class="form-group">';
					$output .= '<label for="group_description"></label>';
					$output .= '<textarea class="form-input" id="group_description" name="group_description" style="height: 250px;">' . $group->group_description. '</textarea>';
					$output .= '</div>';
					$output .= '<div class="form-group">';
					$output .= '<button type="submit" class="btn btn-primary saveGroupDescBtn">Save Changes</button>';
					$output .= '</div>';
					$output .= '</form>';
					$output .= '</div>';
				}
				elseif($section == 'add-links') {
					//print_r($_POST);
					$group_id = $_POST['group_id'];
					if($_POST['add_link'] == 1) {
						$wpdb->insert( 
							'alum_group_links', 
							array( 
								'group_id' => $group_id, 
								'caption' => $_POST['link_caption'],
								'url' => $_POST['link_url']
							), 
							array( 
								'%d',
								'%s', 
								'%s'
							) 
						);
						
/*						
$wpdb->show_errors();
$wpdb->print_error(); 
*/
						
						$_SESSION['alum_message'] = 'Your group link has been added';
						$_SESSION['alum_message_type'] = 'success';
						//header("Location: " . get_bloginfo('url') . "/alum/" . $alum_slug . "/");
						echo '<script>window.location.replace("' . get_bloginfo('url') . '/alum/' . $alum_slug . '/");</script>';
						exit;
					}
					$output .= '<div class="group-content wrapper" >';
					$output .= '<h3>Add Group Link</h3>';
					$output .= '<p>Enter your group link below</p>';
					$output .= '<form method="post" id="group-link-form">';
					$output .= '<input type="hidden" name="add_link" value="1">';
					$output .= '<input type="hidden" name="group_id" value="' . $group->id . '">';
					$output .= '<input type="hidden" name="user_id" value="' . $current_user->ID . '">';
					$output .= '<div class="form-group">
									<label for="link_caption">Caption:</label>
									<input type="text" class="form-input" id="link_caption" name="link_caption" placeholder="Enter title">
								</div>';
					$output .= '<div class="form-group">
									<label for="link_url">Website Address:</label>
									<input type="text" class="form-input" id="link_url" name="link_url" placeholder="http://">
								</div>';
					$output .= '<div class="form-group">';
					$output .= '<button type="submit" class="btn btn-primary saveGroupLinkBtn">Save Link</button>';
					$output .= '</div>';
					$output .= '<form>';
					$output .= '</div>';
				}
				elseif($section == 'add-promo') {
					//print_r($_POST);
					//print_r($_FILE);					
					$output .= '<div class="group-content wrapper">';
					$output .= '<span class="back-button"><a href="'.get_bloginfo('url') . '/alum/' . $group->group_slug.'"><i class="fa fa-chevron-left"></i> Back to ' . $group->group_name . '</a></span>';
					$output .= '<h3>Add Promotional Ad</h3>';
					$output .= '<form method="post" id="group-ad-form" enctype="multipart/form-data">';
					$output .= '<p>Enter your group link below</p>';
					$output .= '<input type="hidden" name="add_promo" value="1">';
					$output .= '<input type="hidden" name="group_id" value="' . $group->id . '">';
					$output .= '<input type="hidden" name="user_id" value="' . $current_user->ID . '">';
					$output .= '<input type="hidden" name="slug" value="' . $alum_slug . '">';
					$output .= '<div class="form-group">
									<label for="url">Link Website:</label>
									<input type="text" class="form-input" id="url" name="url" placeholder="http://">
								</div>';
					$output .= '<div class="form-group">
									<label for="link_url">Upload Ad:</label>
									<input name="ad" id="ad" type="file" />
								</div>';
					$output .= '<div class="form-group">';
					$output .= '<button type="submit" class="btn btn-primary savePromoLinkBtn">Save promo</button>';
					$output .= '</div>';
					$output .= '</form>';
					$output .= '</div>';
				}
				elseif($section == 'blog') {
					$output .= '<div class="group-content wrapper">';
					$output .= '<span class="back-button"><a href="'.get_bloginfo('url') . '/alum/' . $group->group_slug.'"><i class="fa fa-chevron-left"></i> Back to ' . $group->group_name . '</a></span>';
					if($sub_section != '') {
						//post_name
						$args = array(
						  'name'        => $sub_section,
						  'post_type'   => 'post',
						  'post_status' => 'publish',
						  'numberposts' => 1
						);
						$post = get_posts($args);
						if( $post ) :
							$output .= '<div id="group-blog-post">';
						  	$output .= '<h3>' . $post[0]->post_title . '</h3>';
						  	$output .= '<div class="ap-blog-meta">' . date("M d, Y", strtotime($post[0]->post_date)) . ' ' . date("g:i A", strtotime($post[0]->post_date)) . '</div>';
							$output .= '<p>' . str_replace("\n", '<br>', $post[0]->post_content) . '</p>';
							$output .= '<div id="single-post-reply" style="display: none;"><div class="form-group">
											<input type="hidden" name="group_id" value="' . $group->id . '">
											<input type="hidden" name="user_id" value="' . $current_user->ID . '">
											<input type="hidden" name="post_id" value="' . $post[0]->ID . '">
											<input type="text" class="form-control" id="comment_reply" name="comment_reply" placeholder="Reply to post"> <button type="button" class="btn btn-secondary btn-sm sendCommentReplyBtn">Reply</button> <button type="button" class="btn btn-danger btn-sm closeCommentReplyBtn">Cancel</button>
										</div></div>';
							$output .= '<p><button type="button" class="btn btn-primary btn-sm sendBlogReplyBtn">Post Reply</button></p>';
							$output .= '</div>';
							//Get replies
							//Get posts
							$args = array(
								'post_id' => $post[0]->ID,
								'orderby' => 'date',
								'order'   => 'DESC',
							);
							$posts_replies = get_comments( $args );
						//	$posts_replies = get_posts( $args );
						//	echo '<pre>'; print_r($posts_replies); echo '</pre>';
							if(!empty($posts_replies)) {
								$output .= '<h4>Comments</h4>';
								foreach($posts_replies as $reply) {
									$output .= '<div class="post-reply" style="border-bottom: 1px solid #f1f1f1; padding: 20px 0;">';
									$output .= '<div class="reply-content">' . $reply->comment_content . '</div>';
									$output .= '<div class="reply-meta" style="font-size: 12px; color: #999; margin: 10px 0 0 0;">' . $reply->comment_author . ' - ' . date("M d, Y", strtotime($reply->comment_date)) . ' ' . date("g:i a", strtotime($reply->comment_date)). '</div>';
									$output .= '</div>';
								}
							}
						endif;
					}
					else {
						//Get posts
						$args = array(
							'posts_per_page'   => -1,
							'offset'           => 0,
							'category'         => array($cat->cat_id),
							'category_name'    => '',
							'orderby'          => 'date',
							'order'            => 'DESC',
							'include'          => '',
							'exclude'          => '',
							'meta_key'         => '',
							'meta_value'       => '',
							'post_type'        => 'post',
							'post_mime_type'   => '',
							'post_parent'      => '',
							'author'	   	   => '',
							'author_name'	   => '',
							'post_status'      => 'publish',
							'suppress_filters' => true 
						);
						//print_r($cat);
						$posts_array = get_posts( $args );
						//echo '<pre>'; print_r($posts_array); echo '</pre>';
						$output .= '<div class="group-content-actions"><button type="button" class="btn btn-primary btn-sm showPostFrmButton">Submit Post</button></div>';
						//$output .= '<span class="back-button"><a href="'.get_bloginfo('url') . '/alum/' . $group->group_slug.'"><i class="fa fa-chevron-left"></i> Back to ' . $group->group_name . '</a></span>';
						$output .= '<h3>Blog</h3>';
						//Blog form...
						$output .= '<div id="blogFormContainer" class="wrapper"><form role="form" id="submitBlogFrm">
										<h4>Enter your comments below</h4>
										<p>The group admin will approve or dissaprove as soon as possible</p>
										<input type="hidden" name="group_id" value="' . $group->id . '">
										<input type="hidden" name="user_id" value="' . $current_user->ID . '">
										<div class="form-group">
											<label for="post_title">Title:</label>
											<input type="text" class="form-input" id="post_title" name="post_title" placeholder="Enter title">
										</div>
										<div class="form-group">
											<label for="post_content">Comments:</label>';
											$content = '';
						$editor_id = 'post_content';
						$content = '';
						//$output .= wp_editor( $content, $editor_id );
						$output .= '<textarea class="form-input" id="post_content" name="post_content"></textarea>';
						
						$output .= '</div>

										<button type="button" class="btn btn-primary sendBlogRequestBtn">Post Comments</button> <button type="button" class="btn btn-danger cancelBlogRequestBtn">Cancel</button>
								   </form></div>';
						$output .= '<div id="group-blog">';
						if(!empty($posts_array)) {
							foreach($posts_array as $post) {
								if($post->post_excerpt == '') {
									$post->post_excerpt = excerpt(trim($post->post_content), 100);
								}
								$output .= '<div class="blog-post">
												<h3>' . $post->post_title . '</h3>
												<div class="ap-blog-meta">' . date("M d, Y", strtotime($post->post_date)) . ' ' . date("g a", strtotime($post->post_date)) . '</div>
												<p>' . str_replace("\n", '<br>', $post->post_excerpt) . '</p>
												<p><a href="' . $post->post_name . '">Read Full Post</a></p>
											</div>';	
							}
						}
					}
					$output .= '</div>';
					$output .= '</div>';

				}
				elseif($section == 'photos') {
					$output .= '<div class="group-content wrapper" id="group-photos-content">';
					$output .= '<div class="group-content-actions"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#submit_photo_modal">Submit Photo</button></div>';
					$output .= '<span class="back-button"><a href="'.get_bloginfo('url') . '/alum/' . $group->group_slug.'"><i class="fa fa-chevron-left"></i> Back to ' . $group->group_name . '</a></span>';
					$output .= '<h3>Photos</h3>';
					
					if(isset($_SESSION['alum_message'])) {
						$output .= '<div class="alert alert-' . $_SESSION['alum_message_type']  . '">' . $_SESSION['alum_message'] . '</div>';
						unset($_SESSION['alum_message']);
					}

					//Get Photos
					$sql = "SELECT id, user_id, caption, photo_name, last_updated, photo_year FROM alum_photos WHERE 1 AND group_id = '" . $group->id . "' AND status = 1 ";
					if($_GET['pc'] != '' && $_GET['pc'] != 'all') {
						$sql .= "AND pledge_class = '" . $_GET['pc'] . "' ";
					}
					$sql .= "ORDER BY last_updated DESC";
					//echo $sql;
					$photos = $wpdb->get_results($sql);
					if(!empty($photos)) {
						//get pledge class selections
						$sql = "SELECT photo_year FROM alum_photos WHERE 1 AND group_id = '" . $group->id . "' AND status = 1 GROUP BY photo_year";
						$pcs = $wpdb->get_results($sql);
						$output .= '<div style="max-width: 300px; margin: 10px 0 20px 0;"><form method="get"><label for="pc">Show Year:</label> <select id="pc" name="pc" class="form-control" onchange="this.form.submit();">';
						$output .= '<option value="all"';
						if($_GET['pc'] == 'all' || $_GET['pc'] == '') {
							$output .= ' selected';
						}
						$output .= '>Show All Years</option>';
						foreach($pcs as $pc) {
							$output .= '<option value="'.$pc->photo_year.'"';
							if($_GET['pc'] == $pc->photo_year) {
								$output .= ' selected';
							}
							$output .= '>'.$pc->photo_year.'</option>';
						}
						$output .= '</select></form></div>';
						$photo_dir = get_bloginfo('url') . '/wp-content/plugins/alumn/uploads/photos';
						$output .= '<ul id="group-photos">';
						foreach($photos as $photo) {
							//echo'<pre>'; print_r($photo); echo '</pre>';
							//if(function_exists('exif_read_data')) {
							//    $exif = exif_read_data($_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/alumn/uploads/photos/' . $photo->photo_name);
							//	print_r($exif);
							//}
							$output .= '<li id="group-photo-' . $photo->id . '"><a href="' . $photo_dir . '/' . $photo->photo_name . '"><img src="' . get_bloginfo('url') . '/wp-content/plugins/alumn/lib/php/timthumb.php?src=' . $photo_dir . '/' . $photo->photo_name . '&h=200&w=320&z=1"></a><span class="photo-caption">' . $photo->caption . ' - ' . $photo->photo_year . '</span>';
							if($photo->user_id == $current_user->ID || $alum->is_group_admin($group->id) || current_user_can('edit_users')) {
								$output .= '<button type="button" class="btn btn-primary btn-xs editPhoto" data-id="' . $photo->id . '" data-year="' . $photo->photo_year . '" data-toggle="modal" data-target="#edit_photo_modal">Edit Photo</button>';
								$output .= '<button type="button" class="btn btn-danger  btn-xs deletePhoto" data-id="' . $photo->id . '">Delete Photo</button>';
							}
							$output .= '</li>';
						}
						$output .= '</ul>';
					}

					$output .= '</div>';
					
				}
				elseif($section == 'jobs') {
					$sql = "SELECT job_title, job_desc, city, state, contact_info, last_updated FROM alum_jobs WHERE 1 AND group_id = '" . $group->id . "' AND status = 1";
					if($_GET['js'] != '') {
						$sql .= " AND (job_title LIKE '%" . $_GET['js']. "%' OR job_desc LIKE '%" . $_GET['js']. "%')";
					}
					if($_GET['city'] != '') {
						$sql .= " AND city = '".$_GET['city']."'";
					}
					if($_GET['state'] != '') {
						$sql .= " AND state = '".$_GET['state']."'";
					}
					$sql .= " ORDER BY last_updated DESC";
					//echo $sql;
					$jobs = $wpdb->get_results($sql);
					
					//random jobs
					$sql = "SELECT j.job_title, j.job_desc, j.city, j.state, j.contact_info, j.last_updated FROM alum_jobs j WHERE 1 AND j.group_id = '" . $group->id . "' AND j.status = 1 ORDER BY RAND() LIMIT 3";
					//echo $sql;
					$jobs = $wpdb->get_results($sql);
					//Randon looking for hiring....
					$sql = "SELECT g.alum_id, a.user_id, a.state, a.city, CONCAT(a.first_name, ' ', a.last_name, ' ',  a.phone) AS contact_info, m1.meta_value AS job_title FROM alum_groups g, alumni a, wp_usermeta m, wp_usermeta m1 WHERE 1 AND g.group_id = '" . $group->id . "' AND g.status = 1 AND a.id = g.alum_id AND a.user_id != '' AND m.user_id = a.user_id AND m.meta_key = '_alum_are_you_hiring' AND m.meta_value = 'Yes' AND m1.user_id = a.user_id AND m1.meta_key = '_alum_hiring_position' AND m1.meta_value != ''";
					if($_GET['js'] != '') {
						$sql .= " AND (m1.meta_value LIKE '%" . $_GET['js']. "%')";
					}
					if($_GET['city'] != '') {
						$sql .= " AND a.city = '".$_GET['city']."'";
					}
					if($_GET['state'] != '') {
						$sql .= " AND a.state = '".$_GET['state']."'";
					}

//echo $sql;
					$jobs2 = $wpdb->get_results($sql);
//echo '<pre>'; print_r($jobs2); echo '</pre>';
					if(empty($jobs) && !empty($jobs2)) {
						$jobs = $jobs2;
					}
					elseif(!empty($jobs) && !empty($jobs2)) {
						$jobs = array_merge($jobs, $jobs2);
					}

					$output .= '<div class="group-content wrapper">';
					$output .= '<div class="group-content-actions"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#submit_job_modal">Submit Job Listing</button></div>';
					$output .= '<div class="group-content-search">';
					
					$output .= '<div class="search-left">Showing ' . count($jobs) . ' Job';
					if(count($jobs) != 1) {
						$output .= 's';
					}
					if($_GET['js'] != '') {
						$output .= ' | <a href="' . get_bloginfo('url') . '/alum/'.$group->group_slug.'/jobs"><i class="fa fa-caret-left"></i> Back To All Jobs</a>';
					}
					$output .= '</div>';
					$output .= '<form method="get">Search Jobs: <input name="js" type="text" placeholder="Title, Description" value="' . $_GET['js'] . '">
										<input name="city" type="text" placeholder="City" value="' . $_GET['city'] . '">
										<select class="" id="state" name="state">
											<option value="">Choose State</option>' . "\r\n";
							foreach($alum->states as $state_abbr=>$state_name) {
								$output .= '<option value="'.$state_abbr.'"';
								if($state_abbr == $_GET['state']) {
									$output .= ' selected';
								}
								$output .= '>'.$state_name.'</option>' . "\r\n";
							}
							$output .= '</select>
										<button type="submit" class="btn btn-primary btn-sm">search</button>
									</form>
								</div>';
					//$output .= '<h3>Jobs</h3>';
					if(!empty($jobs)) {
						$output .= '<div id="group-jobs">';
						foreach($jobs as $job) {
							$output .= '<div class="job-post">
											<h3>' . $job->job_title . '</h3>
											<div class="ap-job-meta">' . $job->city . ', ' . $job->state . '</div>
											<p>' . str_replace("\n", '<br>', $job->job_desc) . '</p>
											<p>' . str_replace("\n", '<br>', $job->contact_info) . '</p>
										</div>';
						}
					}
					$output .= '</div>';
					$output .= '</div>';
					/* Modal */
					$output .= '<!-- Modal -->
							<div class="modal fade" id="submit_job_modal" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
											<h4>Submit Job - ' . $group->group_name . '</h4>
										</div>
										<div class="modal-body">
											<form role="form" id="submitJobFrm">
												<input type="hidden" name="group_id" value="' . $group->id . '">
												<input type="hidden" name="user_id" value="' . $current_user->ID . '">
												<div class="form-group">
													<label for="job_title">Job Title:</label>
													<input type="text" class="form-control" id="job_title" name="job_title" placeholder="Job Title">
												</div>
												<div class="form-group">
													<label for="job_desc">Description:</label>
													<textarea class="form-control" id="job_desc" name="job_desc"></textarea>
												</div>
												<div class="form-group">
													<label for="caption">City:</label>
													<input type="text" class="form-control" id="city" name="city" placeholder="City">
												</div>
												<div class="form-group">
													<label for="state">State:</label>
													<select class="form-control" id="state" name="state">
														<option value="">Choose State</option>';
							foreach($alum->states as $state_abbr=>$state_name) {
								$output .= '<option value="'.$state_abbr.'">'.$state_name.'</option>';
							}
							$output .= '</select>
												</div>
												<div class="form-group">
													<label for="contact_info">Contact Info:</label>
													<textarea class="form-control" id="contact_info" name="contact_info"></textarea>
												</div>
												<button type="button" class="btn btn-default btn-success sendJobListingBtn">Submit Job Listing</button>
										  </form>
										</div>
									</div>
								</div>
							</div>';
				}
				elseif($section == 'events') {
					$group_id = $group->id;
					$month = $_GET['mo'];
					$year = $_GET['yr']; 
					$output .= '<div class="group-content wrapper" id="group-events">'; //submit_event_modal
					$output .= '<div class="group-content-actions"><button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#submit_event_modal">Submit Event</button></div>';
					$output .= '<span class="back-button"><a href="'.get_bloginfo('url') . '/alum/' . $group->group_slug.'"><i class="fa fa-chevron-left"></i> Back to ' . $group->group_name . '</a></span>';
					$output .= '<h3>Calendar of Events</h3>';
					$output .= '<div class="full-calendar">';
					$output .= $alum->showSideCalendar($month, $year,true,$group_id);
					$output .= '</div>';
					$output .= '</div>';
				}
				elseif($section == 'members') {
					//Get members...
					$sql = "SELECT a.id, a.user_id, a.first_name, a.last_name, a.middle_name, a.pledge_class, a.fraternity, a.initiation_date, a.lat, a.lng, a.city, a.state, u.user_nicename, u.display_name, a.dump_fields, a.date_joined, a.last_updated FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '". $group->id ."' AND a.id = g.alum_id AND g.status = 1";
					if($_GET['state'] != '') {
						$sql .= " AND a.state = '" . $_GET['state'] . "'";
					}
					$sql .= " ORDER BY a.last_updated DESC";
					//echo "$sql";
					$members = $wpdb->get_results($sql);
					$output .= '<div class="group-content wrapper" id="group-members">';
					$output .= '<span class="back-button"><a href="'.get_bloginfo('url') . '/alum/' . $group->group_slug.'"><i class="fa fa-chevron-left"></i> Back to ' . $group->group_name . '</a></span>';
					$output .= '<h3>Alum Members</h3>';
					//echo '<pre>'; print_r($members); echo '</pre>';
					//$output .= '<div id="members-block"></div>';
					if(!empty($members )) {
    					$locations = '';
    					$info = array();
    					foreach($members as $i => $result) {
    						//echo $result->dump_fields;
							$lat = $result->lat;
							$lng = $result->lng;
							if($lat != '' && $lng != '') {
								$locations .= '{lat: '.$lat.', lng: '.$lng.'},' . "\r\n";
								$info[$i] = $result;
								$info[$i]->init_date_formatted = date("m/d/Y", strtotime($result->initiation_date));
								if($result->user_nicename != '' && $result->user_nicename != null) {
									$info[$i]->slug = $result->user_nicename;
								}
								else {
									$info[$i]->slug = $result->id;
								}
							}							
    					}
    					$info= array_values($info);
    					$locations = substr($locations, 0, -3);
    					$infoWin = json_encode($info);
						//echo "LOC: $locations";	
						$output .= '<script>
								      function initMap() {
										var min = .999992;
										var max = 1.000004;
										var pos = [];
								        var map = new google.maps.Map(document.getElementById(\'map\'), {
								          	zoom: 4,
								          	center: {lat: 37.926868, lng: -95.273437}
								        });
										var infoWin = new google.maps.InfoWindow();
										var data = ' . $infoWin . ';
								        var markers = locations.map(function(location, i) {
								          	//return new google.maps.Marker({
								            //	position: location,
								            //	label: labels[i % labels.length]
								          	//});
											latlng = location.lat + "," + location.lng;
											
								          	var content = \'<div class="iwAlum" data-url="' . get_bloginfo('url') . '/m/\' + data[i].slug + \'">\';
								          	//if(data[i].photo != null) {
								          	//	content += \'<div class="iwanglerPhoto" style="border-radius: 50%; height: 50px; width: 50px; float: left; overflow: hidden;"><img src=""></div>\';
								          	//}
								          	//else {
								          		content += \'<div class="iwanglerInit" style="width: 50px; height: 50px; float: left; font-size: 20px; text-align: center; border-radius: 50%; line-height: 2.5em; background: #00b3d3; color: #fff;">\' + data[i].first_name.substr(0, 1) + data[i].last_name.substr(0, 1) + \'</div>\';
								          	//}
											
								          	content += \'<div class="iwAlumInfo" style="float: left; margin-left: 10px;">\';
								          	content += \'<h3 style="font-size: 18px; font-weight: 600; margin: 0 0 2px 0; text-transform: uppercase;">\' + data[i].first_name + \' \' + data[i].last_name + \'</h3>\';
								          	content += \'<div class="iwanglerLocation" style="font-size: 11px; color: #666; text-transform: uppercase;">\' + data[i].city + \', \' + data[i].state + \'</div>\';
								          	content += \'</div>\';
								          	content += \'</div>\';
											console.log("LOC: " + location.lat + " - " + location.lng);
											var finalLocation = location;
											//console.log("INDEX: " + pos.indexOf(latlng));
											if(pos.indexOf(latlng) > 0) {
												//console.log("Duplicate Entry - LATLNG: " + latlng);
												var newLat = location.lat * (Math.random() * (max - min) + min);
				                    			var newLng = location.lng * (Math.random() * (max - min) + min);
				                    			finalLocation = new google.maps.LatLng(newLat,newLng);
												//console.log("New LATLNG: " + finalLocation.lat + "," + finalLocation.lng);
											}
								          	var marker = new google.maps.Marker({
										      	position: finalLocation
										    });
										    google.maps.event.addListener(marker, \'click\', function(evt) {
										    	//console.log(data[i]);
										      	infoWin.setContent(content);
										      	infoWin.open(map, marker);
										    })
											pos.push(latlng);
										    return marker;
								        });
										
										var bounds = new google.maps.LatLngBounds();
										
								        // Add a marker clusterer to manage the markers.
								        var markerCluster = new MarkerClusterer(map, markers, {
								        	imagePath: \'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m\'
								        });
								        
								        for (var i = 0; i < markers.length; i++) {
											//console.log(markers[i].getPosition());
										 	bounds.extend(markers[i].getPosition());
										}
										
										map.fitBounds(bounds);
								        google.maps.event.addListener(map, \'bounds_changed\', function() {
											//console.log("WE ARE HERE");
											var zoom = map.getZoom();
											//console.log(zoom);
											var tds = \'\';
											//Get visible markers
											if(zoom >= 8) {
												mc = 0;
												for (var i=0; i<markers.length; i++){
												    if( map.getBounds().contains(markers[i].getPosition()) ){
												        //code for showing your object, associated with markers[i]
												        //console.log("MARKER: " + i);
												        //console.log(data[i].first_name);
												        tds += \'<tr><td><span style="display: none;"></span><a href="' . get_bloginfo('url'). '/m/\' + data[i].slug + \'">\' + data[i].first_name + \' \' + data[i].last_name + \'</a></td><td>\' + data[i].city + \', \' + data[i].state + \'</td><td>\' + data[i].fraternity + \'</td><td>\' + data[i].pledge_class + \'</td><td>\' + data[i].init_date_formatted + \'</td></tr>\';
												    	mc++;
												    }
												}
												console.log(tds);
												if(tds != \'\') {
													$(\'#loc-members-table tbody\').html(tds);
													$(\'#loc-members-count\').html(\'Showing \' + mc + \' members\');
												}
											}  
										});
										
										map.addListener(\'center_changed, bounds_changed, zoom_changed\', function() {
									    	console.log("CHANGED");
									  	});    
								      }
								      var locations = ['. $locations . ']
								    </script>
								    <script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js"></script>
								    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHMwJQGzC5DG7A2gJhD9Wno2JIXwKKiMU&callback=initMap"></script>
				            		<div id="map" style="width: 100%; height: 350px;"></div>';
						$output .= '<div id="loc-members-table-wrapper">';
						$output .= '<div id="loc-members-count">Showing ' . count($members) . ' members</div>';

				     	$output .= '<table class="table striped" id="loc-members-table"><thead><tr><th>Member Name</th><th>Location</th><th>Pledge Class</th><th>Init. Date</th><th>Last Updated</th></tr></thead><tbody>';
				     	foreach($members as $member) {
							$last_updated = '';
							if($member->user_id != null) {
								$last_updated = date("m/d/y", strtotime($member->last_updated));
							}
				     		$output .= '<tr><td>';
				     		if($member->user_id != '') {
								$user_info = get_userdata($member->user_id);
								$output .= '<a href="' . get_bloginfo('url'). '/m/' . $user_info->user_nicename . '">'; 
							}
							//use alumni id?!?!
							else {
								$output .= '<a href="' . get_bloginfo('url'). '/m/' . $member->id . '">'; 
							}

				     		if($member->display_name != '') {
				     			$output .= $member->display_name;
				     		}
				     		else {
				     		 	$output .= $member->first_name;
				     		 	if($member->middle_name != '') {
				     		 		$output .= ' ' . substr($member->middle_name, 0, 1) . '.'; 
				     		 	}
				     			$output .= ' ' . $member->last_name;
				     		}
				     		$output .= '</a>';
				     		$output .= '</td><td>' . $member->city . ', ' . $member->state . '</td><td align="center">' . $member->pledge_class . '</td><td align="center">' . date("m/d/Y", strtotime($member->initiation_date)) . '</td><td align="center">' . $last_updated. '</td></tr>';
				     	}
				     	$output .= '</tbody></table>';
				     	$output .= '</div>';
				     }
			         
			         
			         $output .= '</div>';

				}
				elseif($section == 'messages') {
					$output .= '<div class="group-content wrapper" id="group-messages">';
					$output .= '<div class="group-content-actions"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#submit_message_modal">Submit Message</button></div>';
					$output .= '<span class="back-button"><a href="'.get_bloginfo('url') . '/alum/' . $group->group_slug.'"><i class="fa fa-chevron-left"></i> Back to ' . $group->group_name . '</a></span>';
					$output .= '<h3>Messages</h3>';
					$sql = "SELECT user_id, pledge_class, message, date_added FROM alum_messages WHERE 1 AND group_id = '" . $group->id . "' AND status = 1 ORDER BY date_added DESC";
					//echo $sql;
					$messages = $wpdb->get_results($sql);
					//echo '<pre>'; print_r($messages); echo '</pre>';
					if(!empty($messages)) {
						foreach($messages as $message) {
							$output .= '<div class="message-block">
											<p>' . $message->message . '</p>
										</div>';
						}
					}
					$output .= '</div>';
					/* Modal */
					$output .= '<!-- Modal -->
							<div class="modal fade" id="submit_message_modal" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
											<h4>Submit Message - ' . $group->group_name . '</h4>
										</div>
										<div class="modal-body">
											<form role="form" id="submitMessageFrm">
												<input type="hidden" name="group_id" value="' . $group->id . '">
												<input type="hidden" name="pledge_class" value="' . $user->pledge_class . '">
												<input type="hidden" name="user_id" value="' . $current_user->ID . '">
												<div class="form-group">
													<label for="message">Message:</label>
													<textarea class="form-control" id="message" name="message"></textarea>
												</div>
												<button type="button" class="btn btn-default btn-success sendMessageBtn">Submit Message</button>
										  </form>
										</div>
									</div>
								</div>
							</div>';
				}
				else {
					$loggedin_user_data = $alum->getUserData($current_user->ID);
					if(isset($_SESSION['alum_message'])) {
						$output .= '<div style="padding-top: 60px; margin-bottom: -40px;" class="wrapper"><div class="alert alert-' . $_SESSION['alum_message_type']  . '">' . $_SESSION['alum_message'] . '</div></div>';
						unset($_SESSION['alum_message']);
					}
					//Get members...
					$sql = "SELECT a.id, a.user_id, a.first_name, a.last_name, a.middle_name, a.pledge_class, a.fraternity, a.initiation_date, a.date_joined, a.last_updated, a.lat, a.lng, a.city, a.state, u.user_nicename, a.dump_fields, a.occupation, a.occupation2, u.user_registered FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '". $group->id ."' AND a.id = g.alum_id AND g.status = 1";
					//if($_GET['show_class'] != '' && $_GET['show_class'] != 'all') {
					//	$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '" . $_GET['show_class'] . "'";
					//}
					//elseif($_GET['show_class'] == 'all') {
					//	$sql .= "";
					//}
					//else {
					//	$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '" . date("Y", strtotime($loggedin_user_data->initiation_date)) . "'";
					//}
					$sql .= " AND a.id != 2203";
					//print_r($loggedin_user_data);
					//echo "$sql";
					$members = $wpdb->get_results($sql);
					if(!empty($members )) {
    					$locations = '';
    					$info = array();
    					foreach($members as $i => $result) {
							$lat = $result->lat;
							$lng = $result->lng;
							if($result->state != '') {
								//$data['states'][$result->state]['count']++;
								//$data['states'][$result->state]['state_name'] = $alum->states[$result->state];
								//$tstates[$i] = $result->state;
								$tsc[$result->state]++; 
							}
							if($lat != '' && $lng != '') {
								$locations .= '{lat: '.$lat.', lng: '.$lng.'},' . "\r\n";
								$info[$i] = $result;
								$info[$i]->init_date_formatted = date("m/d/Y", strtotime($result->initiation_date));
								if($result->user_nicename != '' && $result->user_nicename != null) {
									$info[$i]->slug = $result->user_nicename;
								}
								else {
									$info[$i]->slug = $result->id;
								}
							}							
    					}
    					//print_r($alum->states);
    					//print_r($tsc);
						foreach($alum->states as $state_abbr => $state_name) {
							if($tsc[$state_abbr] == 0) {
								$data['states'][$state_abbr]['count'] = 0;
							}
							else {
								$data['states'][$state_abbr]['count'] = $tsc[$state_abbr];
							}
							$data['states'][$state_abbr]['state_name'] = $state_name;
						}
						//print_r($data);
						$state_data = json_encode($data);
    					$info= array_values($info);
    					$locations = substr($locations, 0, -3);
    					$infoWin = json_encode($info);
    				}
    				$output .= '<div id="alum-desc" class="wrapper">';
    				if($group->group_description != '' ) {
						$output .= str_replace("\n", '<br>', $group->group_description);
					}
					//If group admin, show edit
					if($alum->is_group_admin($group->id) || current_user_can('edit_users')) {
						$output .= '<div id="desc-edit"><a href="edit-description" class="btn btn-primary btn-sm"> Edit Group Description</a></div>';
					}
					$output .= '</div>';
   					$output .= '<div id="alum-content-full">';
					//echo "CAT ID: " . $cat->cat_id . "<BR>";
   					//Get posts
					$args = array(
						'posts_per_page'   => 3,
						'offset'           => 0,
						'category'         => array($cat->cat_id),
						'category_name'    => '',
						'orderby'          => 'date',
						'order'            => 'DESC',
						'include'          => '',
						'exclude'          => '',
						'meta_key'         => '',
						'meta_value'       => '',
						'post_type'        => 'post',
						'post_mime_type'   => '',
						'post_parent'      => '',
						'author'	   	   => '',
						'author_name'	   => '',
						'post_status'      => 'publish',
						'suppress_filters' => true 
					);
					$posts_array = get_posts( $args );
					$output .= '<div id="recent-posts">';
					$output .= '<div class="alum-section-title" id="recent-post-section">';
					$output .= '<div class="wrapper">';
					$output .= '<h3>Alum Blog Posts</h3>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '<div class="alum-page-blog-post-body wrapper">';
					if(!empty($posts_array)) {
						
						foreach($posts_array as $blog) {
							if($blog->post_excerpt == '') {
								$blog->post_excerpt = excerpt(trim($blog->post_content), 50);
							}
							$post_author_id = get_post_field( 'post_author', $blog->ID);
							$adisplay_name = get_the_author_meta( 'display_name' , $post_author_id );
							$anice_name = get_the_author_meta( 'user_nicename' , $post_author_id );
							$user_data = $alum->getUserData($post_author_id); 
							//print_r($user_data);
							//echo $display_name;
							//echo "POST AUTHOR ID: $post_author_id";
							$output .= '<div class="alum-page-blog-post">';
							$output .= '<div class="blog-post-left">';
							$output .= '<div class="profile-avatar">';
							if($user_data->avatar != '' && file_exists(alum_plugin_path . 'uploads/avatars/'. $user_data->avatar)) {
								$output .= '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' .alum_plugin_url. 'uploads/avatars/'. $user_data->avatar . '&h=120&w=120&zc=1" alt="' . $user_displayname . '">';
							}
							else {
								$output .= '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . get_template_directory_uri(). '/lib/images/no-image-icon.png&h=120&w=120&zc=1" alt="' . $user_displayname . '">';
							}
							$output .= '</div>';
							$output .= '</div>';
							$output .= '<div class="blog-post-right">';
							$output .= '<h3><a href="blog/' . $blog->post_name . '">' . $blog->post_title . '</a></h3>
											<p>' . $blog->post_excerpt . '</p>
											<div class="ap-blog-meta">Posted ' . date("M d, Y", strtotime($blog->post_date)) . ' ' . date("g:i A", strtotime($blog->post_date)) . ' by <a href="' . get_bloginfo('url'). '/m/' . $anice_name . '">' . $adisplay_name . '</a></div>
										</div>
										<div class="clr"></div>
										</div>';
						}
						$output .= '<div class="widget-links">';	
						$output .= '<a href="javascript:void(0);" class="showPostFrmButton">Post to Blog</a> | <a href="blog" class="">Go to Blog <i class="fa fa-arrow-right"></i></a>';
						if($alum->is_group_admin($group->id)) {
						//	$output .= '<a href="">Approve Blog Posts</a>';
						}
						$output .= '</div>';
					}
					else {
						$output .= '<h5>No Messages</h5>';
						$output .= '<div class="widget-links">';	
						$output .= '<a href="javascript:void(0);" class="showPostFrmButton">Post to Blog</a>';
						$output .= '</div>';
					}
					$output .= '</div>';
					
					//Blog form...
					$output .= '<div id="blogFormContainer" class="wrapper"><form role="form" id="submitBlogFrm">
									<h4>Enter your comments below</h4>
									<p>The group admin will approve or dissaprove as soon as possible</p>
									<input type="hidden" name="group_id" value="' . $group->id . '">
									<input type="hidden" name="user_id" value="' . $current_user->ID . '">
									<div class="form-group">
										<label for="post_title">Title:</label>
										<input type="text" class="form-input" id="post_title" name="post_title" placeholder="Enter title">
									</div>
									<div class="form-group">
										<label for="post_content">Comments:</label>';
										$content = '';
					$editor_id = 'post_content';
					$content = '';
					//$output .= wp_editor( $content, $editor_id );
					$output .= '<textarea class="form-input" id="post_content" name="post_content"></textarea>';
					
					$output .= '</div>
									<button type="button" class="btn btn-primary sendBlogRequestBtn">Post Comments</button> <button type="button" class="btn btn-danger cancelBlogRequestBtn">Cancel</button>
							   </form></div>';
							   
					//$output .= '<hr></div>';
					$output .= '</div>';
					/*
					//Messages
					$sql = "SELECT user_id, pledge_class, message, date_added FROM alum_messages WHERE 1 AND group_id = '" . $group->id . "' AND status = 1 ORDER BY date_added DESC, RAND() DESC LIMIT 3";
					//echo $sql;
					$messages = $wpdb->get_results($sql);
					$output .= '<div class="content-box">';
					$output .= '<h3>Pledge Class Messages</h3>';
					if(!empty($messages)) {
						//Loop
						foreach($messages as $message) {
							$output .= '<div class="alum-page-message">
											<p>' . substr($message->message, 0, 155) . '...</p>
										</div>';
						}
						$output .= '<div class="widget-links">';
						$output .= '<a href="messages" class="">View All Messages <i class="fa fa-arrow-right"></i></a>';
						$output .= '</div>';
					}
					else {
						$output .= '<h5>No Messages</h5>';
						$output .= '<div class="widget-links">';
						$output .= '<a href="messages" class="">Post Pledge Message <i class="fa fa-arrow-right"></i></a>';
						$output .= '</div>';
					}
					
					$output .= '<hr></div>';
					*/
					/* Composites */
					$sql = "SELECT title, init_year, image_name FROM alum_composites WHERE 1 AND group_id = '" . $group->id . "' AND status = 1 ORDER BY RAND() DESC LIMIT 9";
					//echo $sql;
					$composites = $wpdb->get_results($sql);
					if(!empty($composites)) {
						$output .= '<div class="alum-section-title" id="photos-section">';
						$output .= '<div class="wrapper">';
						$output .= '<h3>Alum Composites</h3>';
						$output .= '</div>';
						$output .= '</div>';
						$output .= '<div class="wrapper">';
						$photo_dir = get_bloginfo('url') . '/wp-content/plugins/alumn/uploads/composites';
						$output .= '<div id="ap-slider" class="slider">';
						//$output .= '<ul class="ap-photos" id="apc-photos">';
						foreach($composites as $composite) {
							//$output .= '<li><a href="' . $photo_dir . '/' . $composite->image_name . '"><img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . $photo_dir . '/' . $composite->image_name . '&h=300&w=500&zc=1"></a></li>';
							$output .= '<div>
											<a href="' . $photo_dir . '/' . $composite->image_name . '"><img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . $photo_dir . '/' . $composite->image_name . '&h=300&w=500&zc=1"></a>
											<span class="c_caption">' . $composite->title. '</span>
										</div>';
						}
						//$output .= '</ul>';
						$output .= '</div>';
						//$output .= '<div class="widget-links">';
						//$output .= '<a href="photos" class="">View All Photos <i class="fa fa-arrow-right"></i></a> | <a href="javascript:void(0);" data-toggle="modal" data-target="#submit_photo_modal">Submit Photo</a>';
						if($alum->is_group_admin($group->id) || current_user_can('edit_users')) {
							$output .= '<div class="widget-links">';
							$output .= '<a href="javascript:void(0);" class="showCompFrmButton">Post Composite</a>';
							$output .= '</div>';
							
							$output .= '<div id="compFormContainer" class="wrapper" style="display: none;">
											<form role="form" id="submitCompFrm" method="post" enctype="multipart/form-data">
												<h4>Upload Your Composite</h4>
												<input type="hidden" name="group_id" value="' . $group->id . '">
												<input type="hidden" name="save_composite" value="1">
												<div class="form-group">
													<label for="post_title">Title:</label>
													<input type="text" class="form-input" id="post_title" name="post_title" placeholder="Enter title">
												</div>
												<div class="form-group">
													<label for="init_year">Initiation Year:</label>
													<input type="text" class="form-input" id="init_year" name="init_year" placeholder="Enter intitiation year (1999)">
												</div>
												<div class="form-group">
													<input type="file" name="composite_img" style="display: none;">
													<img id="photoPreview2" src="#" style="display: none; margin: 0 0 5px 0;" />
													<button type="button" class="btn btn-sm  btn-primary selectPhotoBtn2">Choose Photo</button>
												</div>
												<button type="button" class="btn btn-primary sendCompositeRequestBtn">Post Composite</button> <button type="button" class="btn btn-danger cancelCompositeRequestBtn">Cancel</button>
									   		</form>
										</div>';
						}
						
						$output .= '</div>';
					
					}
					
					/* Photos */
					$output .= '<div class="alum-section-title" id="photos-section">';
					$output .= '<div class="wrapper">';
					$output .= '<h3>Alum Photos</h3>';
					$output .= '</div>';
					$output .= '</div>';
					//Get Photos 
					$sql = "SELECT user_id, caption, photo_name, last_updated FROM alum_photos WHERE 1 AND group_id = '" . $group->id . "' AND status = 1 ORDER BY last_updated DESC, RAND() DESC LIMIT 9";
					//echo $sql;
					$photos = $wpdb->get_results($sql);
					$output .= '<div class="wrapper">';
					if(!empty($photos)) {
						$photo_dir = get_bloginfo('url') . '/wp-content/plugins/alumn/uploads/photos';
						$output .= '<ul class="ap-photos" id="ap-photos">';
						foreach($photos as $photo) {
							$output .= '<li><a href="' . $photo_dir . '/' . $photo->photo_name . '"><img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . $photo_dir . '/' . $photo->photo_name . '&h=300&w=500&zc=1"></a><span class="c_caption">' . $photo->caption. '</span></li>';
						}
						$output .= '</ul>';
						$output .= '<div class="widget-links">';
						$output .= '<a href="photos" class="">View All Photos <i class="fa fa-arrow-right"></i></a> | <a href="javascript:void(0);" data-toggle="modal" data-target="#submit_photo_modal">Submit Photo</a>';
						$output .= '</div>';
					}
					else {
						$output .= '<h5>No Photos</h5>';
						$output .= '<div class="widget-links">';
						$output .= '<a href="photos" class="">Post Photos <i class="fa fa-arrow-right"></i></a>';
						$output .= '</div>';
					}
					
					$output .= '</div>';
					
					/* Map/Calendar */
					//Map
					$output .= '<script>
								(function($) {
									$(document).ready( function() {
										if ($("#us_map").length > 0 ) {
											drawMap();
										}
									})
								
									function drawMap() {
										var markers = [];	
										var data = $.parseJSON(\'' . $state_data. '\');
										//console.log(data.states[\'FL\']);
										$(\'#us_map\').vectorMap({
											map: \'us_lcc\',
											backgroundColor:\'transparent\',
											zoomOnScroll:false,		
											regionStyle: {
											  initial: {
												  fill: \'#428bca\',"fill-opacity":1,
												  stroke: \'#fff\',"stroke-width": 1,"stroke-opacity": 1
											  },
											  hover: {
												  fill: \'#6fb3e0\',"fill-opacity":1
											  }
											},
											markersSelectable: false,
											markers:markers,
											markerStyle: {
												  initial: {
													fill: \'white\',
													stroke: \'black\',"stroke-width": 1,
													r: 6
												  },
												  hover: {
													stroke: \'black\',"stroke-width": 1
												  }					  
											},
											onRegionTipShow: function(event, label, index){
												//console.log(index);
												var state_abbr = index.replace(\'US-\',\'\');
												//console.log(state_abbr);
												label.html(
												  \'<b>\'+ data.states[state_abbr].state_name +\'</b><br/>\'+
												  \'Alum Members: \' + data.states[state_abbr].count + \'\'
												);

											},
											onRegionClick: function(e, code) {
												console.log(code);
												var stateAbbr = code.replace(\'US-\',\'\');
												var map = $(\'#us_map\').vectorMap(\'get\', \'mapObject\');
												var stateName = data.states[stateAbbr].state_name;
												var pathname = window.location.pathname; // Returns path only
												var url = window.location.href;
												url += \'members?state=\' + stateAbbr;
												console.log(url);
												location.href = url;
											}

										});
									}
								})(jQuery);
								</script>';
					$output .= '<div class="alum-section-title" id="map-section">';
					$output .= '<div class="wrapper">';
					$output .= '<h3>Alum Member Map</h3>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '<div class="wrapper">';
					$output .= '<div id="member_map">';
					$output .= '<div id="us_map"></div><p class="helper" style="margin: 10px auto 25px auto; text-align: center;">Use map to find Alums</p>';
					$output .= '</div>';

					$output .= '<div id="member_chart">';
					$output .= '<div class="div-overlay"><div class="div-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><h4>Loading Class Updates</h4><span class="sr-only">Loading...</span></div></div>';
					$output .= '<div id="member_chart-inner">';
					$output .= '<center><h4>Alum Class Update Percentage</h4></center>';
					$output .= '<div id="doughnutChart" class="chart"></div>';
					$output .= '<div class="clr"></div>';
					$output .= '<div id="tp-block">';
					$output .= '<div id="sl123" class="tp-selector">';
					$output .= '<h5><span>Top Classes</span> <i class="fas fa-caret-down"></i></h5>';
					$output .= '<div id="sl123-block" class="tp-selections">';
					$output .= '<ul id="tp-type">';
					$output .= '<li data-val="top-classes" data-type="order" class="active">Top Classes</li>';
					$output .= '<li data-val="bottom-classes" data-type="order">Bottom Classes</li>';
					$output .= '</ul>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '<div id="sr123" class="tp-selector">';
					$output .= '<h5>Initiation Year <i class="fas fa-caret-down"></i></h5>';
					$output .= '<div id="sr123-block" class="tp-selections">';
					$output .= '<ul id="tp-year">';
					//get initiation years
					//Get Class Years...
					$sql = "SELECT YEAR(STR_TO_DATE(initiation_date, \"%Y\")) AS year FROM alum_groups g, alumni a WHERE g.group_id = '". $group->id ."' AND a.id = g.alum_id AND g.status = 1";
					$sql .= " AND a.id != 2203";
					$sql .= " GROUP BY YEAR(a.initiation_date) ORDER BY year DESC";
					//echo "SQL : $sql";
					$class_years = $wpdb->get_results($sql);
					$output .= '<li data-val="all" data-type="year" class="active">All</li>';
					foreach($class_years as $class_year) {
						$output .= '<li data-val="' . $class_year->year . '" data-type="year">' . $class_year->year . '</li>';
					}
					$output .= '</ul>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '<div class="clr"></div>';


					$output .= '<ul id="top-p">';
					/*
					$cc = 0;
					foreach($yearly_percent as $year => $percentage) {
						$output .= '<li><span class="tp-c">Class of ' . $year . '</span><span class="tp-p">' . $percentage['percent'] . '%</span></li>';
						$cc++;
						if( $cc == 3) {
							break;
						}
					}
					*/
					$output .= '</ul>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '<div class="clr"></div>';
					/* Members */
					$output .= '<div class="alum-section-title" id="members-section">';
					$output .= '<div class="wrapper">';
					//print_r($_GET);
					$output .= '<h3>Alum Members</h3>';
					$output .= '</div>';
					$output .= '</div>';
					$output .= '<div id="members-block" style="position: relative; min-height: 200px;">';
					//$output .= '<div class="div-overlay"><div class="div-loading"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><h4>Loading Alum Members</h4><span class="sr-only">Loading...</span></div></div>';
					$output .= '</div>';
					/*
					if(!empty($members)) {
						$output .= '<div class="wrapper">';
						$output .= '<small>(D) = Deceased</small>';
						

						$output .= '<div id="filter-members">';
						$output .= '<b>Filter:</b>';
						
						$output .= '<div id="filter-members-init">';
						$output .= '<label>By Initiation Year:</label> ';
						$output .= '<select name="init_year_filter" id="init_year_filter">
											<option value="all"';
						if($_GET['show_class'] == 'all') {
							$output .= ' selected=""';
						}					
						$output .= '>All Classes</option>';
						foreach($class_years as $class_year) {
							$output .= '<option value="' . $class_year->year . '"';
							if(($_GET['show_class'] != '' && $_GET['show_class'] != 'all') && $class_year->year == $_GET['show_class']) {
								$output .= ' selected=""';
							}
							else if($class_year->year == date("Y", strtotime($loggedin_user_data->initiation_date))) {
								$output .= ' selected=""';
							}

							$output .= '>' . $class_year->year . '</option>';
						}

						$output .= '</select>';
						$output .= '</div>';
						
						$output .= '<div id="filter-members-prof">';
						$output .= '<label>By profession:</label> ';
						$output .= '<select name="occupation" id="occupation_filter">
											<option value="">Choose Occupation</option>';
						ksort($alum->professions);
						foreach($alum->professions as $profession=>$profession2) {
							$output .= '<option value="' . $profession. '"';
							if($_GET['occupation'] == $profession) {
								$output .= ' selected';
							}
							$output .= '>' . $profession. '</option>';
							foreach($profession2 as $prof2) {
								$output .= '<option value="' . $prof2. '"';
								if($_GET['occupation'] == $prof2) {
									$output .= ' selected';
								}
								$output .= '> - ' . $prof2. '</option>';
							}
						}
						$output .= '</select>';
						$output .= '</div>';
						
						$output .= '</div>';
						$output .= '<table class="table" id="members_table" >';
						$output .= '<thead><tr><th>Member Name</th><th class="hide-mobile">Profession</th><th>Location</th><th class="hide-mobile">Pledge Class</th><th >Init. Date</th><th class="hide-mobile">Last Updated</th><th>Claimed Profile</th></tr></thead>';
						$output .= '<tbody>';
						foreach($members as $member) {
							$last_updated = '';
							$last_updated_str = '';
							$claimed_profile = '';
							if($member->user_id != null) {
								$last_updated = date("m/d/y", strtotime($member->last_updated));
								$last_updated_str = date("Ymd", strtotime($member->last_updated));
								$claimed_profile = date("m/d/y", strtotime($member->user_registered));
							}
							elseif(strpos($member->dump_fields, 'Deceased') === FALSE) {
								$claimed_profile = '<button type="button" class="btn btn-primary btn-sm sipopup" data-toggle="modal" data-target="#send_invite_modal" data-id="' . $member->id . '" data-group="' . $group->id . '" data-name="" data-email="' . $member->email . '" data-phone="' . $member->phone . '">Send Invite</button>';
							}
							//print_r($member);
							$output .= '<tr><td>';
							$output .= '<span style="display: none;">' . $member->last_name . '</span>';
							//use user nicename
							if($member->user_id != '') {
								$user_info = get_userdata($member->user_id);
								$output .= '<a href="' . get_bloginfo('url'). '/m/' . $user_info->user_nicename . '">'; 
							}
							//use alumni id?!?!
							else {
								$output .= '<a href="' . get_bloginfo('url'). '/m/' . $member->id . '">'; 
							}
							$output .= $member->first_name;
							if($member->middle_name != '') {
								$output .= ' ' . substr($member->middle_name, 0, 1) . '.';
							}
							$output .= ' ' . $member->last_name;
							//if($member->user_id != '') { 
								$output .= '</a>';
							//}
							if(strpos($member->dump_fields, 'Deceased') !== FALSE) {
								$output .= ' <small>(D)</small>';
							}
							$output .= '</td>
										<td class="hide-mobile">';
							$occ = $member->occupation;
							if($member->occupation2 != '' && $member->occupation2 != 'null') {
								$occ .= ' - ' . $member->occupation2;
							}
							$output .= $occ; 
							$output .= '</td>
										<td>';
							if($member->city!= '' && $member->state != '') {			
								$output .= $member->city . ', ' . $member->state; 
							}
							$output .= '</td><td align="center" class="hide-mobile">' . $member->pledge_class . '</td><td align="center"><span style="display: none;">' . date("Ymd", strtotime($member->initiation_date)) . '</span> ' . date("m/d/Y", strtotime($member->initiation_date)) . '</td><td class="hide-mobile"><span style="display: none;">' . $last_updated_str . '</span> ' . $last_updated . '</td><td align="center">' . $claimed_profile . '</td></tr>';
						}
						$output .= '</thead>';
						$output .= '</table>';
						$output .= '</div>';

					}
					else {

					}
					*/
					
					
					/* Promotions */
					$output .= '<div class="alum-section-title" id="promo-section">';
					$output .= '<div class="wrapper">';
					$output .= '<h3>Support Your Alum Businesses</h3>';
					$output .= '</div>';
					$output .= '</div>';
					//Promos/Ads
					$sql = "SELECT * FROM alum_ads WHERE 1 AND group_id = '" . $group->id . "' AND status = 1 ORDER BY RAND() LIMIT 9";
					//echo $sql;
					$ads = $wpdb->get_results($sql);
					$output .= '<div class="wrapper">';
					if(!empty($ads)) {
						//Loop
						foreach($ads as $ad) {
							//Get user Data
							$user_data = $alum->getUserData($ad->user_id);
							$user_info = get_userdata($ad->user_id);
							//echo '<pre>'; print_r($user_data); echo '</pre>';
							$output .= '<div class="alum-page-ad" id="group-ad-' . $ad->id . '">
											<a href="' . get_bloginfo('url'). '/m/' . $user_info->user_nicename . '"><img src="' . get_bloginfo('url') . '/wp-content/plugins/alumn/uploads/ads/' . $ad->filename . '" alt=""></a><div style="font-size: 12px; text-align: center; color: #ccc;">Added by ' . $user_data->first_name . ' '. $user_data->last_name . '</div>';
							if($ad->user_id == $current_user->ID || $alum->is_group_admin($group->id)) {
								$output .= '<br><button type="button" class="btn btn-primary btn-xs deleteAd" data-id="' . $ad->id . '">Delete Ad</button>';
							}
							$output .= '</div>';
						}
						//End loop	
					}
					else {
						
						
					}
					$output .= '<div class="widget-links">';
					//$output .= '<a href="add-promo" class="">Post Promotion <i class="fa fa-arrow-right"></i></a>';
					$output .= '<a href="/profile/edit/?group_id=' . $group->id . '#promote_business" class="">Post Promotion <i class="fa fa-arrow-right"></i></a>';
					$output .= '</div>';
					$output .= '</div>';
					
					/* Job Listings */
					$output .= '<div class="alum-section-title" id="jobs-section">';
					$output .= '<div class="wrapper">';
					$output .= '<h3>Alum Professions and Job Listings</h3>';
					$output .= '</div>';
					$output .= '</div>';
					//Get Job Listings
					//random jobs
					$sql = "SELECT j.job_title, j.job_desc, j.city, j.state, j.contact_info, j.last_updated FROM alum_jobs j WHERE 1 AND j.group_id = '" . $group->id . "' AND j.status = 1 ORDER BY RAND() LIMIT 3";
					//echo $sql;
					$jobs = $wpdb->get_results($sql);
					//Randon looking for hiring....
					$sql = "SELECT g.alum_id, a.user_id, a.state, a.city, m1.meta_value AS job_title FROM alum_groups g, alumni a, wp_usermeta m, wp_usermeta m1 WHERE 1 AND g.group_id = '" . $group->id . "' AND g.status = 1 AND a.id = g.alum_id AND a.user_id != '' AND m.user_id = a.user_id AND m.meta_key = '_alum_are_you_hiring' AND m.meta_value = 'Yes' AND m1.user_id = a.user_id AND m1.meta_key = '_alum_hiring_position' AND m1.meta_value != '' ORDER BY RAND() LIMIT 3";
//echo $sql;
					$jobs2 = $wpdb->get_results($sql);
//echo '<pre>'; print_r($jobs2); echo '</pre>';
					if(empty($jobs) && !empty($jobs2)) {
						$jobs = $jobs2;
					}
					elseif(!empty($jobs) && !empty($jobs2)) {
						$jobs = array_merge($jobs, $jobs2);
					}
//echo '<pre>'; print_r($jobs); echo '</pre>';
					$output .= '<div class="wrapper">';
					$output .= '<div class="pw3">';
					/*
					$output .= '<div id="pjsearch" style="margin: 10px 15px 25px 0;">
									<form method="get" id="search-job-frm" action="jobs">
										<label for="occupation_search">Search Profession and Job Listings</label>
										<select name="occupation" id="occupation_search" class="form-input" onchange="this.form.submit();">
											<option value="">Choose Occupation</option>';
					foreach($alum->professions as $profession=>$profession2) {
						$output .= '<option value="' . $profession. '"';
						if($_GET['occupation'] == $profession) {
							$output .= ' selected';
						}
						$output .= '>' . $profession. '</option>';
					}
					$output .= '</select>
									</form>
								</div>';
					*/
					$output .= '<h4>JOB LISTINGS</h4>';
					if(!empty($jobs)) {
						//Loop
						foreach($jobs as $job) {
							$output .= '<div class="alum-page-job-post">
											<h3>' . $job->job_title . '</h3>';
							if($job->job_desc != '') {				
								$output .= '<p>' . substr($job->job_desc, 0, 155) . '...</p>';
							}
							$output .= '<div class="ap-job-meta">' . $job->city . ', ' . $job->state . '</div>
										</div>';
							//End loop
						}
						$output .= '<div class="widget-links">';
						$output .= '<a href="jobs" class="">View All Jobs <i class="fa fa-arrow-right"></i></a>';
						$output .= '</div>';
					}
					else {
						//$output .= '<a href="jobs" class="">Post Job</a>';
						$output .= '<h5>No Job Postings</h5>';
						$output .= '<div class="widget-links">';
						$output .= '<a href="jobs" class="">Post Job <i class="fa fa-arrow-right"></i></a>';
						$output .= '</div>';
					}
					$output .= '</div>';
					/* Links */
					$output .= '<div class="pw3">';
					$output .= '<h4>ALUM LINKS</h4>';
					$sql = "SELECT id, url, caption FROM alum_group_links WHERE group_id = '". $group->id ."'";
					//echo "$sql";
					$links = $wpdb->get_results($sql);
					if(!empty($links)) {
						$output .= '<ul id="group-links">';
						foreach($links as $link) {
							$output .= '<li><a href="' . $link->url . '" target="_blank">';
							if(strpos($link->url, 'facebook') !== FALSE) {
								$output .= '<i class="fab fa-facebook-square" aria-hidden="true"></i>';
							}
							if(strpos($link->url, 'twitter') !== FALSE) {
								$output .= '<i class="fab fa-twitter-square" aria-hidden="true"></i>';
							}
							if(strpos($link->url, 'instagram') !== FALSE) {
								$output .= '<i class="fab fa-instagram-square" aria-hidden="true"></i>';
							}
							if($link->caption != '') {
								$output .= $link->caption;
							}
							else {
								$output .= $link->url;
							}
							$output .= '</a></li>';
						}
						$output .= '</ul>';
						
					}
					//If group admin, show add links
					if($alum->is_group_admin($group->id)) {
						$output .= '<a href="add-links" class="btn btn-primary btn-sm" style="display: inline-block; margin-top: 15px;">Add Group Links</a>';
					}
					$output .= '</div>';
					/* Contact Form */
					$output .= '<div class="pw3">';
					$output .= '<h4>CONTACT US</h4>';
					$output .= '<form id="alum-contact-frm" method="post">';
					$output .= '<input type="hidden" name="group_id" value="' . $group->id. '">';		
					$output .= '<div class="form-group">';
					$output .= '<input type="text" name="sender_name" class="form-input" value="" placeholder="Your Name">';
					$output .= '</div>';
					$output .= '<div class="form-group">';
					$output .= '<input type="text" name="sender_email"  class="form-input" value="" placeholder="Your Email">';
					$output .= '</div>';
					$output .= '<div class="form-group">';
					$output .= '<textarea name="sender_comments"  class="form-input"></textarea>';
					$output .= '</div>';
					$output .= '<button type="button" class="btn btn-primary btn-sm sendAlumContact">Send Comments</button>';
					$output .= '</form>';
					$output .= '</div>';
					$output .= '<div class="clr"></div>';

					
					$output .= '</div>';
					
					

					$output .= '</div>';

				}
			}
			else {
				$g = explode('-', $group->group_name);
				$college = trim($g[1]);
				$frat = trim($g[0]);
				//echo 'TEST<pre>'; print_r($current_user); echo '</pre>';
				if($current_user->first_name != '' && $current_user->last_name != '') {
					$full_name = $current_user->first_name . ' ' . $current_user->last_name;
				}
				else {
					$full_name = $current_user->display_name;
				}
				$output .= '<div class="group-content wrapper">';
				$output .= '<center><button class="btn btn-primary joinGroupBtn" data-toggle="modal" data-target="#join_group_modal">Join ' . $group->group_name . '</button></center>';
				$output .= '</div>';
				$output .= '<!-- Modal -->
							<div class="modal fade" id="join_group_modal" role="dialog">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
											<h4>Join ' . $group->group_name . '</h4>
										</div>
										<div class="modal-body">
											<form role="form" id="joinGrpFrm">
												<input type="hidden" name="group_id" value="' . $group->id . '">												
												<input type="hidden" name="user_id" value="' . $current_user->ID . '">
												<input type="hidden" name="college" value="' . $college . '">
												<input type="hidden" name="frat" value="' . $frat . '">
												<div class="form-group">
													<label for="name">Name:</label>
													<input type="text" class="form-input" id="name" name="name" placeholder="Enter full name" value="' . $full_name . '">
												</div>
												<div class="form-group">
													<label for="email">Email:</label>
													<input type="text" class="form-input" id="email" name="email" placeholder="Enter email" value="' . $current_user->user_email . '">
												</div>
												<div class="form-group">
													<label for="comments">Comments:</label>
													<textarea class="form-input" id="comments" name="comments"></textarea>
												</div>
												<!--<div class="checkbox">
													<label><input type="checkbox" value="" checked>Remember me</label>
												</div>-->
												<button type="button" class="btn btn-primary btn-block sendJoinRequestBtn">Send Request</button> 
												<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> Cancel</button>
										  </form>
										</div>
										<div class="modal-footer">
											
										</div>
									</div>
								</div>
							</div>';
			}
			
			/* Edit Photo Modal */
			$output .= '<!-- Modal -->
					<div class="modal fade" id="edit_photo_modal" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
									<h4>Edit Photo</h4>
								</div>
								<div class="modal-body">
									<form role="form" id="editPhotoFrm" method="post" enctype="multipart/form-data">
										<input type="hidden" name="edit_photo" value="1">
										<input type="hidden" name="photo_id" value="">
										<input type="hidden" name="slug" value="' . $alum_slug . '">										
										
										<div class="form-group">
											<label for="caption">Photo Year:</label> 
											<select name="photo_year" class="form-input">
												<option value="">Choose Year</option>';
			for($i=date("Y")+1;$i >= 1900; $i--) {
				$output .= '<option value="'.$i.'">'.$i.'</option>';
			}
			$output .= '</select>
										</div>
										<div class="form-group">
											<label for="caption">Caption:</label>
											<input type="text" class="form-input" id="caption" name="caption" placeholder="Enter caption">
										</div>
										<button type="button" class="btn btn-primary editPhotoRequestBtn">Save Changes</button>
								  </form>
								</div>
							</div>
						</div>
					</div>';
			/* Submit Photo Modal */
			$output .= '<!-- Modal -->
					<div class="modal fade" id="submit_photo_modal" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
									<h4>Submit Photo</h4>
								</div>
								<div class="modal-body">
									<form role="form" id="submitPhotoFrm" method="post" enctype="multipart/form-data">
										<input type="hidden" name="submit_photo" value="1">
										<input type="hidden" name="group_id" value="' . $group->id . '">
										<input type="hidden" name="user_id" value="' . $current_user->ID . '">
										<div class="form-group">
											<label for="caption">Photo Year:</label> 
											<select name="photo_year" class="form-input">
												<option value="">Choose Year</option>';
			for($i=date("Y");$i >= 1900; $i--) {
				$output .= '<option value="'.$i.'">'.$i.'</option>';
			}
			$output .= '</select>
										</div>
										<div class="form-group">
											<label for="caption">Caption:</label>
											<input type="text" class="form-input" id="caption" name="caption" placeholder="Enter caption">
										</div>
										<div class="form-group">
											<label for="caption">Photo:</label>
											<input type="file" name="photo" style="display: none;">
											<img id="photoPreview" src="#" style="display: none; margin: 0 0 5px 0;" />
											<button type="button" class="btn btn-sm  btn-primary selectPhotoBtn">Choose Photo</button>
											<div class="helper">Max photo size is 2 MB</div>
										</div>
										<button type="button" class="btn btn-primary sendPhotoRequestBtn">Submit Photo</button>
								  </form>
								</div>
							</div>
						</div>
					</div>';
			/* Submit Event Modal */
			$output .= '<!-- Modal -->
					<div class="modal fade" id="submit_event_modal" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
									<h4>Submit Event</h4>
								</div>
								<div class="modal-body">
									<form role="form" id="submitEventFrm">
										<input type="hidden" name="group_id" value="' . $group->id . '">
										<input type="hidden" name="user_id" value="' . $current_user->ID . '">
										<div class="form-group">
											<label for="event_title">Event Title:</label>
											<input type="text" class="form-input" id="event_title" name="event_title" placeholder="Event Title">
										</div>
										<div class="form-group">
											<label for="event_date">Date:</label>
											<input type="date" class="form-input" id="event_date" name="event_date">
										</div>
										<div class="form-group">
											<label for="time">Time:</label>
											<select class="form-input" id="event_time" name="event_time">
												<option value="">Choose Time</option>';
											$meridian = 'am';
											for($hours=0; $hours<24; $hours++) {
												$display_hours = $hours;
												if($hours==0) {
													$display_hours = 12;
												}
												elseif($hours > 12) {
													$display_hours = $hours - 12;
												}
												if($hours == 12) {
													$meridian = 'pm';
												}
												for($mins=0; $mins<60; $mins+=30) {
													$output .= '<option>'.$display_hours.':'
               										.str_pad($mins,2,'0',STR_PAD_LEFT).' ' . $meridian. '</option>';
               									}
               								}
										$output .= '</select></div>
										<button type="button" class="btn btn-primary sendEventListingBtn">Submit Event</button>
								  </form>
								</div>
							</div>
						</div>
					</div>';
			/* Send Invite Modal */
			$output .= '<!-- Modal -->
					<div class="modal fade" id="send_invite_modal" role="dialog">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal"><i class="fa fa-times"></i></button>
									<h4>Send Invite</h4>
								</div>
								<div class="modal-body">
									<form method="post" id="invite-member-frm" style="max-width: 600px;">
										<input type="hidden" name="alumn_id" value="">
										<input type="hidden" name="group_id" value="">
										<input type="hidden" name="sender_id" value="' . $current_user->ID . '">
									<div class="form-group">
										<label for="member_email">Email Address:</label>
										<input class="form-input" id="member_email" name="member_email" placeholder="Enter Email Address" type="text" value="">
									</div>
									
									<div class="form-group">
										<label for="member_phone">Phone:</label>
										<input class="form-input" id="member_phone" name="member_phone" placeholder="Enter Phone" type="text" value="">
									</div>
									
									<button type="button" class="btn btn-primary sendInviteBtn">Send Invite</button>
									</form>
								</div>
							</div>
						</div>
					</div>';

		}
		else {
			$output .= '<div class="group-content wrapper">';
			$output .= '<center><button class="btn btn-primary" onclick="location.href=\'' . get_bloginfo('url') . '/login\';">Login To View</button></center>';
			$output .= '</div>';
		}
		$output .= '<div class="clr"></div>';
	}
	return $output;
}
add_shortcode( 'alum_page', 'alum_page_func');

function title_callback($title){
	global $wpdb, $alum;
	$current_user = wp_get_current_user();
	//print_r($_GET);
	//print_r($_SERVER);
	//print_r(get_query_vars());
    //$title='Your new title'; //define your title here
	$profile_user_name = get_query_var('user_name');
	//User profile
	//echo get_query_var('alum_slug');
	if(get_query_var('alum_slug') != '') {
		$alum_slug = get_query_var('alum_slug');
		$alum_slug = sanitize_text_field($alum_slug);
		$sql = "SELECT * FROM groups WHERE 1 AND group_slug = '$alum_slug'";
		//echo "SQL: $sql";
		$group = $wpdb->get_row($sql);
		//print_r($group);
		$title = $group->group_name;
	}
	elseif($profile_user_name != '') {
		$user_name = trim($profile_user_name, '/');
		if(is_numeric($user_name)) { //This profile does not belong to member
			$alumni_id = $user_name;
			$sql = "SELECT first_name, last_name FROM alumni WHERE id = '$alumni_id'";
			$alum = $wpdb->get_row($sql);
			$user_nicename = $alum ->first_name . ' ' . $alum ->last_name;
			$title = $user_nicename  . ' Profile';
		}
		else {
			$user_name = trim($profile_user_name, '/');
			$user = $wpdb->get_row( $wpdb->prepare(
				"SELECT `ID`, display_name, user_nicename, user_email FROM $wpdb->users WHERE `user_nicename` = %s", $user_name
			));
			//echo 'User is ' . $user->ID . '';
			//print_r($user);
			if($user->ID != '') {
				$user_id = $user->ID;
			}
			if($user->ID == $current_user->ID) {
				$is_owner = true;
			}
			$user_nicename = $user->user_nicename;
			$user_displayname = $user->display_name;
			if($user_displayname == '') {
				$user_data = $alum->getUserData($user_id);
				$user_displayname = $user_data->first_name;
				if($user_data->middle_name !== '') {
					$user_displayname .= ' ' . substr($user_data->middle_name, 0, 1) . '.';
				}
				$user_displayname .= ' ' . $user_data->last_name;
			}
			$title = $user_displayname . ' Profile';
			
		}
		if($current_user->ID == '' || $current_user->ID == 0) {
			//Redirect them out... or display nothing
			$output = 'Profile Unavailable';
			return  $output;
		}
	}
	else {
		//other pages
		/*
		if($current_user->ID == '' || $current_user->ID == 0) {
			//Redirect them out... or display nothing
			$output = 'Profile Unavailable';
			return  $output;
		}
		$user_id = $current_user->ID;
		$is_owner = true;
		$user_nicename = $current_user->data->user_nicename;
		$user_displayname = $current_user->data->display_name;
		$title = 'My Profile';
		*/
		$title = 'The Alum';
	}

    return $title;
}

add_filter('wp_title','title_callback');
?>