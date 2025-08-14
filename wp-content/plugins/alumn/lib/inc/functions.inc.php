<?php
if (!session_id()) {
	session_start();
}

//print_r($_POST);

/* Rewrite Rules */
function alum_rewrite_rules($rules) {
	global $wpdb;
	$newrules = array();
	//$newrules['listings'] = 'index.php?pagename=listings&action=search-results';
	//$newrules['homes-for-sale/(.+)$'] = 'index.php?pagename=listings&search=y&city=$matches[1]';
	//add_rewrite_rule('^profile/settings/?','index.php?pagename=profile&view=settings','top');
	$newrules['profile/(.+)$'] = 'index.php?pagename=profile&view=$matches[1]';
	$newrules['m/(.+)$'] = 'index.php?pagename=profile&user_name=$matches[1]';
	$newrules['members/([^/]+)/?$'] = 'index.php?pagename=members&get=$matches[1]';
	//$newrules['alum/(.+)/events/?month=[0-9]&year=[0-9]?$'] = 'index.php?pagename=alum&alum_slug=$matches[1]&section=events&month=$matches[2]&year=$matches[3]';
	$newrules['alum/([^/]+)/?$'] = 'index.php?pagename=alum&alum_slug=$matches[1]';
	$newrules['alum/([^/]+)/([^/]+)/?$'] = 'index.php?pagename=alum&alum_slug=$matches[1]&section=$matches[2]';
	$newrules['alum/([^/]+)/([^/]+)/([^/]+)/?$'] = 'index.php?pagename=alum&alum_slug=$matches[1]&section=$matches[2]&sub_section=$matches[3]';
	$newrules['logout/?$'] = 'index.php?logout=1';
	return $newrules + $rules;
}

add_filter('rewrite_rules_array','alum_rewrite_rules');

function alum_query_vars($vars){
	array_push(
		$vars,
		'view',
		'user_name',
		'get',
		'alum_slug',
		'section',
		'sub_section',
		'month',
		'year',
		'logout'
	);
	return $vars;
}
add_filter('query_vars','alum_query_vars');

function alum_rewrite_flush(){
	global $wp_rewrite;
   	$wp_rewrite->flush_rules();
}
add_action('init','alum_rewrite_flush');


add_filter('show_admin_bar', '__return_false');

function alum_login() { 
	global $alum, $wpdb;
	/* Try to Login */
	//print_r($_POST);
	//exit;
	if(isset($_POST['login_user']) && $_POST['login_user'] == '1') {
		$creds = array(
	        'user_login'    => $_POST['user_login'],
	        'user_password' => $_POST['user_password'],
	        'remember'      => true
	    );
		$user = wp_signon( $creds, false );
	    if ( is_wp_error( $user ) ) {
	        $_SESSION['alum_message'] = str_replace('<a href="' . get_bloginfo('url') . '/wp-login.php?action=lostpassword">Lost your password?</a>', '', $user->get_error_message());
	        $_SESSION['alum_message_type'] = 'danger';
	        $url = get_bloginfo('url') . '/login';
	        wp_redirect( $url );
	        //echo '<script>window.location = "' . $url . '"</script>';
	        exit();
	    }
	    else {
	    	//we logged them in, so forward them
	    	//echo '<pre>'; print_r($user);echo '</pre>';
	    	$userID = $user->ID;
			wp_set_current_user( $userID, $_POST['user_login'] );
			wp_set_auth_cookie( $userID, true, false );
			do_action( 'wp_login', $_POST['user_login'] );
			//exit;
	    	if(in_array('administrator', $user->roles)) {
	    		$url = get_bloginfo('url') . '/wp-admin/';
	    	}
	    	else {
	    		$url = get_bloginfo('url') . '/profile';
	    	}
	    	//echo "URL: $url";
	    	wp_redirect( $url );
	    	//echo '<script>window.location = "' . $url . '"</script>';
			exit();
	    }
    }
} 
add_action('init', 'alum_login');

function alum_register() { 
	global $alum, $wpdb;
	/* Try to Register */
	if(isset($_POST['register_user']) && $_POST['register_user'] == '1') {
//echo 'POST: <pre>'; print_r($_POST); echo '</pre>'; 
//exit;
		$initiation_date = $_POST['initiation_date'];
		//$pledge_class = $_POST['pledge_class'];
		$search_last_name = str_replace(' ', '', strtolower(trim($_POST['last_name'])));
		$search_first_name = substr(strtolower(trim($_POST['first_name'])), 0, 1);
		if($_POST['middle_name'] != '') {
			$search_middle_name = substr(strtolower(trim($_POST['middle_name'])), 0, 1);
		}
		//First see if they have a listing -- Unless they have already done ($_POST['claim_profile'] Set)
		if(!isset($_POST['claim_profile'])) {
			//$sql = "SELECT id, initiation_date FROM alumni WHERE last_name = '$last_name' AND pledge_class = '$pledge_class' AND initiation_date = '$initiation_date'";
			//SELECT * FROM projects WHERE YEAR(Date) = 2011 AND MONTH(Date) = 5
			$sql = "SELECT id, initiation_date FROM alumni WHERE LOWER(last_name) = '$search_last_name' AND YEAR(initiation_date) = '$initiation_date'";
			//Add middle intitial AND first intital search
			$sql .= " AND LOWER(first_name) LIKE '$search_first_name%'";
			if($_POST['middle_name'] != '') {
				$sql .= " AND LOWER(middle_name) LIKE '$search_middle_name%'";
			} 
			$sql .= " AND user_id IS NULL";
			$account = $wpdb->get_row($sql);
			//echo "SQL: $sql<BR>";
			//echo 'ACCT: <pre>'; print_r($account); echo '</pre>'; 
			//exit;
			
			if(!empty($account)) {
				$_SESSION['alum_profile_id'] = $account->id;
				$_SESSION['alum_user_data'] = $_POST;
				$url = get_bloginfo('url') . '/profile-found';
				wp_redirect( $url );
				//echo '<script>window.location = "' . $url . '"</script>';
				exit();
			}
			return false;
		}
		/*
		
		*/
		//echo "WE ARE HERE";
		//exit;
		//else {
		$user_login = $_POST['user_login'];
		$user_email = $_POST['user_login'];
		$user_password = $_POST['user_password'];		
		$user_id = username_exists( $user_login);
		if(!$user_id && email_exists($user_email) == false ) {
			$user_id = wp_create_user( $user_login, $user_password, $user_email );
			$first_name = $_POST['first_name'];
			$middle_name = $_POST['middle_name'];
			$last_name = $_POST['last_name'];
			$display_name = $first_name;
			if($middle_name != '') {
				$display_name .= ' ' . substr($middle_name, 0, 1) . '.';
			}
			$display_name .= ' ' . $last_name;
			//echo $display_name . "<BR>";
			$username = $alum->generateUniqueUsername($_POST);
			$initiation_date = date("Y-m-d", strtotime($_POST['initiation_date']));
			$user_upd = wp_update_user(array( 'ID' => $user_id, 'user_nicename' => $username, 'display_name' => $display_name, 'user_status' => '1' ) );
			//$wpdb->show_errors(); 
			//$wpdb->print_error(); 
//echo "149<BR>";
//print_r($_POST);
//echo "<BR>";
	//exit;
			//Claim profile is set, so if they claimed it set it
			if($_POST['claim_profile'] == 1) {
				//$user_id = $_POST['user_id'];
				$wpdb->update( 
					'alumni', 
					array( 
						'user_id' => $user_id,
						'email' => $user_email,
					), 
					array( 'id' => $_POST['alum_profile_id'] ), 
					array( 
						'%s',
						'%s'
					), 
					array( '%d' ) 
				);	
				//$wpdb->show_errors(); 
				//$wpdb->print_error();
				//Get data for email
				$user_data = $alum->getUserData($user_id);
				$first_name = $user_data->first_name;
				$middle_name = $user_data->middle_name;
				$last_name = $user_data->last_name;
				$initiation_date = $user_data->initiation_date;
				$display_name = $first_name;
				if($middle_name != '') {
					$display_name .= ' ' . substr($middle_name, 0, 1) . '.';
				}
				$display_name .= ' ' . $last_name;
				$_POST['first_name'] = $first_name;
				$_POST['last_name'] = $last_name;
				$_POST['middle_name'] = $last_name;
				$_POST['initiation_date'] = date("Y", strtotime($initiation_date));
				$username = $alum->generateUniqueUsername($_POST);
				$user_upd = wp_update_user(array( 'ID' => $user_id, 'user_nicename' => $username, 'display_name' => $display_name, 'user_status' => '1' ) );
			}
			else {
				$wpdb->insert( 
					'alumni', 
					array( 
						'user_id' => $user_id,
						'first_name' => $first_name, 
						'middle_name' => $middle_name,
						'last_name' => $last_name,
						'initiation_date' => $initiation_date,
						'salutation' => $_POST['Salutation'],
						'suffix' => $_POST['Suffix'],
						'pledge_class' => $_POST['pledge_class'],
						'email' => $user_email,
						'phone' => $_POST['Phone Number'],
						'address' => $_POST['Address'],
						'city' => $_POST['City'],
						'state' => $_POST['State'],
						'zipcode' => $_POST['Zip Code'],
						'college' => $_POST['college_name'],
						'fraternity' => $_POST['frat_name'],
						'dump_fields' => $dump_fields
					), 
					array( 
						'%d',
						'%s', 
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%d',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s',
						'%s' 
					) 
				);
			//	$wpdb->show_errors(); 
			//	$wpdb->print_error();
			}
			if($_POST['update_email'] != '') {
				$wpdb->update( 
					'alumni', 
					array( 
						'email' => $_POST['update_email'],
					), 
					array( 'id' => $_POST['alum_profile_id'] ), 
					array( 
						'%s'
					), 
					array( '%d' ) 
				);
			}
			if($_POST['update_phone'] != '') {
				$wpdb->update( 
					'alumni', 
					array( 
						'phone' => $_POST['update_phone'],
					), 
					array( 'id' => $_POST['alum_profile_id'] ), 
					array( 
						'%s'
					), 
					array( '%d' ) 
				);
			}
			//$wpdb->show_errors(); 
			//$wpdb->print_error();
			$_SESSION['alum_message'] = 'You have been registered';
			$_SESSION['alum_message_type'] = 'success';
			unset($_SESSION['alum_user_data']);
			unset($_SESSION['alum_profile_id']);
			unset($_SESSION['alum_user_register']);
			$url = get_bloginfo('url') . '/login';
//echo "URL: $url<BR>";
		//	$url = get_bloginfo('url') . '/profile';
		//	wp_redirect( $url );
			//Email Somebody
			$admin_email = get_option( 'admin_email' );
			$subject = 'Someone has registered on OurAlum';
			$headers = "From: OurAlum<info@ouralum.com>\r\n";
			$headers .= "Reply-To: OurAlum<info@ouralum.com>\r\n";
			$headers .= "CC: mikejr@malouf.law\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$message = '<html><body>';
			$message .= '<h1>OurAlum.com</h1>';
			$message .= 'Hello Admin,<br><br>';
			$message .= $display_name . ' has registered on OurAlum.com. Below are the details:<br><br>';
			//$message .= 'Pledge Class: ' . $_POST['pledge_class'] . '<br>';
			$message .= 'Initiation Date: ' . date("m/d/Y", strtotime($initiation_date)) . '<br>';
			if($_POST['claim_profile'] == 1) {
				$message .= 'They did claim a profile';
			}
			else {
				$message .= 'They did not claim a profile';
			}
			$message .= '</body></html>';
			mail($admin_email, $subject, $message, $headers);
			//Email Welcome Message to User
			$subject = 'Welcome to OurAlum';
			$headers = "From: OurAlum<info@ouralum.com>\r\n";
			$headers .= "Reply-To: OurAlum<info@ouralum.com>\r\n";
			$headers .= "CC: mikejr@malouf.law\r\n";
			$headers .= "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$message = '<html><body>';
			$message .= '<h1>OurAlum.com</h1>';
			$message .= 'Hello ' . $first_name . ',<br><br>';
			$message .= 'You have successfully registered on OurAlum.com. Your details are below:<br><br>';
			$message .= 'Username: ' . $username . '<br>';
			//$message .= 'Pledge Class: ' . $_POST['pledge_class'] . '<br>';
			$message .= 'Initiation Date: ' . date("m/d/Y", strtotime($initiation_date)) . '<br><br>';
			$message .= 'Your public profile can be viewed at <a href="' . get_bloginfo('url') . '/m/' . $username . '">' . get_bloginfo('url') . '/m/' . $username . '</a><br><br>';
			$message .= 'You can log in at ' . get_bloginfo('url') . '/login<br><br>';
			
			$message .= '</body></html>';
			mail($user_email, $subject, $message, $headers);
			//echo '<script>window.location = "' . $url . '"</script>';
			//echo "REDIR: $url";
			wp_redirect( $url );
			exit();
		} 
		else {
			//$random_password = __('User already exists.  Password inherited.');
			$_SESSION['alum_message'] = 'User already exists';
			$_SESSION['alum_message_type'] = 'danger';
			$url = get_bloginfo('url') . '/register';
			wp_redirect( $url );
			//echo '<script>window.location = "' . $url . '"</script>';
			exit();
		}
		//}
    }
} 
add_action('init', 'alum_register');

