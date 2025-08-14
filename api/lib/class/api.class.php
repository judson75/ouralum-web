<?php

class alumAPI
{



	public function login()
	{

		global $wp;

		//print_r($_REQUEST);

		$login = wp_signon(array('user_login' => $_REQUEST['username'], 'user_password' => $_REQUEST['password']));

		//print_r($login);

		if (empty($login->errors)) {

			$response['code'] = 1;

			$response['msg'] = 'success';

			$response['data']['user_id'] = $login->data->ID;

			$response['data']['display_name'] = $login->data->display_name;
		} else {

			foreach ($login->errors as $ei => $error) {

				$response['code'] = 1;

				if ($ei == 'invalid_username') {

					$response['msg'] = 'Invalid Username';

					$response['data'] = 'Invalid Username';
				} elseif ($ei == 'incorrect_password') {

					$response['msg'] = 'Incorrect Password';

					$response['data'] = 'Incorrect Password';
				} else {

					$response['msg'] = $error[0];

					$response['data'] = $error[0];
				}
			}
		}

		return $response;
	}

	///Created By Firooz Smart

	function claim_profile()
	{

		global $wpdb;

		$last_name = $_POST['last_name'];

		//print_r($_POST);

		$initiation_date = $_POST['initiation_date'];

		$search_last_name = str_replace(' ', '', strtolower(trim($_POST['last_name'])));

		$search_first_name = substr(strtolower(trim($_POST['first_name'])), 0, 1);

		if ($_POST['middle_name'] != '') {

			$search_middle_name = substr(strtolower(trim($_POST['middle_name'])), 0, 1);
		}



		//$pledge_class = $_POST['pledge_class'];

		//First We find last name, and sub verify on others

		//$account = $wpdb->get_row( "SELECT id, initiation_date FROM alumni WHERE last_name = '$last_name'");

		//$sql = "SELECT id, initiation_date FROM alumni WHERE last_name = '$last_name'  AND pledge_class = '$pledge_class' AND initiation_date = '$initiation_date'";

		$sql = "SELECT id, initiation_date FROM alumni WHERE LOWER(last_name) = '$search_last_name' AND YEAR(initiation_date) = '$initiation_date'";

		//Add middle intitial AND first intital search

		$sql .= " AND LOWER(first_name) LIKE '$search_first_name%'";

		if ($_POST['middle_name'] != '') {

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

		if (!empty($account)) {

			//we send them to claim profile...

			// $_SESSION['alum_profile_id'] = $account->id;

			// $_SESSION['alum_user_register'] = true;

			//$url = get_bloginfo('url') . '/profile-found';

			//wp_redirect( $url );

			//echo '<script>window.location = "' . $url . '"</script>';

			//exit();
			$response['data'] = $account;

			$response['resp'] = 'success';

			$response['mssg'] = 'Your profile was found';
		} else {

			$response['resp'] = 'error';

			$response['mssg'] = 'Your profile was not found or has already been claimed. If you have claimed your profile, click the Login';
		}

		$response['code'] = 1;

		return $response;
	}


	public function register()
	{
		global $alum, $wpdb;


		// print_r($_POST);

		// exit();

		if (!isset($_POST['alum_profile_id']) || empty($_POST['alum_profile_id'])) {

			$response['msg'] = 'Please provide a profile ID.';

			$response['code'] = 5;

			return $response;
		}



		$user_login = $_POST['user_login'];

		$user_email = $_POST['user_login'];

		$user_password = $_POST['user_password'];

		$user_id = username_exists($user_login);

		if (!$user_id && email_exists($user_email) == false) {

			$user_id = wp_create_user($user_login, $user_password, $user_email);


			$first_name = $_POST['first_name'];

			$middle_name = $_POST['middle_name'];

			$last_name = $_POST['last_name'];

			$display_name = $first_name;

			if ($middle_name != '') {

				$display_name .= ' ' . substr($middle_name, 0, 1) . '.';
			}

			$display_name .= ' ' . $last_name;


			$username = $alum->generateUniqueUsername($_POST);

			$initiation_date = date("Y-m-d", strtotime($_POST['initiation_date']));


			$user_upd = wp_update_user(array('ID' => $user_id, 'user_nicename' => $username, 'display_name' => $display_name, 'user_status' => '1'));



			// $log = print_r($_REQUEST, true);

			// $log .= 'Logged';

			// $fp = fopen('api_log.txt', 'w');

			// fwrite($fp, $log);

			// fclose($fp);



			$wpdb->update(

				'alumni',

				array(

					'user_id' => $user_id,

					'email' => $user_email,

				),

				array('id' => $_POST['alum_profile_id']),

				array(

					'%s',

					'%s'

				),

				array('%d')

			);

			// $wpdb->show_errors();

			// $wpdb->print_error();

			// //Get data for email


			//Email Somebody

			$admin_email = get_option('admin_email');

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

			$message .= 'They did claim a profile';

			$message .= '</body></html>';

			mail($admin_email, $subject, $message, $headers);

			// //Email Welcome Message to User

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


			$message .= 'Initiation Date: ' . date("m/d/Y", strtotime($initiation_date)) . '<br><br>';

			$message .= 'Your public profile can be viewed at <a href="' . get_bloginfo('url') . '/m/' . $username . '">' . get_bloginfo('url') . '/m/' . $username . '</a><br><br>';

			$message .= 'You can log in at ' . get_bloginfo('url') . '/login<br><br>';



			$message .= '</body></html>';

			mail($user_email, $subject, $message, $headers);

			// //echo '<script>window.location = "' . $url . '"</script>';


			$response['msg'] = 'success';

			$response['user_id'] = $user_id;
		} else {

			$response['msg'] = 'User already exists';
		}

		$response['code'] = 1;

		return $response;
	}


	public function postToken()
	{

		global $wp, $pdo;

		$sql = "UPDATE alumni SET app_token = :token WHERE user_id = :user_id";

		//echo "SQL: $sql";

		$query = $pdo->prepare($sql);

		$query->execute(array(':user_id' => $_REQUEST['user_id'], ':token' => $_REQUEST['token']));



		$response['code'] = 1;

		$response['msg'] = 'success';

		return $response;
	}




	public function saveProfile()
	{

		global $alum;



		// $log = print_r($_REQUEST, true);

		// $fp = fopen('api_log.txt', 'w');

		// fwrite($fp, $log);

		// fclose($fp);



		$profile = $alum->updateProfile($_REQUEST['user_id']);

		$response['code'] = 1;

		$response['msg'] = 'success';

		$response['data'] = $profile;

		return $response;
	}



	public function sendNotification()
	{

		global $alum;

		if ($_REQUEST['user_id'] == '' ||  $_REQUEST['message'] == '') {

			$response['code'] = 0;

			$response['msg'] = 'User id and Message required';
		} else {

			$profile = $alum->sendAlumPushNotifications(array('user_id' => $_REQUEST['user_id'], 'message' => $_REQUEST['message']));

			$response['code'] = 1;

			$response['msg'] = 'success';
		}

		return $response;
	}



	public function getGroupPercentage()
	{

		global $alum;

		/*

		$_REQUEST['year'];

		$_REQUEST['type'];

		$_REQUEST['group_id'];

		$_REQUEST['type'] == 'top-classes' || 'bottom-classes'

		*/

		$percentage = $alum->get_alum_percents();

		//$percentage = array();

		//print_r($percentage);

		$data['list_html'] = $percentage['list_html'];

		$data['script'] = $percentage['script'];


		if (!is_nan($percentage['percent'])) {

			$data['percent'] = $percentage['percent'];
		} else {

			$data['percent'] = 0;
		}

		$response['code'] = 1;

		$response['msg'] = 'success';

		$response['data'] = $data;

		return $response;
	}



	public function getMemberCountHome()
	{

		global $alum;

		$members = $alum->getMemberCount();

		$response['code'] = 1;

		$response['msg'] = 'success';

		$response['data'] = $members;

		return $response;
	}



	public function getAlumns()
	{

		global $wpdb, $pdo;

		$sql = "SELECT g.id AS group_id, g.group_name, g.group_logo FROM groups g WHERE g.status = 1";

		//echo "SQL: $sql";

		$query = $pdo->prepare($sql);

		$query->execute();

		$results = $query->fetchAll(PDO::FETCH_ASSOC);

		//print_r($results);

		if (!empty($results)) {

			//get alum count

			foreach ($results as $r => $result) {

				$sql2 = "SELECT COUNT(id) AS member_count FROM alum_groups WHERE group_id = :group_id";

				//echo "SQL: " . str_replace(array(':group_id'), array($result['group_id']), $sql2) . "\r\n";

				$query2 = $pdo->prepare($sql2);

				$query2->execute(array(':group_id' => $result['group_id']));

				$m = $query2->fetch(PDO::FETCH_ASSOC);

				if (!empty($m)) {

					$results[$r]['member_count'] = $m['member_count'];
				}
			}

			$response['code'] = 1;

			$response['msg'] = 'success';

			//$response['sql'] = $sql;

			$response['data'] = $results;
		}

		return $response;
	}



	public function getAlumn()
	{

		global $wpdb, $pdo, $alum;

		//Get Category

		$sql = "SELECT cat_id FROM groups WHERE id = '" . $_REQUEST['id'] . "' LIMIT 1";

		$cat = $wpdb->get_row($sql);



		$sql = "SELECT * FROM groups WHERE id = :id";

		//echo "SQL: $sql";

		//print_r($_REQUEST);

		$query = $pdo->prepare($sql);

		$query->execute(array(':id' => $_REQUEST['id']));

		$results = $query->fetch(PDO::FETCH_ASSOC);

		//print_r($results);





		if (!empty($results)) {

			//Get other info...

			if ($results['group_logo'] != '') {

				$results['logo_src'] = get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . get_bloginfo('url') . '/images/alum_group_images/' . $results['group_logo'] . '&h=160&w=160&zc=1';
			}

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

			$posts_array = get_posts($args);

			if (!empty($posts_array)) {

				//$results['posts'] = $posts_array;			

				foreach ($posts_array as $bi => $blog) {

					if ($blog->post_excerpt == '') {

						$blog->post_excerpt = excerpt(trim($blog->post_content), 50);
					}



					$post_author_id = get_post_field('post_author', $blog->ID);

					$adisplay_name = get_the_author_meta('display_name', $post_author_id);

					$anice_name = get_the_author_meta('user_nicename', $post_author_id);

					$user_data = $alum->getUserData($post_author_id);



					//echo $user_data->avatar;

					//echo alum_plugin_url;

					if ($user_data->avatar != '' && file_exists(alum_plugin_path . 'uploads/avatars/' . $user_data->avatar)) {

						$avatar = $user_data->avatar;

						$results['posts'][$bi]->avatar_url = alum_plugin_url . 'uploads/avatars/' . $avatar;

						$results['posts'][$bi]->avatar_img = '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . alum_plugin_url . 'uploads/avatars/' . $avatar . '&h=120&w=120&zc=1" alt="' . $adisplay_name . '">';
					} else {

						$results['posts'][$bi]->avatar_url = get_template_directory_uri() . '/lib/images/no-image-icon.png';

						$results['posts'][$bi]->avatar_img = '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . get_template_directory_uri() . '/lib/images/no-image-icon.png&h=120&w=120&zc=1" alt="' . $adisplay_name . '">';
					}

					$results['posts'][$bi]->title = $blog->post_title;

					$results['posts'][$bi]->post_excerpt = $blog->post_excerpt;

					$results['posts'][$bi]->post_name = $blog->post_name;

					$results['posts'][$bi]->post_date = date("M d, Y", strtotime($blog->post_date)) . ' ' . date("g:i A", strtotime($blog->post_date));

					$results['posts'][$bi]->nice_name = $anice_name;

					$results['posts'][$bi]->display_name = $adisplay_name;
				}
			}

			//Composites

			$sql = "SELECT title, init_year, image_name FROM alum_composites WHERE 1 AND group_id = '" . $_REQUEST['id'] . "' AND status = 1 ORDER BY RAND() DESC LIMIT 9";

			//echo $sql;

			$composites = $wpdb->get_results($sql);

			if (!empty($composites)) {

				$photo_dir = get_bloginfo('url') . '/wp-content/plugins/alumn/uploads/composites';

				//$results['composites_dir'] = $photo_dir;

				//$results['composites'] = $composites;

				foreach ($composites as $ci => $composite) {

					$results['composites'][$ci]['img_url'] = get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . $photo_dir . '/' . $composite->image_name . '&h=200&w=300&zc=1';

					$results['composites'][$ci]['title'] = $composite->title;
				}
			}

			//Photos

			$sql = "SELECT user_id, caption, photo_name, last_updated FROM alum_photos WHERE 1 AND group_id = '" . $_REQUEST['id'] . "' AND status = 1 ORDER BY last_updated DESC, RAND() DESC LIMIT 9";

			//echo $sql;

			$photos = $wpdb->get_results($sql);

			if (!empty($photos)) {

				$photo_dir = get_bloginfo('url') . '/wp-content/plugins/alumn/uploads/photos';

				//$results['photos_dir'] = $photo_dir;

				//$results['photos'] = $photos;

				foreach ($photos as $pi => $photo) {

					$results['photos'][$pi]['img_url'] = get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . $photo_dir . '/' . $photo->photo_name . '&h=200&w=300&zc=1';

					$results['photos'][$pi]['caption'] = $photo->caption;
				}
			}

			//Map



			//Update percent

			$p = $this->getGroupPercentage();

			$results['percents']['percent'] = $p['data']['percent'];;

			$results['percents']['list_html'] = $p['data']['list_html'];

			//			$results['percents']['script'] = $p['data']['script'];



			//print_r($results);

			//members



			$results['members'] = $this->getMembers();

			// print_r($results);



			//Promos/Ads

			$sql = "SELECT * FROM alum_ads WHERE 1 AND group_id = '" . $_REQUEST['id'] . "' AND status = 1 ORDER BY RAND() LIMIT 9";

			//echo $sql;

			$ads = $wpdb->get_results($sql);

			if (!empty($ads)) {

				$results['ads'] = $ads;

				foreach ($ads as $ac => $ad) {

					//Get user Data

					$user_data = $alum->getUserData($ad->user_id);

					$user_info = get_userdata($ad->user_id);

					//print_r($user_info);

					//print_r($user_data);

					$results['ads'][$ac]->user_name = $user_info->display_name;

					$results['ads'][$ac]->member_id = $user_data->id;

					$results['ads'][$ac]->img_url = get_bloginfo('url') . '/wp-content/plugins/alumn/uploads/ads/' . $ad->filename;
				}
			}

			//Get Job Listings

			//random jobs

			$sql = "SELECT j.job_title, j.job_desc, j.city, j.state, j.contact_info, j.last_updated FROM alum_jobs j WHERE 1 AND j.group_id = '" . $_REQUEST['id'] . "' AND j.status = 1 ORDER BY RAND() LIMIT 3";

			//echo $sql;

			$jobs = $wpdb->get_results($sql);

			//Randon looking for hiring....

			$sql = "SELECT g.alum_id, a.user_id, a.state, a.city, CONCAT(a.first_name, ' ', a.last_name, ' ',  a.phone) AS contact_info, m1.meta_value AS job_title FROM alum_groups g, alumni a, wp_usermeta m, wp_usermeta m1 WHERE 1 AND g.group_id = '" . $_REQUEST['id'] . "' AND g.status = 1 AND a.id = g.alum_id AND a.user_id != '' AND m.user_id = a.user_id AND m.meta_key = '_alum_are_you_hiring' AND m.meta_value = 'Yes' AND m1.user_id = a.user_id AND m1.meta_key = '_alum_hiring_position' AND m1.meta_value != '' ORDER BY RAND() LIMIT 3";

			//echo $sql;

			$jobs2 = $wpdb->get_results($sql);

			//echo '<pre>'; print_r($jobs2); echo '</pre>';



			if (empty($jobs) && !empty($jobs2)) {

				$jobs = $jobs2;
			} elseif (!empty($jobs) && !empty($jobs2)) {

				$jobs = array_merge($jobs, $jobs2);
			}

			if (!empty($jobs)) {

				$results['jobs'] = $jobs;

				foreach ($jobs as $jc => $job) {

					if ($job->job_desc != '') {

						$results['jobs'][$jc]->short_desc = substr($job->job_desc, 0, 155);
					}
				}
			}


			/* Links */

			$sql = "SELECT id, url, caption FROM alum_group_links WHERE group_id = '" . $_REQUEST['id'] . "'";

			//echo "$sql";

			$links = $wpdb->get_results($sql);

			if (!empty($links)) {

				$results['links'] = $links;
			}



			$response['code'] = 1;

			$response['msg'] = 'success';

			$response['data'] = $results;

			// print_r($response);
		}





		return $response;
	}



	public function getProfile()
	{

		global $wpdb, $pdo, $alum;

		$non_member = false;

		$sql = "SELECT * FROM alumni WHERE id = :id";

		//echo "SQL: $sql";

		//print_r($_REQUEST);

		$query = $pdo->prepare($sql);

		$query->execute(array(':id' => $_REQUEST['id']));

		$results = $query->fetch(PDO::FETCH_ASSOC);

		//Extra

		$results['avatar'] = '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . get_template_directory_uri() . '/lib/images/no-image-icon.png&h=160&w=160&zc=1" alt="' . $results['first_name'] . ' ' . $results['last_name'] . '">';

		if ($results['user_id'] != '') {

			//$array = get_object_vars($object);

			$user_data = get_object_vars($alum->getUserData($results['user_id']));

			$avatar = get_user_meta($results['user_id'], '_alum_avatar', true);

			//print_r($user_data);

			if ($user_data['display_name'] != '') {

				$results['display_name'] = $user_data['display_name'];
			}



			if ($user_data['birthdate'] != '') {

				$results['birthdate'] = date("m/d/Y", strtotime($user_data['birthdate']));

				$results['birthdate_db'] = $user_data['birthdate'];
			}

			if ($user_data['spouse_name'] != '') {

				$results['spouse_name'] = $user_data['spouse_name'];
			}

			if ($user_data['employer_name'] != '') {

				$results['employer_name'] = $user_data['employer_name'];
			}



			if ($user_data['employer_address'] != '') {

				$results['employer_address'] = $user_data['employer_address'];
			}

			if ($user_data['work_phone'] != '') {

				$results['work_phone'] = $user_data['work_phone'];
			}



			$results['are_you_hiring'] = '';

			if ($user_data['are_you_hiring'] == 'Yes') {

				$results['hiring_position'] = $user_data['hiring_position'];

				$results['are_you_hiring'] = 'Yes';
			}



			$results['seeking_employment'] = '';

			if ($user_data['seeking_employment'] == 'Yes') {

				$results['type_employment_seeking'] = $user_data['type_employment_seeking'];

				$results['seeking_employment'] = 'Yes';
			}
		} else {

			$non_member = true;
		}



		if ($avatar != '') {

			$results['avatar'] = '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . alum_plugin_url . 'uploads/avatars/' . $avatar . '&h=160&w=160&zc=1" alt="' . $results['last_name'] . '">';
		}

		//echo "AV: " . $results->avatar;		

		//Get Groups

		$sql = "SELECT g.*, a.pledge_class, a.initiation_date FROM groups g, alum_groups ag, alumni a WHERE 1 AND a.user_id = '" . $results['user_id'] . "' AND a.id = ag.alum_id AND ag.status = 1 AND g.id = ag.group_id";

		$groups = $wpdb->get_results($sql, ARRAY_A);

		//echo '<!--'; print_r($groups); echo '-->';

		if (!empty($groups)) {

			$results['groups'] = $groups;

			foreach ($groups as $gc => $group) {

				$results['groups'][$gc]['init_date_format'] = date("M j, Y", strtotime($group['initiation_date']));
			}
		}



		//Timeline

		if ($non_member != true) {

			$results['timeline'] = $alum->buildProfileTimeline($results['user_id']);
		}



		$response['code'] = 1;

		$response['msg'] = 'success';

		$response['data'] = $results;



		return $response;
	}





	public function getUser()
	{

		global $wpdb, $pdo, $alum;

		$data =  $alum->getUserData($_REQUEST['user_id']);

		$user_data = get_object_vars($alum->getUserData($_REQUEST['user_id']));

		if (!empty($data) && $user_data['initiation_date'] != '') {

			$user_data['initiation_year'] = date("Y", strtotime($user_data['initiation_date']));
		}

		$response['code'] = 1;

		$response['msg'] = 'success';

		$response['data'] = $user_data;

		return $response;
	}





	public function sendMemberInvite()
	{

		global $wpdb, $alum;

		//print_r($_REQUEST);

		$alum_id = $_REQUEST['member_id'];

		$sender_id = $_REQUEST['user_id'];

		$group_id = $_REQUEST['group_id'];

		$email = $_REQUEST['email'];

		$phone = $_REQUEST['phone'];

		//Get sender info

		$sender = $alum->getUserData($sender_id);



		//Get alumni info 

		$sql = "SELECT first_name, last_name, middle_name, initiation_date, phone, email FROM alumni WHERE id = '$alum_id'";

		$alumni = $wpdb->get_row($sql);

		//Get group info 

		$sql = "SELECT group_name, group_slug FROM groups WHERE id = '$group_id'";

		$group = $wpdb->get_row($sql);

		//Add alt phone and alt email if not same as submitted

		if ($alumni->phone != $phone) {

			$wpdb->update(

				'alumni',

				array(

					'alt_phone' => $phone,

				),

				array('id' => $alum_id),

				array(

					'%s',

				),

				array('%d')

			);
		}

		if ($alumni->email != $email) {

			$wpdb->update(

				'alumni',

				array(

					'alt_email' => $email,

				),

				array('id' => $alum_id),

				array(

					'%s',

				),

				array('%d')

			);
		}

		//Create a link 

		$invite_hash = base64_encode(time() . ':' . $alum_id);

		$wpdb->update(

			'alumni',

			array(

				'invite_hash' => $invite_hash,

			),

			array('id' => $alum_id),

			array(

				'%s',

			),

			array('%d')

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



		$opts = array(
			'http' =>

			array(

				'method'  => 'POST',

				'header'  => 'Content-type: application/x-www-form-urlencoded',

				'content' => $postdata

			)

		);



		$context  = stream_context_create($opts);



		$results['sms'] = file_get_contents($url, false, $context);

		$response['code'] = 1;

		$response['msg'] = 'success';

		$response['data'] = $results;





		return $response;
	}



	public function submitGroupPhoto()
	{

		global $wpdb, $alum, $pdo;

		//unset($_REQUEST['photo_data']);

		//print_r($_REQUEST);

		//print_r($_FILES);

		//echo "EXIF: " . print_r($_REQUEST['exif_data']);

		$jsonData = stripslashes(html_entity_decode($_REQUEST['exif_data']));

		$exif = json_decode($jsonData, true);

		//echo alum_plugin_path . 'uploads/photos';

		$uploads_dir = alum_plugin_path . 'uploads/photos';



		//return false;



		$image_x = 1024;



		$handle = new Upload($_FILES['photo']);

		if ($handle->uploaded) {

			$handle->image_convert         = 'jpg';

			$handle->image_resize          = true;

			$handle->image_ratio_y         = true;

			$handle->image_x               = $image_x;

			$handle->jpeg_quality          = 90;

			$handle->Process($uploads_dir);

			$newname = $handle->file_dst_name;

			//$_SESSION['alum_message'] = 'Your photo has been submitted';

			//$_SESSION['alum_message_type'] = 'success';

			$response['response'] = 'Your photo has been submitted';

			$response['msg'] = 'success';
		} else {

			//$_SESSION['alum_message'] = 'Error (138): ' . $handle->error . '';

			//$_SESSION['alum_message_type'] = 'error';

			$response['response'] = 'Error (138): ' . $handle->error . '';

			$response['msg'] = 'error';
		}

		$handle->Clean();

		//Check Orientation

		$filename = $uploads_dir . '/' . $newname;

		//echo "NEW $filename";

		$this->correctImageOrientation($filename, $_REQUEST['orientation'], $exif);



		//Save to DB

		$wpdb->insert(

			'alum_photos',

			array(

				'user_id' => $_REQUEST['user_id'],

				'group_id' => $_REQUEST['group_id'],

				'photo_name' => $newname,

				'caption' => $_REQUEST['photo_caption'],

				'photo_year' => $_REQUEST['photo_year'],

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



		$response['code'] = 1;

		return $response;
	}



	public function getPhotos()
	{

		global $wpdb, $alum, $pdo;

		//print_r($_REQUEST);

		$group_id = $_REQUEST['id'];

		//Get Photos

		$sql = "SELECT id, user_id, caption, photo_name, last_updated, photo_year FROM alum_photos WHERE 1 AND group_id = '" . $group_id . "' AND status = 1 ";

		if ($_REQUEST['pc'] != '' && $_REQUEST['pc'] != 'all') {

			$sql .= "AND pledge_class = '" . $_REQUEST['pc'] . "' ";
		}

		$sql .= "ORDER BY last_updated DESC";

		//echo $sql;

		$photos = $wpdb->get_results($sql);

		if (!empty($photos)) {

			$results = $photos;

			$photo_dir = get_bloginfo('url') . '/wp-content/plugins/alumn/uploads/photos';

			foreach ($photos as $pc => $photo) {

				$results[$pc]->photo_url = get_bloginfo('url') . '/wp-content/plugins/alumn/lib/php/timthumb.php?src=' . $photo_dir . '/' . $photo->photo_name . '&h=200&w=320&z=1';
			}
		}

		$response['code'] = 1;

		$response['msg'] = 'success';

		$response['data'] = $results;



		return $response;
	}



	public function getPosts()
	{

		global $wpdb, $alum;

		//print_r($_REQUEST);

		$group_id = $_REQUEST['id'];

		//Get Category

		$sql = "SELECT cat_id FROM groups WHERE id = '" . $_REQUEST['id'] . "' LIMIT 1";

		$cat = $wpdb->get_row($sql);



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

		$posts_array = get_posts($args);

		$editor_id = 'post_content';

		$content = '';

		if (!empty($posts_array)) {

			$results = $posts_array;

			foreach ($posts_array as $pc => $post) {

				if ($post->post_excerpt == '') {

					$results[$pc]->post_excerpt = excerpt(trim($post->post_content), 100);
				}

				$results[$pc]->post_date_formatted = date("M d, Y", strtotime($post->post_date)) . ' ' . date("g a", strtotime($post->post_date));

				$post_author_id = get_post_field('post_author', $post->ID);

				$adisplay_name = get_the_author_meta('display_name', $post_author_id);

				$anice_name = get_the_author_meta('user_nicename', $post_author_id);

				$user_data = $alum->getUserData($post_author_id);

				$results[$pc]->author = $adisplay_name;

				if ($user_data->avatar != '' && file_exists(alum_plugin_path . 'uploads/avatars/' . $user_data->avatar)) {

					$avatar = $user_data->avatar;

					$results[$pc]->avatar_url = alum_plugin_url . 'uploads/avatars/' . $avatar;

					$results[$pc]->avatar_img = '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . alum_plugin_url . 'uploads/avatars/' . $avatar . '&h=120&w=120&zc=1" alt="' . $adisplay_name . '">';
				} else {

					$results[$pc]->avatar_url = get_template_directory_uri() . '/lib/images/no-image-icon.png';

					$results[$pc]->avatar_img = '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . get_template_directory_uri() . '/lib/images/no-image-icon.png&h=120&w=120&zc=1" alt="' . $adisplay_name . '">';
				}

				$results[$pc]->post_date = date("M d, Y", strtotime($post->post_date)) . ' ' . date("g:i A", strtotime($post->post_date));
			}
		}

		$response['code'] = 1;

		$response['msg'] = 'success';

		$response['data'] = $results;



		return $response;
	}



	public function getMembers()
	{

		global $wpdb, $alum;

		//print_r($_REQUEST);

		$group_id = $_REQUEST['id'];

		$limit = 25;

		//pagination

		if ($_REQUEST['page'] == '') {

			$_REQUEST['page'] = 1;
		}

		$start = ($_REQUEST['page'] - 1) * $limit;

		$next_page = $_REQUEST['page'] + 1;

		$prev_page = $_REQUEST['page'] - 1;

		$sql = "SELECT a.id, a.user_id, a.first_name, a.last_name, a.middle_name, a.pledge_class, a.fraternity, a.initiation_date, a.phone, a.email, a.lat, a.lng, a.city, a.state, u.user_nicename, u.display_name, a.dump_fields, a.date_joined, a.last_updated, u.user_registered FROM alum_groups g, alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE g.group_id = '" .  $_REQUEST['id'] . "' AND a.id = g.alum_id AND g.status = 1";

		if ($_REQUEST['state'] != '') {

			$sql .= " AND a.state = '" . $_REQUEST['state'] . "'";
		}

		if ($_REQUEST['init_year'] != '') {

			$sql .= " AND YEAR(a.initiation_date) = '" . $_REQUEST['init_year'] . "'";
		}

		if ($_REQUEST['search_str'] != '') {

			$sql .= " AND (a.first_name LIKE '%" . $_REQUEST['search_str'] . "%' OR a.last_name LIKE '%" . $_REQUEST['search_str'] . "%' OR a.email LIKE '%" . $_REQUEST['search_str'] . "%')";
		}



		$sql_all = $sql .  " ORDER BY a.last_updated DESC";



		if ($_REQUEST['search_str'] != '') {

			$sql .= " ORDER BY a.last_name";
		} else {

			$sql .= " ORDER BY a.last_updated DESC";
		}

		$sql .= " LIMIT $start, $limit";



		$all_members = $wpdb->get_results($sql_all);

		//print_r($all_members);

		$total_pages = ceil(count($all_members) / $limit);

		//echo "$sql";

		$members = $wpdb->get_results($sql);

		if (!empty($members)) {

			$results['members'] = $members;

			foreach ($members as $mc => $member) {

				$results['members'][$mc]->deceased = false;

				$results['members'][$mc]->avatar = '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . get_template_directory_uri() . '/lib/images/no-image-icon.png&h=120&w=120&zc=1" alt="' . $member->display_name . '">';

				if (strpos($member->dump_fields, 'Deceased') !== FALSE || $dump_fields['Date of Death'] != '') {

					$results['members'][$mc]->deceased = true;
				} else {

					if ($member->user_id != null) {

						$results['members'][$mc]->last_updated = date("m/d/y", strtotime($member->last_updated));

						$results['members'][$mc]->last_updated_str = date("Ymd", strtotime($member->last_updated));

						$results['members'][$mc]->claimed_profile = date("m/d/y", strtotime($member->user_registered));
					}
				}

				$results['members'][$mc]->init_date_format = date("m/d/y", strtotime($member->initiation_date));

				//Avatar

				if ($member->user_id != '') {

					$avatar = get_user_meta($member->user_id, '_alum_avatar', true);

					if ($avatar != '') {

						$results['members'][$mc]->avatar = '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . alum_plugin_url . 'uploads/avatars/' . $avatar . '&h=120&w=120&zc=1" alt="' . $member->display_name . '">';
					}
				}
			}
		}



		$response['code'] = 1;

		$response['msg'] = 'success';

		$response['next_page'] = $next_page;

		$response['prev_page'] = $prev_page;

		$response['current_page'] = $_REQUEST['page'];

		$response['total_pages'] = $total_pages;

		$response['total_members'] = count($all_members);

		$response['data'] = $results['members'];



		return $response;
	}



	public function getInviteList()
	{

		global $wpdb, $alum, $pdo;

		//print_r($_REQUEST);

		$user_id = $_REQUEST['user_id'];

		//$clist = array();

		$cc = 0;

		if (!empty($_REQUEST['contacts'])) {

			$jsonData = stripslashes(html_entity_decode($_REQUEST['contacts']));

			$contacts = json_decode($jsonData, true);

			//print_r($contacts); 

			foreach ($contacts as $contact) {

				//print_r($contact);

				$contact_name = $contact['displayName'];

				//if($contact_name == 'Judson Cooper') {

				//	echo 'Yeah 2';

				//}

				//check DB on phoneNumbers and emails

				if (!empty($contact['phoneNumbers'])) {

					foreach ($contact['phoneNumbers'] as $phoneNumber) {

						$contact_phone = preg_replace("/[^0-9]/", "", $phoneNumber['value']);

						$sql = "SELECT id, user_id, email, phone FROM alumni WHERE replace(replace(replace(replace(phone,'-',''),'(',''), ')',''), ' ','') = '" . $contact_phone . "' AND user_id = ''";

						//if($contact_name == 'Mike Malouf') {

						//	echo 'Yeah';

						//	print_r($contact);

						//	echo "SQL: $sql";	

						//}

						//

						$query = $pdo->prepare($sql);

						$query->execute(array(':phone' => $contact_phone));

						$results = $query->fetchAll(PDO::FETCH_ASSOC);

						if (!empty($results)) {

							//print_r($results);

							//save to return list



							foreach ($results as $result) {

								$member_id = $result['id'];

								$clist[$member_id]['name'] = $contact_name;

								$clist[$member_id]['phone'] = $contact_phone;

								$clist[$member_id]['email'] = $result['email'];

								$cc++;
							}
						}
					}
				}

				if (!empty($contact['emails'])) {

					foreach ($contact['emails'] as $email) {

						$contact_email = $email['value'];

						$sql = "SELECT id, user_id, email, phone FROM alumni WHERE email = :email";

						//echo "SQL: $sql";

						$query = $pdo->prepare($sql);

						$query->execute(array(':email' => $contact_email));

						$results = $query->fetchAll(PDO::FETCH_ASSOC);

						if (!empty($results)) {

							//print_r($results);

							foreach ($results as $result) {

								$member_id = $result['id'];

								$clist[$member_id]['name'] = $contact_name;

								$clist[$member_id]['email'] = $contact_email;

								$clist[$member_id]['phone'] = $result['phone'];

								$cc++;
							}
						}
					}
				}
			}

			//echo "G";

			//echo 'T: ' . json_decode(rtrim($_REQUEST['contacts'], "\0")) . '<br>';

			//$contacts = json_decode($_REQUEST['contacts']);

			//print_r($contacts);

			//var_dump($contacts);

			//var_dump($_REQUEST['contacts']);

			//$f = explode('{', $_REQUEST['contacts']);

			//print_r($f);

		} else {

			//echo "P";

		}

		$response['code'] = 1;

		$response['msg'] = 'success';

		$response['data'] = $clist;



		return $response;
	}



	public function sendInviteList()
	{

		global $wpdb, $alum, $pdo;

		//print_r($_REQUEST);

		//Save to file or DB

		$response['code'] = 1;

		$response['msg'] = 'success';

		//$response['data'] = $clist;

		foreach ($_REQUEST['send_invite'] as $mc => $member_id) {

			//echo alum_plugin_path . 'lib/tmp/invites/member_invite_'. $member_id . '_' . time() . '.txt';

			$fn = alum_plugin_path . 'lib/tmp/invites/member_invite_' . $member_id . '_' . time() . '.txt';

			$fc = 'member_id: ' . $member_id . "\r\n";

			$fc .= 'user_id: ' . $_REQUEST['user_id'] . "\r\n";

			$fc .= 'phone: ' . $_REQUEST['member_phone'][$mc] . "\r\n";

			$fc .= 'email: ' . $_REQUEST['member_email'][$mc] . "\r\n";

			//echo $fc;



			$fp = fopen($fn, 'w');

			if (is_writable($fn)) {

				//echo "file is writable<br>";

			}

			//if(fwrite($fp, $fc)) {

			if (file_put_contents($fn, $fc)) {

				//echo "Written";

				//chmod('/file/path/here', 0775);

			} else {

				//echo "Not Written";

			}

			fclose($fp);
		}

		$response['code'] = 1;

		$response['msg'] = 'success';

		return $response;
	}



	public function sendGroupContact()
	{

		global $wpdb, $alum, $pdo;



		$group_id = $_REQUEST['group_id'];

		$sender_name = $_REQUEST['sender_name'];

		$sender_email = $_REQUEST['sender_email'];

		$sender_comments = $_REQUEST['sender_comments'];

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

		if ($sender_comments != '') {

			$message .= 'Comments: ' . $sender_comments . '<br>';
		}

		$message .= '</body></html>';

		$mail = $alum->sendAlumMail(array('to' => $gr->user_email, 'message' => $message, 'subject' => $subject));

		//mail($gr->user_email, $subject, $message, $headers);

		//if(!isset($results)) {

		$response['code'] = 1;

		$response['msg'] = 'success';

		///}





		return $response;
	}



	public function getJobs()
	{

		global $wpdb, $alum, $pdo;

		$group_id = $_REQUEST['id'];

		$sql = "SELECT j.job_title, j.job_desc, j.city, j.state, j.contact_info, j.last_updated FROM alum_jobs j WHERE 1 AND j.group_id = '" . $group_id . "' AND j.status = 1 ORDER BY j.last_updated DESC";

		//echo $sql;

		$jobs = $wpdb->get_results($sql);

		//Randon looking for hiring....

		$sql = "SELECT g.alum_id, a.user_id, a.state, a.city, CONCAT(a.first_name, ' ', a.last_name, ' ',  a.phone) AS contact_info, m1.meta_value AS job_title FROM alum_groups g, alumni a, wp_usermeta m, wp_usermeta m1 WHERE 1 AND g.group_id = '" . $group_id . "' AND g.status = 1 AND a.id = g.alum_id AND a.user_id != '' AND m.user_id = a.user_id AND m.meta_key = '_alum_are_you_hiring' AND m.meta_value = 'Yes' AND m1.user_id = a.user_id AND m1.meta_key = '_alum_hiring_position' AND m1.meta_value != '' ";

		//echo $sql;

		$jobs2 = $wpdb->get_results($sql);

		//echo '<pre>'; print_r($jobs2); echo '</pre>';

		if (empty($jobs) && !empty($jobs2)) {

			$jobs = $jobs2;
		} elseif (!empty($jobs) && !empty($jobs2)) {

			$jobs = array_merge($jobs, $jobs2);
		}

		if (!empty($jobs)) {

			$response['data'] = $jobs;
		}

		$response['code'] = 1;

		$response['msg'] = 'success';

		return $response;
	}



	public function getJob()
	{

		global $wpdb, $alum, $pdo;

		$job_id = $_REQUEST['id'];
	}



	public function getStateList()
	{

		global $wpdb, $alum, $pdo;

		$stateList = $alum->states;

		$response['data'] = $stateList;

		$response['code'] = 1;

		$response['msg'] = 'success';

		return $response;
	}





	public function getOccupationList()
	{

		global $wpdb, $alum, $pdo;

		$occupationList = $alum->professions;

		$data = array();

		foreach ($occupationList as $occupation => $occupationCat) {

			$data[] = $occupation;
		}

		$response['data'] = $data;

		$response['code'] = 1;

		$response['msg'] = 'success';

		return $response;
	}



	public function getOccupationCatList()
	{

		global $wpdb, $alum, $pdo;

		//$_REQUEST['occupation'];

		$occupationList = $alum->professions;

		$data = array();

		foreach ($occupationList as $occupation => $occupationCat) {

			if ($occupation == $_REQUEST['occupation']) {

				foreach ($occupationCat as $cat) {

					$data[] = $cat;
				}
			}
		}



		$response['data'] = $data;

		$response['code'] = 1;

		$response['msg'] = 'success';

		return $response;
	}



	public function searchSite()
	{

		global $wpdb, $alum, $pdo;

		$rc = 0;

		//Search members first

		$sql = "SELECT a.id, a.user_id, a.first_name, a.last_name, a.middle_name, a.pledge_class, a.fraternity, a.initiation_date, a.lat, a.lng, a.city, a.state, u.user_nicename, u.display_name, a.dump_fields, a.date_joined, a.last_updated, u.user_registered FROM alumni a LEFT JOIN wp_users u ON (a.user_id = u.ID) WHERE 1";

		if ($_REQUEST['search_term'] != '') {

			$sql .= " AND (a.first_name LIKE '%" . $_REQUEST['search_term'] . "%' OR a.last_name LIKE '%" . $_REQUEST['search_term'] . "%'";

			if (strpos($_REQUEST['search_term'], '@') !== FALSE) {

				$sql .= " OR a.email LIKE '%" . $_REQUEST['search_term'] . "%'";
			}

			$sql .= ")";
		}

		//$sql .= " ORDER BY a.last_updated DESC LIMIT $start, $limit";

		$sql .= " ORDER BY a.last_updated DESC";

		//echo $sql;

		$results = $wpdb->get_results($sql);

		if (!empty($results)) {

			foreach ($results as $result) {

				$data[$rc]->id = $result->id;

				$data[$rc]->title = $result->first_name . ' ' . $result->last_name;

				$data[$rc]->type = 'member';

				$data[$rc]->img = '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . get_template_directory_uri() . '/lib/images/no-image-icon.png&h=120&w=120&zc=1" alt="' . $adisplay_name . '">';

				//print_r($result);

				if ($result->user_id != '') {

					$user_data = $alum->getUserData($result->user_id);

					//print_r($user_data);

					if ($user_data->avatar != '' && file_exists(alum_plugin_path . 'uploads/avatars/' . $user_data->avatar)) {

						$avatar = $user_data->avatar;

						$data[$rc]->img = '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . alum_plugin_url . 'uploads/avatars/' . $avatar . '&h=120&w=120&zc=1" alt="' . $adisplay_name . '">';
					}
				}

				$rc++;
			}
		}



		//Search Groups

		$sql2 = "SELECT group_name, id, group_logo FROM groups g WHERE 1";

		if ($_REQUEST['search_term'] != '') {

			$sql2 .= " AND (g.group_name LIKE '%" . $_REQUEST['search_term'] . "%')";
		}

		//echo $sql2;

		$results2 = $wpdb->get_results($sql2);

		if (!empty($results2)) {

			foreach ($results2 as $result2) {

				//$data[$rc] = $result2;

				$data[$rc]->id = $result2->id;

				$data[$rc]->title = $result2->group_name;

				$data[$rc]->type = 'alum';

				$data[$rc]->img = '<img src="' . get_bloginfo('template_url') . '/lib/php/timthumb.php?src=' . get_bloginfo('url') . '/images/alum_group_images/' . $result2->group_logo . '&h=160&w=160&zc=1" alt="' . $result2->group_name . '">';

				$rc++;
			}
		}



		$response['data'] = $data;

		$response['code'] = 1;

		$response['msg'] = 'success';

		return $response;
	}



	public function correctImageOrientation($filename, $orientation, $exif)
	{

		if ($orientation == '') {

			if (function_exists('exif_read_data')) {

				if ($exif == '') {

					$exif = exif_read_data($filename);
				}

				//echo "EXIF<pre>"; print_r($exif); echo '</pre>';

				if ($exif && isset($exif['Orientation'])) {

					$orientation = $exif['Orientation'];
				} // if have the exif orientation info

			} // if function exists 

			else {

				//echo "EXIF function not found";

			}
		}

		//echo "ORENT: $orientation<BR>\r\n";



		if ($orientation != '' && $orientation != 1) {

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

			//echo "DEG $deg";

			if ($deg) {

				$img = imagerotate($img, $deg, 0);
			}

			// then rewrite the rotated image back to the disk as $filename

			imagejpeg($img, $filename, 95);
		} // if there is some rotation necessary

	}
}