function alum_update_avatar() {
	if(isset($_FILES['avatar'])) {
		$current_user = wp_get_current_user();
		$user_id = $current_user->ID;
		//save file, and enter into database
		$target_dir = alum_plugin_path . 'uploads/avatars/';
		$file_name = time() . '_' . str_replace(array(' '), array('_'), basename($_FILES['avatar']['name']));
		$target_file = $target_dir . $file_name;
		$path_parts = pathinfo($target_file);
		$fn = $path_parts['filename'];
		$uploadOk = 1;
		$fileType = pathinfo($target_file,PATHINFO_EXTENSION);	
		// Check if file already exists
		if (file_exists($target_file)) {
			//echo "Sorry, file already exists.";
			$_SESSION['alum_message'] = '<div class="alert alert-danger">Sorry, file already exists.</div>';
			$uploadOk = 0;
		}
		// Check file size
		if ($_FILES["alum_import_file"]["size"] > 500000) {
			$_SESSION['alum_message'] = '<div class="alert alert-danger">Sorry, your file is too large.</div>';
			$uploadOk = 0;
		}
		// Allow certain file formats
		if($fileType != "jpg" && $fileType != "jpeg" && $fileType != "png" && $fileType != "gif") {
			$_SESSION['alum_message'] = '<div class="alert alert-danger">Sorry, only jpg, png and gif files are allowed. You uploaded a ' . $fileType . '</div>';
			$uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
			$_SESSION['alum_message'] = '<div class="alert alert-danger">Sorry, your file was not uploaded.</div>';
		// if everything is ok, try to upload file
		} 
		else {
			if(!move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
				$_SESSION['alum_message'] = '<div class="alert alert-danger">Sorry, there was an error uploading your file.</div>';
			} 
			else {
				update_user_meta( $user_id, '_alum_avatar', $file_name);
				$_SESSION['alum_message'] = '<div class="alert alert-success">Your profile picture has been updated.</div>';
			}
		}
		//echo '<script>location.reload();</script>';
		//echo '<script>window.location = "' . get_bloginfo('url') . '/profile";</script>';
		wp_redirect(get_bloginfo('url') . '/profile');
		exit;
	}
}
add_action('init', 'alum_update_avatar');
	
function alum_update_settings() {
	global $alum;
	if(isset($_POST['save_settings']) && $_POST['save_settings'] == 1) {
//print_r($_POST);
		$alum->saveSettings($_POST['user_id']);
		if($_REQUEST['password'] != '') { //Logout & forward to homepage...
			 wp_logout();
			 $redir = get_bloginfo('url') . '/';
		}
		else { //forward to profile
			$_SESSION['alum_message'] = '<div class="alert alert-success">Your privacy settings have been updated.</div>';
			$redir = get_bloginfo('url') . '/profile';
		}
//echo "REDIR: $redir";
		//echo '<script>window.location = "' . $redir . '";</script>';
	    wp_redirect( $redir );
		exit;
	}
	
}
add_action('init', 'alum_update_settings');	

function alum_send_reset() {
	global $wpdb;
	if(isset($_POST['reset_user']) && $_POST['reset_user'] == 1) {
		//print_r($_POST);
		$email = $_POST['user_login'];
		//creater hash and put into DB
		$hash = substr(md5(time()), 0, 12);
		$wpdb->update( 
			'wp_users', 
			array( 
				'user_activation_key' => $hash,
			), 
			array( 'user_email' => $email ), 
			array( 
				'%s',
			), 
			array( '%s' ) 
		);
		//$wpdb->show_errors(); 
		//$wpdb->print_error();
		//Email link to user
		$subject = 'Your Password Reset Link from OurAlum.com';
		$headers = "From: OurAlum<info@ouralum.com>\r\n";
		$headers .= "Reply-To: OurAlum<info@ouralum.com>\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$message = '<html><body>';
		$message .= '<h1>OurAlum.com</h1>';
		$message .= 'Hello,<br><br>';
		$message .= 'A request was made to rest the password on the account associated with this email. If you did not make this request, just ignore this message.<br><br>';
		$message .= 'To reset your password, go to <a href="' . get_bloginfo('url') . '/login/?reset_password&ut=' . base64_encode( $email . '::' . $hash ) . '">' . get_bloginfo('url') . '/login/?reset_password&ut=' . base64_encode( $email . '::' . $hash ) . '</a>';
		$message .= '</body></html>';
		mail($email, $subject, $message, $headers);
		$results['resp'] = 'success';
		$results['mssg'] = 'Please check your email for password reset link';
		$redir = get_bloginfo('url') . '/login';
		wp_redirect( $redir );
		exit;
		return false;
	}
}
add_action('init', 'alum_send_reset');

function alum_update_password() {
	global $wpdb;
	if(isset($_POST['reset_password']) && $_POST['reset_password'] == 1) {
		$email = $_POST['user_login'];
		$password = $_POST['password'];
		$user = get_user_by( 'email', $email );
		wp_set_password( $password, $user->ID );
		//Empty hash
		$wpdb->update( 
			'wp_users', 
			array( 
				'user_activation_key' => '',
			), 
			array( 'user_email' => $email ), 
			array( 
				'%s',
			), 
			array( '%s' ) 
		);
		//Email link to user
		$subject = 'Your password has been reset';
		$headers = "From: OurAlum<info@ouralum.com>\r\n";
		$headers .= "Reply-To: OurAlum<info@ouralum.com>\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$message = '<html><body>';
		$message .= '<h1>OurAlum.com</h1>';
		$message .= 'Hello,<br><br>';
		$message .= 'Your password has been update. You may log in now using your new password.<br><br>';
		//$message .= 'To reset your password, go to ' . get_bloginfo('url') . '/login/?reset_password&ut=' . base64_encode( $email . '::' . $hash );
		$message .= '</body></html>';
		mail($email, $subject, $message, $headers);
		$results['resp'] = 'success';
		$results['mssg'] = 'Please check your email for password reset link';
		$redir = get_bloginfo('url') . '/login';
		wp_redirect( $redir );
		exit;
		return false;
		
	}
}
add_action('init', 'alum_update_password');

function alum_group_upload_photo() {
	global $wpdb;
	if($_POST['submit_photo'] == 1) {
		$uploads_dir = alum_plugin_path . 'uploads/photos';
		$file = $_FILES['photo'];
		$redir = $_SERVER['HTTP_REFERER'];
		//echo $redir;
		//Check size

		//check type
		$image_x = 1024;
		$handle = new Upload($file);
		if ($handle->uploaded) {
			$handle->image_convert         = 'jpg';
			$handle->image_resize          = true;
			$handle->image_ratio_y         = true;
			$handle->image_x               = $image_x;
			$handle->jpeg_quality          = 90;
			$handle->Process($uploads_dir);
			$newname = $handle->file_dst_name;
			$_SESSION['alum_message'] = 'Your photo has been submitted';
			$_SESSION['alum_message_type'] = 'success';
		} 
		else {
			$_SESSION['alum_message'] = 'Error (138): ' . $handle->error . '';
			$_SESSION['alum_message_type'] = 'error';
		}
		$handle-> Clean(); 
		
		//Check Orientation
		$filename = $uploads_dir . '/' . $newname;
		correctImageOrientation($file);
		
		//Save to DB
		$wpdb->insert( 
			'alum_photos', 
			array( 
				'user_id' => $_POST['user_id'],
				'group_id' => $_POST['group_id'],
				'photo_name' => $newname,
				'caption' => $_POST['caption'],
				'photo_year' => $_POST['photo_year'],
				'status' => 1,
			), 
			array( 
				'%d',
				'%d', 
				'%s',
				'%s',
				'%s',
				'%d',
			) 
		);
		//$wpdb->show_errors(); 
		//$wpdb->print_error();	
		//Get Alum admin email
		/*
		$sql = "SELECT u.user_email, g.group_name FROM groups g, wp_users u WHERE g.id = '$group_id' AND u.ID = g.admin_id LIMIT 1";
		$gr = $wpdb->get_row($sql);
		//Email it...
		$subject = 'Someone has requested to join ' . $gr->group_name;
		$headers = "From: TheAlum<info@ouralum.com>\r\n";
		$headers .= "Reply-To: TheAlum<info@ouralum.com>\r\n";
		$headers .= "CC: judsonc75@gmail.com\r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$message = '<html><body>';
		$message .= '<h1>ouralum.com</h1>';
		$message .= 'Hello Admin,<br><br>';
		$message .= $name . ' has request to join ' . $gr->group_name . ' on ouralum.com.<br><br>';
		$message .= 'Email: ' . $email . '<br>';
		if($comments != '') {
			$message .= 'Comments: ' . $comments . '<br>';
		}
		$message .= '</body></html>';
		mail($gr->user_email, $subject, $message, $headers);
		if(!isset($results)) {
			$results['resp'] = 'success';
			$results['mssg'] = 'Your user info was updated';
		}
		*/
		//$output .= '<div class="alert alert-success">Your photo has been submitted</div>';
		
		//header("Location: .");
		unset($_POST);
		unset($_FILES);
		
		wp_redirect( $redir );
		exit;
		return false;
	}

}
add_action('init', 'alum_group_upload_photo');

function alum_group_edit_photo() {
	global $wpdb;
	if($_POST['edit_photo'] == 1) {
		$alum_slug = $_POST['slug'];
		$wpdb->update( 
			'alum_photos', 
			array( 
				'pledge_class' => $_POST['pledge_class'],
				'caption' => $_POST['caption'],
			), 
			array( 'id' => $_POST['photo_id'] ), 
			array( 
				'%s',
			), 
			array( '%d' ) 
		);
		$_SESSION['alum_message'] = 'Your photo has been updated';
		$_SESSION['alum_message_type'] = 'success';
		//header("Location: " . get_bloginfo('url') . "/alum/" . $alum_slug . "/");
		//echo '<script>window.location.replace("' . get_bloginfo('url') . '/alum/' . $alum_slug . '/");</script>';
		$redir = get_bloginfo('url') . '/alum/' . $alum_slug . '/photos/';
		wp_redirect( $redir );
		exit;
		return false;
	
	}
}
add_action('init', 'alum_group_edit_photo');

function alum_promo_ad() {
	global $wpdb;
	if($_POST['add_promo'] == 1) {
		//Save file
		$uploads_dir = alum_plugin_path . 'uploads/ads';
		$file = $_FILES['ad'];
		//$redir = $_SERVER['HTTP_REFERER'];
		$alum_slug = $_POST['slug'];
		$redir = get_bloginfo('url') . '/alum/' . $alum_slug;
		$image_x = 360;
		//$tmp_name = $_FILES['ad']['tmp_name'];
		//$name = basename($_FILES['ad']['name']);
		//move_uploaded_file($tmp_name, "$uploads_dir/$name");

		$group_id = $_POST['group_id'];
		$handle = new Upload($file);
		if ($handle->uploaded) {
			$handle->image_convert         = 'jpg';
			$handle->image_resize          = true;
			$handle->image_ratio_y         = true;
			$handle->image_x               = $image_x;
			$handle->jpeg_quality          = 90;
			$handle->Process($uploads_dir);
			$newname = $handle->file_dst_name;
			$_SESSION['alum_message'] = 'Your ad has been submitted';
			$_SESSION['alum_message_type'] = 'success';
		} 
		else {
			$_SESSION['alum_message'] = 'Error (138): ' . $handle->error . '';
			$_SESSION['alum_message_type'] = 'error';
		}
		$handle-> Clean(); 
		
		$wpdb->insert( 
			'alum_ads', 
			array( 
				'group_id' => $group_id, 
				'user_id' => $_POST['user_id'],
				'url' => $_POST['url'],
				'filename' => $newname
			), 
			array( 
				'%d',
				'%d',
				'%s', 
				'%s'
			) 
		);
		$ad_id = $wpdb->insert_id;
						
//$wpdb->show_errors();
//$wpdb->print_error(); 



		$_SESSION['alum_message'] = 'Your ad has been added';
		$_SESSION['alum_message_type'] = 'success';
		//header("Location: " . get_bloginfo('url') . "/alum/" . $alum_slug . "/");
		//echo '<script>window.location.replace("' . get_bloginfo('url') . '/alum/' . $alum_slug . '/");</script>';
		//exit;
		//$redir = get_bloginfo('url') . '/alum/' . $alum_slug;
		wp_redirect( $redir );
		exit;
		return false;
	}
}

add_action('init', 'alum_promo_ad');

function alum_group_upload_ad() {
	global $wpdb;
	if($_POST['add_promo'] == 1) {
		//Save file
		$uploads_dir = alum_plugin_path . 'uploads/ads';
		$file = $_FILES['ad'];
		//$redir = $_SERVER['HTTP_REFERER'];
		$alum_slug = $_POST['slug'];
		$redir = get_bloginfo('url') . '/alum/' . $alum_slug;
		$image_x = 360;
		//$tmp_name = $_FILES['ad']['tmp_name'];
		//$name = basename($_FILES['ad']['name']);
		//move_uploaded_file($tmp_name, "$uploads_dir/$name");

		$group_id = $_POST['group_id'];
		$handle = new Upload($file);
		if ($handle->uploaded) {
			$handle->image_convert         = 'jpg';
			$handle->image_resize          = true;
			$handle->image_ratio_y         = true;
			$handle->image_x               = $image_x;
			$handle->jpeg_quality          = 90;
			$handle->Process($uploads_dir);
			$newname = $handle->file_dst_name;
			$_SESSION['alum_message'] = 'Your ad has been submitted';
			$_SESSION['alum_message_type'] = 'success';
		} 
		else {
			$_SESSION['alum_message'] = 'Error (138): ' . $handle->error . '';
			$_SESSION['alum_message_type'] = 'error';
		}
		$handle-> Clean(); 
		
		$wpdb->insert( 
			'alum_ads', 
			array( 
				'group_id' => $group_id, 
				'user_id' => $_POST['user_id'],
				'url' => $_POST['url'],
				'filename' => $newname
			), 
			array( 
				'%d',
				'%d',
				'%s', 
				'%s'
			) 
		);
		$ad_id = $wpdb->insert_id;
						
//$wpdb->show_errors();
//$wpdb->print_error(); 



		$_SESSION['alum_message'] = 'Your ad has been added';
		$_SESSION['alum_message_type'] = 'success';
		//header("Location: " . get_bloginfo('url') . "/alum/" . $alum_slug . "/");
		//echo '<script>window.location.replace("' . get_bloginfo('url') . '/alum/' . $alum_slug . '/");</script>';
		//exit;
		//$redir = get_bloginfo('url') . '/alum/' . $alum_slug;
		wp_redirect( $redir );
		exit;
		return false;
	}

}
add_action('init', 'alum_group_upload_ad');

function alum_group_update_desc() {
	global $wpdb;
	//print_r($_POST);
	$alum_slug = $_POST['slug'];
	if($_POST['update_desc'] == 1) {
		$wpdb->update( 
			'groups', 
			array( 
				'group_description' => $_POST['group_description'],
			), 
			array( 'id' => $_POST['group_id'] ), 
			array( 
				'%s',
			), 
			array( '%d' ) 
		);
		$_SESSION['alum_message'] = 'Your group description has been updated';
		$_SESSION['alum_message_type'] = 'success';
		//header("Location: " . get_bloginfo('url') . "/alum/" . $alum_slug . "/");
		//echo '<script>window.location.replace("' . get_bloginfo('url') . '/alum/' . $alum_slug . '/");</script>';
		$redir = get_bloginfo('url') . '/alum/' . $alum_slug;
		wp_redirect( $redir );
		exit;
		return false;
	}
					
}
add_action('init', 'alum_group_update_desc');

					
//Ajax
function claim_profile() {
	global $wpdb;
	$last_name = $_POST['last_name'];
	//print_r($_POST);
	$initiation_date = $_POST['initiation_date'];
	$search_last_name = str_replace(' ', '', strtolower(trim($_POST['last_name'])));
	$search_first_name = substr(strtolower(trim($_POST['first_name'])), 0, 1);
	if($_POST['middle_name'] != '') {
		$search_middle_name = substr(strtolower(trim($_POST['middle_name'])), 0, 1);
	}
	
	//$pledge_class = $_POST['pledge_class'];
	//First We find last name, and sub verify on others
	//$account = $wpdb->get_row( "SELECT id, initiation_date FROM alumni WHERE last_name = '$last_name'");
	//$sql = "SELECT id, initiation_date FROM alumni WHERE last_name = '$last_name'  AND pledge_class = '$pledge_class' AND initiation_date = '$initiation_date'";
	$sql = "SELECT id, initiation_date FROM alumni WHERE LOWER(last_name) = '$search_last_name' AND YEAR(initiation_date) = '$initiation_date'";
	//Add middle intitial AND first intital search
	$sql .= " AND LOWER(first_name) LIKE '$search_first_name%'";
	if($_POST['middle_name'] != '') {
		$sql .= " AND LOWER(middle_name) LIKE '$search_middle_name%'";
	}
	$sql .= " AND user_id IS NULL";
	//echo $sql;
	$account = $wpdb->get_row($sql);
	//print_r($account);
	//exit;
	//foreach ( $fivesdrafts as $fivesdraft ) {
	//	echo $fivesdraft->post_title;
	//}
	if(!empty($account)) {
		//we send them to claim profile...
		$_SESSION['alum_profile_id'] = $account->id;
		$_SESSION['alum_user_register'] = true;
		//$url = get_bloginfo('url') . '/profile-found';
		//wp_redirect( $url );
		//echo '<script>window.location = "' . $url . '"</script>';
		//exit();
		$results['resp'] = 'success';
		$results['mssg'] = 'Your profile was found';
	}
	else {
		$results['resp'] = 'error';
		$results['mssg'] = 'Your profile was not found or has already been claimed. If you have claimed your profile, <a href="' . get_bloginfo('url') . '/login">click here to login</a>';
	}
	echo json_encode($results);
	exit;
}
add_action('wp_ajax_claim_profile', 'claim_profile');
add_action('wp_ajax_nopriv_claim_profile', 'claim_profile');


function get_photo_data() {
	global $wpdb;
	$photo_id = $_POST['id'];
	//get name
	$sql = "SELECT * FROM alum_photos WHERE id = '$photo_id' LIMIT 1";
	$p = $wpdb->get_row($sql);
	if(!empty($p)) {
		$results['resp'] = 'success';
		$results['photo'] = $p;
	}
	echo json_encode($results);
	exit;

}
add_action('wp_ajax_get_photo_data', 'get_photo_data');
add_action('wp_ajax_nopriv_get_photo_data', 'get_photo_data');

function get_occ2() {
	global $wpdb, $alum;
	$this_profession = $_POST['profession'];
	//echo "PROF: $this_profession";
	
	foreach($alum->professions as $profession=>$profession2) {
	//echo "PROF: $profession \r\n";
		if($this_profession == $profession) {
			foreach($profession2 as $profession2_title) {
				$results['occ2'][] = $profession2_title;
			}
		}
	}
	$results['resp'] = 'success';
	echo json_encode($results);
	exit;

}
add_action('wp_ajax_get_occ2', 'get_occ2');
add_action('wp_ajax_nopriv_get_occ2', 'get_occ2');


function update_user_name() {
	global $wpdb;
	$user_name = $_POST['user_name'];
	$user_id = $_POST['user_id'];
	$wpdb->update( 
		'wp_users', 
		array( 
			'user_nicename' => $user_name,
		), 
		array( 'ID' => $user_id ), 
		array( 
			'%s',
		), 
		array( '%d' ) 
	);
	
	$wpdb->update( 
		'alumni', 
		array( 
			'last_updated' => date("Y-m-d H:i:s"),
		), 
		array( 'user_id' => $user_id ), 
		array( 
			'%s',
		), 
		array( '%d' ) 
	);
	
	$results['resp'] = 'success';
	$results['mssg'] = 'Your username was updated';
	echo json_encode($results);
	exit;
}
add_action('wp_ajax_update_user_name', 'update_user_name');
add_action('wp_ajax_nopriv_update_user_name', 'update_user_name');

function update_user_data() {
	global $wpdb, $alum;
	$user_id = $_POST['user_id'];
	$email = $_POST['email'];
	//$phone= $_POST['phone'];
	$address = $_POST['address'];
	$city= $_POST['city'];
	$state= $_POST['state'];
	$zipcode= $_POST['zipcode'];
	$occupation = $_POST['occupation'];
	$occupation2 = $_POST['occupation2'];
	$occupation_description = $_POST['occupation_description'];
	$display_name = $_POST['display_name'];
	$username = $_POST['username'];
	$group_id = $_POST['group_id'];
	$employer_name = $_POST['employer_name'];
	$employer_address = $_POST['employer_address'];
	$birthdate = $_POST['birthdate'];
	$spouse_name = $_POST['spouse_name'];
	$phone = $_POST['mobile_phone'];
	$home_phone = $_POST['home_phone'];
	$work_phone = $_POST['work_phone'];
	
	$are_you_hiring = $_POST['are_you_hiring'];
	$hiring_position = $_POST['hiring_position'];
	$seeking_employment = $_POST['seeking_employment'];
	$type_employment_seeking = $_POST['type_employment_seeking'];
	
	$wpdb->update( 
		'alumni', 
		array( 
			'email' => $email,
			'phone' => $phone,
			'address' => $address,
			'city' => $city,
			'state' => $state,
			'zipcode' => $zipcode,
			'occupation' => $occupation,
			'occupation2' => $occupation2,
			'occupation_description' => $occupation_description,
			'last_updated' => date("Y-m-d H:i:s"),
		), 
		array( 'user_id' => $user_id ), 
		array( 
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
		), 
		array( '%d' ) 
	);
	//$wpdb->show_errors(); 
	//$wpdb->print_error();
	
	//Extra Fields
	update_user_meta( $user_id, '_alum_birthdate', $birthdate);
	update_user_meta( $user_id, '_alum_employer_name', $employer_name);
	update_user_meta( $user_id, '_alum_employer_address', $employer_address);
	update_user_meta( $user_id, '_alum_spouse_name', $spouse_name);
	//update_user_meta( $user_id, '_alum_mobile_phone', $mobile_phone);
	update_user_meta( $user_id, '_alum_home_phone', $home_phone);
	update_user_meta( $user_id, '_alum_work_phone', $work_phone);

	update_user_meta( $user_id, '_alum_are_you_hiring', $are_you_hiring);
	update_user_meta( $user_id, '_alum_hiring_position', $hiring_position);
	update_user_meta( $user_id, '_alum_seeking_employment', $seeking_employment);
	update_user_meta( $user_id, '_alum_type_employment_seeking', $type_employment_seeking);

		
	if($display_name != '' ) {
		$wpdb->update( 
			'wp_users', 
			array( 
				'display_name' => $display_name,
			), 
			array( 'ID' => $user_id ), 
			array( 
				'%s',
			), 
			array( '%d' ) 
		);
		
	}
	$log_user_out = false;
	if($username != '' ) {
		//get current username and see if its changed
		$sql = "SELECT user_nicename FROM wp_users WHERE ID = '$user_id' LIMIT 1";
		$user_nicename = $wpdb->get_row($sql);
		if($user_nicename->user_nicename != $username) {
			//see if its valid
			$username_avail = $alum->checkUsername($username);
			if($username_avail == true) {
				$log_user_out = true;
				$wpdb->update( 
					'wp_users', 
					array( 
						'user_nicename' => $username,
					), 
					array( 'ID' => $user_id ), 
					array( 
						'%s',
					), 
					array( '%d' ) 
				);
			}
			else {
				$results['resp'] = 'error';
				$results['mssg'] = 'The user name you entered is not available';
			}
		}
	}		
	if($email != '') {
		//get Current email (Login), if different, change and log user out...
		$sql = "SELECT user_email FROM wp_users WHERE ID = '$user_id' LIMIT 1";
		$user_email = $wpdb->get_row($sql);
		//echo "EMAIL: " . $user_email->user_email . "<BR>";
		if($user_email->user_email != $email) {
			//see if its taken
			$sql = "SELECT user_email FROM wp_users WHERE user_email = '$email' LIMIT 1";
			$email_exist = $wpdb->get_row($sql);
			if(empty($email_exist)) {
				$wpdb->update( 
					'wp_users', 
					array( 
						'user_email' => $email,
						'user_login' => $email,
					), 
					array( 'ID' => $user_id ), 
					array( 
						'%s',
						'%s',
					), 
					array( '%d' ) 
				);
				//Log user out
				$log_user_out = true;
			}
			else {
				$results['resp'] = 'error';
				$results['mssg'] = 'The email address you entered is not available';
			}
		}
	}
//print_r($_POST);
//print_r($_FILES);
	//Ad ???
	if($_FILES['ad'] != '') {
		$uploads_dir = alum_plugin_path . 'uploads/ads';
		$file = $_FILES['ad'];
		//$redir = $_SERVER['HTTP_REFERER'];
		//$alum_slug = $_POST['slug'];
		//$redir = get_bloginfo('url') . '/alum/' . $alum_slug;
		$image_x = 360;
		//$tmp_name = $_FILES['ad']['tmp_name'];
		//$name = basename($_FILES['ad']['name']);
		//move_uploaded_file($tmp_name, "$uploads_dir/$name");

		$group_id = $_POST['group_id'];
		$handle = new Upload($file);
		if ($handle->uploaded) {
			$handle->image_convert         = 'jpg';
			$handle->image_resize          = true;
			$handle->image_ratio_y         = true;
			$handle->image_x               = $image_x;
			$handle->jpeg_quality          = 90;
			$handle->Process($uploads_dir);
			$newname = $handle->file_dst_name;
			$_SESSION['alum_message'] = 'Your ad has been submitted';
			$_SESSION['alum_message_type'] = 'success';
		} 
		else {
			$_SESSION['alum_message'] = 'Error (138): ' . $handle->error . '';
			$_SESSION['alum_message_type'] = 'error';
		}
		$handle-> Clean(); 
		
		$wpdb->insert( 
			'alum_ads', 
			array( 
				'group_id' => $group_id, 
				'user_id' => $_POST['user_id'],
				'url' => '',
				'filename' => $newname
			), 
			array( 
				'%d',
				'%d',
				'%s', 
				'%s'
			) 
		);
		
//$wpdb->show_errors();
//$wpdb->print_error();
		
		$ad_id = $wpdb->insert_id;
	}
	
	//Email Somebody
	$admin_email = get_option( 'admin_email' );
	$subject = 'Someone has updated their profile on OurAlum';
	$headers = "From: OurAlum<info@ouralum.com>\r\n";
	$headers .= "Reply-To: OurAlum<info@ouralum.com>\r\n";
	$headers .= "CC: mikejr@malouf.law\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = '<html><body>';
	$message .= '<h1>OurAlum.com</h1>';
	$message .= 'Hello Admin,<br><br>';
	$message .= $display_name . ' has updated their profile on OurAlum.com.<br><br>';
	$message .= '</body></html>';
	mail($admin_email, $subject, $message, $headers);

	if(!isset($results)) {
		$results['resp'] = 'success';
		$results['mssg'] = 'Your user info was updated';
	}
	echo json_encode($results);
	exit;
}
add_action('wp_ajax_update_user_data', 'update_user_data');
add_action('wp_ajax_nopriv_update_user_data', 'update_user_data');

function send_group_join_request() {
	global $wpdb, $alum;
	$group_id = $_POST['group_id'];
	$user_id = $_POST['user_id'];
	$college = $_POST['college'];
	$email = $_POST['email'];
	$frat = $_POST['frat'];
	$comments = $_POST['comments'];
	//Add to Alumni
	$wpdb->insert( 
		'alumni', 
		array( 
			'user_id' => $user_id,
			'college' => $group_id, 
			'fraternity' => $frat,
			'email' => $email
		), 
		array( 
			'%d',
			'%s', 
			'%s',
			'%s'
		) 
	);
	$alum_id = $wpdb->insert_id;
	
	//Add to group, as unapproved...
	$wpdb->insert( 
		'alum_groups', 
		array( 
			'alum_id' => $alum_id,
			'group_id' => $group_id, 
			'status' => 0
		), 
		array( 
			'%d',
			'%s', 
			'%d'
		) 
	);
	//$wpdb->show_errors(); 
	//$wpdb->print_error();
	//Get Alum admin email
	$sql = "SELECT u.user_email, g.group_name FROM groups g, wp_users u WHERE g.id = '$group_id' AND u.ID = g.admin_id LIMIT 1";
	$gr = $wpdb->get_row($sql);
	//Email it...
	$subject = 'Someone has requested to join ' . $gr->group_name;
	$headers = "From: OurAlum<info@ouralum.com>\r\n";
	$headers .= "Reply-To: OurAlum<info@ouralum.com>\r\n";
	$headers .= "CC: judsonc75@gmail.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = '<html><body>';
	$message .= '<h1>OurAlum.com</h1>';
	$message .= 'Hello Admin,<br><br>';
	$message .= $name . ' has request to join ' . $gr->group_name . ' on OurAlum.com.<br><br>';
	$message .= 'Email: ' . $email . '<br>';
	if($comments != '') {
		$message .= 'Comments: ' . $comments . '<br>';
	}
	$message .= '</body></html>';
	mail($gr->user_email, $subject, $message, $headers);
	if(!isset($results)) {
		$results['resp'] = 'success';
		$results['mssg'] = 'Your request has been sent';
	}
	echo json_encode($results);
	exit;
}
add_action('wp_ajax_send_group_join_request', 'send_group_join_request');
add_action('wp_ajax_nopriv_send_group_join_request', 'send_group_join_request');

function send_group_post() {
	global $wpdb, $alum;
	$group_id = $_POST['group_id'];
	$user_id = $_POST['user_id'];
	$post_title = $_POST['post_title'];
	$post_content = $_POST['post_content'];
	//Get Category
	$sql = "SELECT cat_id FROM groups WHERE id = '$group_id' LIMIT 1";
	$cat = $wpdb->get_row($sql);
	$post_excerpt = excerpt(trim($post_content), 100);
	$post_slug = sanitize_title($post_title );
	//INSERT POST
	$do_post = array(
	  'post_title'    => wp_strip_all_tags( $post_title ),
	  'post_content'  => trim($post_content),
	  'post_name'     => $post_slug,
	  'post_status'   => 'publish',
	  'post_author'   => $user_id,
	  'post_excerpt'  => $post_excerpt,
	  'post_category' => array($cat->cat_id)
	);

	// Insert the post into the database
	wp_insert_post( $do_post );
	
	//Get Alum admin email
	/*
	$sql = "SELECT u.user_email, g.group_name FROM groups g, wp_users u WHERE g.id = '$group_id' AND u.ID = g.admin_id LIMIT 1";
	$gr = $wpdb->get_row($sql);
	//Email it...
	$subject = 'Someone has posted to ' . $gr->group_name . ' blog';
	$headers = "From: TheAlum<info@ouralum.com>\r\n";
	$headers .= "Reply-To: TheAlum<info@ouralum.com>\r\n";
	$headers .= "CC: judsonc75@gmail.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = '<html><body>';
	$message .= '<h1>OurAlum</h1>';
	$message .= 'Hello Admin,<br><br>';
	$message .= 'A post has been made to ' . $gr->group_name . ' blog on ouralum.com.<br><br>';
	$message .= 'Title: ' . stripslashes($post_title) . '<br>';
	if($post_content != '') {
		$message .= 'Comments: ' . $post_excerpt . '<br>';
	}
	$message .= '</body></html>';
	mail($gr->user_email, $subject, $message, $headers);
	*/
	if(!isset($results)) {
		$results['resp'] = 'success';
		$results['mssg'] = 'Your post has been submitted';
	}
	echo json_encode($results);
	exit;
}
add_action('wp_ajax_send_group_post', 'send_group_post');
add_action('wp_ajax_nopriv_send_group_post', 'send_group_post');

function send_group_post_reply() {
	global $wpdb, $alum;
	$group_id = $_POST['group_id'];
	$user_id = $_POST['user_id'];
	$post_id = $_POST['post_id'];
	$reply = $_POST['reply'];
	$userdata = get_userdata( $user_id );
	$commentdata = array(
		'comment_post_ID' => $post_id,
		'comment_author' => $userdata->display_name, 
		'comment_author_email' => $userdata->user_email, 
		'comment_author_url' => $userdata->user_url, 
		'comment_content' => $reply, 
		'comment_type' => '',
		'comment_parent' => $post_id,
		'user_id' => $user_id,
	);

	//Insert new comment and get the comment ID
	$comment_id = wp_new_comment( $commentdata );
	$results['resp'] = 'success';
	$results['mssg'] = 'Your reply has been submitted';
	$results['id'] = $comment_id;
	echo json_encode($results);
	exit;
}
add_action('wp_ajax_send_group_post_reply', 'send_group_post_reply');
add_action('wp_ajax_nopriv_send_group_post_reply', 'send_group_post_reply');

function send_group_job_request() {
	global $wpdb, $alum;
	$group_id = $_POST['group_id'];
	$user_id = $_POST['user_id'];
	$job_title = $_POST['job_title'];
	$job_desc = $_POST['job_desc'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$contact_info = $_POST['contact_info'];
	//Add to group, as unapproved...
	$wpdb->insert( 
		'alum_jobs', 
		array( 
			'user_id' => $user_id,
			'group_id' => $group_id,
			'job_title' => $job_title,
			'job_desc' => $job_desc,
			'city' => $city,
			'state' => $state,
			'contact_info' => $contact_info, 
			'status' => 1,
			'date_added' => date("Y-m-d H:i:s")
		), 
		array( 
			'%d',
			'%d', 
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
			'%d',
			'%s'
		) 
	);
	//$wpdb->show_errors(); 
	//$wpdb->print_error();
	
	//Get Alum admin email
	/*
	$sql = "SELECT u.user_email, g.group_name FROM groups g, wp_users u WHERE g.id = '$group_id' AND u.ID = g.admin_id LIMIT 1";
	$gr = $wpdb->get_row($sql);
	//Email it...
	$subject = 'Someone has requested to join ' . $gr->group_name;
	$headers = "From: TheAlum<info@ouralum.com>\r\n";
	$headers .= "Reply-To: TheAlum<info@ouralum.com>\r\n";
	$headers .= "CC: judsonc75@gmail.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = '<html><body>';
	$message .= '<h1>ouralum.com</h1>';
	$message .= 'Hello Admin,<br><br>';
	$message .= $name . ' has request to join ' . $gr->group_name . ' on ouralum.com.<br><br>';
	$message .= 'Email: ' . $email . '<br>';
	if($comments != '') {
		$message .= 'Comments: ' . $comments . '<br>';
	}
	$message .= '</body></html>';
	mail($gr->user_email, $subject, $message, $headers);
	*/
	if(!isset($results)) {
		$results['resp'] = 'success';
		$results['mssg'] = 'Your job listing has been added';
	}
	echo json_encode($results);
	exit;
	
}
add_action('wp_ajax_send_group_job_request', 'send_group_job_request');
add_action('wp_ajax_nopriv_send_group_job_request', 'send_group_job_request');

function show_calendar() {
	global $alum;
	$month = $_POST['month'];
	$group_id = $_POST['group_id'];
	$year = $_POST['year'];
	$calendar = $alum->showSideCalendar($month, $year, '', $group_id);
	$results['resp'] = 'success';
	$results['html'] = $calendar;
	echo json_encode($results);
	exit;
}

add_action('wp_ajax_show_calendar', 'show_calendar');
add_action('wp_ajax_nopriv_show_calendar', 'show_calendar');

function send_calendar_event() {
	global $alum, $wpdb;
	//print_r($_POST);
	$group_id = $_POST['group_id'];
	$user_id = $_POST['user_id'];
	$event_title = $_POST['event_title'];
	$date = $_POST['event_date'];
	$time = $_POST['event_time'];
	$event_date = date("Y-m-d H:i:s", strtotime($date . ' ' . $time));
	//Add to calendar
	$wpdb->insert( 
		'alum_events', 
		array( 
			'user_id' => $user_id,
			'group_id' => $group_id,
			'event_title' => $event_title,
			'event_date' => $event_date
		), 
		array( 
			'%d',
			'%d', 
			'%s',
			'%s'
		) 
	);
	//$wpdb->show_errors(); 
	//$wpdb->print_error();
	$results['resp'] = 'success';
	$results['mssg'] = 'Your event has been added';
	echo json_encode($results);
	exit;
}

add_action('wp_ajax_send_calendar_event', 'send_calendar_event');
add_action('wp_ajax_nopriv_send_calendar_event', 'send_calendar_event');

function send_alum_contact() {
	global $wpdb, $alum;
	$group_id = $_POST['group_id'];
	$sender_name = $_POST['sender_name'];
	$sender_email = $_POST['sender_email'];
	$sender_comments = $_POST['sender_comments'];
	//Get Alum admin email
	$sql = "SELECT u.user_email, g.group_name FROM groups g, wp_users u WHERE g.id = '$group_id' AND u.ID = g.admin_id LIMIT 1";
	$gr = $wpdb->get_row($sql);
	//Email it...
	$subject = 'Someone has sent a message to ' . $gr->group_name;
	$headers = "From: OurAlum<info@ouralum.com>\r\n";
	$headers .= "Reply-To: $sender_name<$sender_email>\r\n";
	$headers .= "CC: judsonc75@gmail.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = '<html><body>';
	$message .= '<h1>OurAlum.com</h1>';
	$message .= 'Hello Admin,<br><br>';
	$message .= $sender_name . ' has sent a message to ' . $gr->group_name . ' on OurAlum.com.<br><br>';
	$message .= 'Email: ' . $sender_email . '<br>';
	if($sender_comments != '') {
		$message .= 'Comments: ' . $sender_comments . '<br>';
	}
	$message .= '</body></html>';
	mail($gr->user_email, $subject, $message, $headers);
	if(!isset($results)) {
		$results['resp'] = 'success';
		$results['mssg'] = 'Your user info was updated';
	}
	
	echo json_encode($results);
	exit;
}

add_action('wp_ajax_send_alum_contact', 'send_alum_contact');
add_action('wp_ajax_nopriv_send_alum_contact', 'send_alum_contact');

function send_message_request() {
	global $wpdb, $alum;
	$group_id = $_POST['group_id'];
	$user_id = $_POST['user_id'];
	$pledge_class = $_POST['pledge_class'];
	$message = $_POST['message'];

	//Add to group, as unapproved...
	$wpdb->insert( 
		'alum_messages', 
		array( 
			'user_id' => $user_id,
			'group_id' => $group_id,
			'pledge_class' => $pledge_class,
			'message' => $message,
			'status' => 1,
			'date_added' => date("Y-m-d H:i:s")
		), 
		array( 
			'%d',
			'%d', 
			'%s',
			'%s',
			'%d',
			'%s'
		) 
	);
	//$wpdb->show_errors(); 
	//$wpdb->print_error();
	
	//Get Alum admin email
	/*
	$sql = "SELECT u.user_email, g.group_name FROM groups g, wp_users u WHERE g.id = '$group_id' AND u.ID = g.admin_id LIMIT 1";
	$gr = $wpdb->get_row($sql);
	//Email it...
	$subject = 'Someone has requested to join ' . $gr->group_name;
	$headers = "From: TheAlum<info@ouralum.com>\r\n";
	$headers .= "Reply-To: TheAlum<info@ouralum.com>\r\n";
	$headers .= "CC: judsonc75@gmail.com\r\n";
	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	$message = '<html><body>';
	$message .= '<h1>ouralum.com</h1>';
	$message .= 'Hello Admin,<br><br>';
	$message .= $name . ' has request to join ' . $gr->group_name . ' on ouralum.com.<br><br>';
	$message .= 'Email: ' . $email . '<br>';
	if($comments != '') {
		$message .= 'Comments: ' . $comments . '<br>';
	}
	$message .= '</body></html>';
	mail($gr->user_email, $subject, $message, $headers);
	*/
	if(!isset($results)) {
		$results['resp'] = 'success';
		$results['mssg'] = 'Your message has been added';
	}
	echo json_encode($results);
	exit;

}

add_action('wp_ajax_send_message_request', 'send_message_request');
add_action('wp_ajax_nopriv_send_message_request', 'send_message_request');


function delete_group_photo() {
	global $wpdb, $alum;
	$photo_id = $_POST['id'];
	//get name
	$sql = "SELECT photo_name FROM alum_photos WHERE id = '$photo_id' LIMIT 1";
	$p = $wpdb->get_row($sql);
	$uploads_dir = alum_plugin_path . 'uploads/photos';
	//echo $uploads_dir . '/' . $p->photo_name;
	unlink($uploads_dir . '/' . $p->photo_name);
	$wpdb->delete( 'alum_photos', array( 'id' => $photo_id) );
	if(!isset($results)) {
		$results['resp'] = 'success';
		$results['mssg'] = 'Your photo has been deleted';
	}
	echo json_encode($results);
	exit;

}

add_action('wp_ajax_delete_group_photo', 'delete_group_photo');
add_action('wp_ajax_nopriv_delete_group_photo', 'delete_group_photo');

function delete_group_ad() {
	global $wpdb, $alum;
	$ad_id = $_POST['id'];
	//print_r($_POST);
	//get name
	$sql = "SELECT filename FROM alum_ads WHERE id = '$ad_id' LIMIT 1";
	$p = $wpdb->get_row($sql);
	$uploads_dir = alum_plugin_path . 'uploads/ads';
	//echo $uploads_dir . '/' . $p->photo_name;
	unlink($uploads_dir . '/' . $p->filename);
	$wpdb->delete( 'alum_ads', array( 'id' => $ad_id) );
	if(!isset($results)) {
		$results['resp'] = 'success';
		$results['mssg'] = 'Your ad has been deleted';
	}
	echo json_encode($results);
	exit;

}

add_action('wp_ajax_delete_group_ad', 'delete_group_ad');
add_action('wp_ajax_nopriv_delete_group_ad', 'delete_group_ad');

function get_alum_percents() {
	global $wpdb, $alum;
	$year = $_POST['year'];
	$type = $_POST['type'];
	$group_id = $_POST['group_id'];
	//print_r($_POST);
	//Get members count...
	$sql = "SELECT COUNT(a.id) AS members_count FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '". $group_id ."' AND a.id = g.alum_id AND g.status = 1";
	$sql .= " AND a.id != 2203";
	if($year != 'all') {
		$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '$year'";
	}
//echo "SQL: $sql";
	$members_count = $wpdb->get_row($sql);
//print_r($members_count);
	$sql = "SELECT COUNT(a.id) AS active_count FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '". $group_id ."' AND a.id = g.alum_id AND g.status = 1";
	$sql .= " AND a.id != 2203 AND a.user_id != ''";
	if($year != 'all') {
		$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '$year'";
	}
	$active_count = $wpdb->get_row($sql);
//print_r($active_count);
	$percent = round(($active_count->active_count/$members_count->members_count)*100, 2);
	$non_members_count = $members_count->members_count - $active_count->active_count; 
	
	//List other years percentage
	//Get members count by year...
	$sql = "SELECT COUNT(a.id) AS members_count, YEAR(initiation_date) AS year FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '". $group_id ."' AND a.id = g.alum_id AND g.status = 1";
	$sql .= " AND a.id != 2203"; 
	if($year != 'all') {
		$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '$year'";
	}
	$sql .= " GROUP BY YEAR(a.initiation_date)";
	$members_count_by_year = $wpdb->get_results($sql);
    //Active By Year
	$sql = "SELECT COUNT(a.id) AS active_count, YEAR(initiation_date) AS year FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '". $group_id ."' AND a.id = g.alum_id AND g.status = 1";
	$sql .= " AND a.id != 2203 AND a.user_id != ''";
	if($year != 'all') {
		$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '$year'";
	}
	$sql .= " GROUP BY YEAR(a.initiation_date)";
	$active_count_by_year = $wpdb->get_results($sql);
	
//print_r($members_count_by_year);
//print_r($active_count_by_year);
	foreach($members_count_by_year as $members_by_year) {
		$year = $members_by_year->year;
		$count = $members_by_year->members_count;
		$yp[$year]['count'] = $count;
	}
	foreach($active_count_by_year as $active_by_year) {
		$year = $active_by_year->year;
		$count = $active_by_year->active_count;
		$yearly_percent[$year]['percent'] = ceil(($count/$yp[$year]['count']) *100);
	}
	//print_r($yearly_percent);
	if($type == 'top-classes' || $type == '') {
		arsort($yearly_percent);
	}
	elseif($type == 'bottom-classes') {
		asort($yearly_percent);
	}
	//Make the year list..
	$cc = 0;
	$list_html = '';
	foreach($yearly_percent as $year => $percentage) {
		$list_html .= '<li><span class="tp-c">Class of ' . $year . '</span><span class="tp-p">' . $percentage['percent'] . '%</span></li>';
		$cc++;
		if( $cc == 3) {
			break;
		}
	}
	
	$script = '<script id="alum_chart">
				(function($) {
				  	$("#doughnutChart").drawDoughnutChart([
				    	{ title: "Members", value :  ' . $active_count->active_count .  ',  color: "#337ab7" },
				    	{ title: "Non-Members", value: ' . $non_members_count . ',   color: "#e1e1e1" }
				  	]);
			  	})(jQuery);
			  	</script>';

	$results['list_html'] = $list_html;
	$results['script'] = $script;
	$results['resp'] = 'success';
	echo json_encode($results);
	exit;

}

add_action('wp_ajax_get_alum_percents', 'get_alum_percents');
add_action('wp_ajax_nopriv_get_alum_percents', 'get_alum_percents');

function send_alum_invite() {
	global $wpdb, $alum;
	//print_r($_POST);
	$alum_id = $_POST['alum_id'];
	$sender_id = $_POST['sender_id'];
	$group_id = $_POST['group_id'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	//Get sender info
	$sender = $alum->getUserData($sender_id);
	//Get alumni info 
	$sql = "SELECT first_name, last_name, middle_name, initiation_date, phone, email FROM alumni WHERE id = '$alum_id'";
	$alumni = $wpdb->get_row($sql);
	//Get group info 
	$sql = "SELECT group_name, group_slug FROM groups WHERE id = '$group_id'";
	$group = $wpdb->get_row($sql);
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

	$link = 'https://www.ouralum.com/claim-profile/?claim=1&hash=' . $invite_hash . '';
	
	//email/text alum a link to claim profile
	$msg = 'Hello ' . $alumni->first_name . ',<br><br>';
	$msg .= "<p>" . $sender->first_name . " " . $sender->last_name . " is requesting that you help update our Kappa Sigma alumni database.  Please click the link below or go to www.OurAlum.com to claim your profile and update your information.</p>";
	$msg .= "<p>" . $link . "&r=email_invite</p>";
	//echo $msg;
	$sms_msg = 'Hello ' . $alumni->first_name . ', ' . $sender->first_name . ' ' . $sender->last_name . ' has sent you an invitation to join OurAlum. Go to ' . $link . '&r=sms_invite to claim your profile';
	$wpdb->insert( 
		'invites_sent', 
			array( 
				'sender_id' => $sender_id,
				'group_id' => $group_id,
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
	exit;

}

add_action('wp_ajax_send_alum_invite', 'send_alum_invite');
add_action('wp_ajax_nopriv_send_alum_invite', 'send_alum_invite');

function get_members_table() {
	set_time_limit(3000);
	error_reporting(E_ALL);
	global $wpdb, $alum;
	$user_id = $_POST['user_id'];
	$search = $_POST['search'];
	$loggedin_user_data = $alum->getUserData($user_id);
	//print_r($_POST);
	
	$sql = "SELECT a.id, a.user_id, a.first_name, a.last_name, a.middle_name, a.pledge_class, a.fraternity, a.initiation_date, a.date_joined, a.last_updated, a.lat, a.lng, a.city, a.state, u.user_nicename, a.dump_fields, a.occupation, a.occupation2, u.user_registered FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '". $_POST['group_id'] ."' AND a.id = g.alum_id AND g.status = 1";
	if($_POST['init_year'] == 'all') {
		$sql .= "";
		$year_select = 'all';
	}
	elseif($_POST['init_year'] != '') {
		$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '" . $_POST['init_year'] . "'";
		$year_select = $_POST['init_year'];
	}
	
	else {
		$sql .= " AND YEAR(STR_TO_DATE(initiation_date, \"%Y\")) = '" . date("Y", strtotime($loggedin_user_data->initiation_date)) . "'";
		$year_select = date("Y", strtotime($loggedin_user_data->initiation_date));
	}
	$sql .= " AND a.id != 2203";
	if($search != '') {
		$fname = $search;
		$lname = $search;
		if(strpos($search, ' ') !== FALSE) {
			$name_split = explode(" ", $search);
			$fname = trim($name_split[0]);
			if(trim($name_split[1]) != '') {
				$lname = trim($name_split[1]);
				$sql .= " AND (a.first_name LIKE '%$fname%' AND a.last_name LIKE '%$lname%')";
			}
			else {
				$sql .= " AND (a.first_name LIKE '%$fname%' OR a.last_name LIKE '%$lname%')";
			}
		}
		else {
			$sql .= " AND (a.first_name LIKE '%$fname%' OR a.last_name LIKE '%$lname%')";
		}
		
	}
	if($_POST['init_year'] == 'all') {
		$sql .= " LIMIT 1710";
	}
	//print_r($loggedin_user_data);
	//echo "$sql";
	$results['sql'] = $sql;
	$members = $wpdb->get_results($sql);
	
	$sql2 = "SELECT YEAR(STR_TO_DATE(initiation_date, \"%Y\")) AS year FROM alum_groups g, alumni a WHERE g.group_id = '". $_POST['group_id'] ."' AND a.id = g.alum_id AND g.status = 1";
	$sql2 .= " AND a.id != 2203";
	$sql2 .= " GROUP BY YEAR(a.initiation_date) ORDER BY year DESC";
	//echo "SQL : $sql";
	$class_years = $wpdb->get_results($sql2);
	

	//print_r($members);
	if($_POST['init_year'] == 'all') {
	//$results['post'] = print_r($_POST, true);
	//$results['members'] = print_r($members, true);
	//echo json_encode($results);
	//exit;
	}
	
	$output = '';
	$js = '';
//	if(!empty($members)) {
		$js = '';
		$output .= '<div class="wrapper">';
		$output .= '<small>(D) = Deceased</small>';
		

		$output .= '<div id="filter-members">';
		$output .= '<b>Filter:</b>';
		
		$output .= '<div id="filter-members-init">';
		$output .= '<label>By Initiation Year:</label> ';
		$output .= '<select name="init_year_filter" id="init_year_filter">
							<option value="all"';
		if($year_select == 'all') {
			$output .= ' selected=""';
		}					
		$output .= '>All Classes</option>';
		foreach($class_years as $class_year) {
			$output .= '<option value="' . $class_year->year . '"';
			if($year_select == $class_year->year) {
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
			if($_POST['occupation'] == $profession) {
				$output .= ' selected';
			}
			$output .= '>' . $profession. '</option>';
			foreach($profession2 as $prof2) {
				$output .= '<option value="' . $prof2. '"';
				if($_POST['occupation'] == $prof2) {
					$output .= ' selected';
				}
				$output .= '> - ' . $prof2. '</option>';
			}
		}
		$output .= '</select>';
		$output .= '</div>';
		
		$output .= '</div>';
		$output .= '<table class="table" id="members_table" >';
		$output .= '<thead><tr><th>Member Name</th><th class="hide-mobile">Profession</th><th>Location</th><th >Init. Date</th><th class="hide-mobile">Last Updated</th><th>Claimed Profile</th></tr></thead>';
		$output .= '<tbody>';
		foreach($members as $member) {
		
			$last_updated = '';
			$last_updated_str = '';
			$claimed_profile = '';
			$dump_fields = json_decode($member->dump_fields, true);
		
			if($member->user_id != null) {
				$last_updated = date("m/d/y", strtotime($member->last_updated));
				$last_updated_str = date("Ymd", strtotime($member->last_updated));
				$claimed_profile = date("m/d/y", strtotime($member->user_registered));
			}
			/*
			elseif(strpos($member->dump_fields, 'Deceased') === FALSE && $dump_fields['Date of Death'] == '') {
				$claimed_profile = '<button type="button" class="btn btn-primary btn-sm sipopup" data-toggle="modal" data-target="#send_invite_modal" data-id="' . $member->id . '" data-group="' . $group->id . '" data-name="" data-email="' . $member->email . '" data-phone="' . $member->phone . '">Send Invite</button>';
			}
			//print_r($member);
			*/
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
			if(strpos($member->dump_fields, 'Deceased') !== FALSE || $dump_fields['Date of Death'] != '') {
				$output .= ' <small>(D)</small>';
			}
			else {
				if($member->user_id != null) {
					$last_updated = date("m/d/y", strtotime($member->last_updated));
					$last_updated_str = date("Ymd", strtotime($member->last_updated));
					$claimed_profile = date("m/d/y", strtotime($member->user_registered));
				}
				else {
					$claimed_profile = '<button type="button" class="btn btn-primary btn-sm sipopup" data-toggle="modal" data-target="#send_invite_modal" data-id="' . $member->id . '" data-email="' . $member->email . '" data-phone="' . $member->phone . '">Send Invite</button>';
				}
				
				//$claimed_profile = '<button type="button">Send Invite</button>';

				//$claimed_profile = 'test';
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
			$output .= '</td><td align="center"><span style="display: none;">' . date("Ymd", strtotime($member->initiation_date)) . '</span> ' . date("m/d/Y", strtotime($member->initiation_date)) . '</td><td class="hide-mobile"><span style="display: none;">' . $last_updated_str . '</span> ' . $last_updated . '</td><td align="center">' . $claimed_profile . '</td></tr>';
		
		}
		$output .= '</thead>';
		$output .= '</table>';
		$output .= '</div>';

//	}
//	else {
		
//	}
	$html = $js . "\r\n" . $output;
	$results['html'] = $html;
	$results['resp'] = 'success';
	echo json_encode($results);

	exit;
}

add_action('wp_ajax_get_members_table', 'get_members_table');
add_action('wp_ajax_nopriv_get_members_table', 'get_members_table');

function send_composite() {
	global $wpdb, $alum;
	if($_REQUEST['save_composite'] == 1) {
		$group_id = $_POST['group_id'];
		$post_title = $_POST['post_title'];
		$init_year = $_POST['init_year'];
		//print_r($_POST);
		//print_r($_FILES);
		//Save Photo
		$target_dir = alum_plugin_path . 'uploads/composites/';
		$file_name = time() . '_' . str_replace(array(' '), array('_'), basename($_FILES['composite_img']['name']));
		$target_file = $target_dir . $file_name;
		$path_parts = pathinfo($target_file);
		$fn = $path_parts['filename'];
		$uploadOk = 1;
		if(!move_uploaded_file($_FILES['composite_img']['tmp_name'], $target_file)) {
			$_SESSION['alum_message'] = '<div class="alert alert-danger">Sorry, there was an error uploading your file.</div>';
		} 
		else {
			$_SESSION['alum_message'] = '<div class="alert alert-success">Your composite has been uploaded.</div>';
		
			//echo '<script>location.reload();</script>';
			//echo '<script>window.location = "' . get_bloginfo('url') . '/profile";</script>';
			
			// Insert the post into the database
			$wpdb->insert( 
				'alum_composites', 
				array( 
					'group_id' => $group_id,
					'title' => $post_title, 
					'init_year' => $init_year,
					'image_name' => $file_name,
					'status' => 1,
				), 
				array( 
					'%d',
					'%s', 
					'%d',
					'%s',
					'%d', 
				) 
			);
			//$wpdb->show_errors(); 
			//$wpdb->print_error();	
		}
		//header("Refresh:0");
		header('Location:: current_page_url');
		exit;
		//if(!isset($results)) {
		//	$results['resp'] = 'success';
		//	$results['mssg'] = 'Your post has been submitted';
		//}
		//echo json_encode($results);
		//exit;
	}
}
add_action('wp_ajax_send_composite', 'send_composite');
add_action('wp_ajax_nopriv_send_composite', 'send_composite');

add_action('init', 'send_composite');	

function ajaxurl() { 
	echo '<script type="text/javascript"> var ajaxurl = "'.admin_url("admin-ajax.php").'"; </script>'; 
} 
add_action('wp_head', 'ajaxurl');


function excerpt($content, $limit) {
	$excerpt = explode(' ', $content, $limit);
  	if (count($excerpt)>=$limit) {
    	array_pop($excerpt);
    	$excerpt = implode(" ",$excerpt).'...';
  	} else {
    	$excerpt = implode(" ",$excerpt);
  	}	
  	$excerpt = preg_replace('`[[^]]*]`','',$excerpt);
  	return $excerpt;
}

function correctImageOrientation($filename) {
	if(function_exists('exif_read_data')) {
	    $exif = exif_read_data($filename);
	    if($exif && isset($exif['Orientation'])) {
			$orientation = $exif['Orientation'];
	      	if($orientation != 1){
	       		$img = imagecreatefromjpeg($filename);
	        	$deg = 0;
	        	switch ($orientation) {
	          		case 3:
	            		$deg = 180;
            		break;
          			case 6:
            			$deg = 270;
            		break;
          			case 8:
            			$deg = 90;
            		break;
        		}
        		if ($deg) {
          			$img = imagerotate($img, $deg, 0);       
        		}
        		// then rewrite the rotated image back to the disk as $filename
        		imagejpeg($img, $filename, 95);
      		} // if there is some rotation necessary
    	} // if have the exif orientation info
  	} // if function exists     
}
?>