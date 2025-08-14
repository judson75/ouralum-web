<?php
/*
 Template Name: Invite Members Page
 *
*/

if($_GET['id'] != '') {
	$sql = "SELECT user_id, first_name, last_name, email, phone  FROM alumni WHERE 1 AND id = '" . $_GET['id'] . "'";
	//echo $sql;
	$user = $wpdb->get_row($sql);
}

/*
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

*/

if($_REQUEST['claim'] == 1) {
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
			$_SESSION['alum_new_email'] = $tp[0];
		}
		elseif($tp[0] == 'new_phone') {
			$_SESSION['alum_update_phone'] = true;
			$_SESSION['alum_new_phone'] = $tp[0];
		}
	}
}
else {
	$pt = get_the_title();
}
?>
<?php get_header(); ?>
	<div id="inner-content">
		<div class="page-title">
			<div class="wrapper">
				<h1 itemprop="headline"><?php echo $pt ?></h1>
			</div>
		</div>
		<div class="wrapper">
			<main id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
				<?php
				if($_REQUEST['claim'] == 1 && $_REQUEST['hash'] != '') { 
						//Get id from hash
						$sql = "SELECT first_name, last_name, middle_name, pledge_class, fraternity, initiation_date, address, city, state, zipcode, email, user_id, college FROM alumni WHERE id = '$alum_id'";
						$profile = $wpdb->get_row($sql);
						//print_r($profile);
					?>
						<h3>We Found A Matching Profile</h3>
						<p></p>
						<h4><?php echo $profile->first_name . ' ' . $profile->middle_name . ' ' . $profile->last_name; ?></h4>
						<?php
						if($profile->pledge_class != '') {
							echo '<p>Pledge Class: <b>' . $profile->pledge_class . '</b></p>';
						}
						?>
						<p>Initiation Date: <b><?php echo date("M n, Y", strtotime($profile->initiation_date)) ;?></b></p>
						<form id="found-profile-form" method="post" action="<?php  get_bloginfo('url') ;?>/register">
						<input type="hidden" name="alum_profile_id" value="<?php $alum_id ;?>">
						<?php
						if($_SESSION['alum_user_register'] == true) {
							echo '<input type="hidden" name="alum_user_register" value="true">';
						}
						
						if($_SESSION['alum_profile_id'] == true) {
							echo '<input type="hidden" name="alum_profile_id" value="' . $_SESSION['alum_profile_id'] . '">';
						}
						if(isset($_SESSION['alum_user_data'])) {
							echo '<input type="hidden" name="register_user" value="1">';
							foreach($_SESSION['alum_user_data'] as $f => $v) {
								echo '<input type="hidden" name="' . $f. '" value="' . $v . '">';
							}
						}
						if($_SESSION['alum_new_email'] != '') {
							echo '<input type="hidden" name="update_email" value="' . $_SESSION['alum_new_email']. '">';
						}
						if($_SESSION['alum_new_phone'] != '') {
							echo '<input type="hidden" name="update_phone" value="' . $_SESSION['alum_new_phone']. '">';
						}
						?>
						<div class="form-group">
						<input id="box1" type="radio" class="checkbox" name="claim_profile" value="1" /><label for="box1">This is me, <b><u>Claim it</u></b></label>
						</div>
						<div class="form-group">
						<input id="box2" type="radio" class="checkbox" name="claim_profile" value="0" /><label for="box2">This is not me, <b><u>proceed without claiming</u></b></label>
						</div>
						<div class="form-group">
						<button type="button" class="btn btn-primary pr123" style="margin: 0;">Proceed to Register</button>
						</div>
						<!--<button type="button" class="btn btn-sm btn-primary claimFoundProfileBtn" style="display: inline-block; margin: 0;">This is me, Claim it</button> <button type="button" class="btn btn-sm btn-danger dontclaimFoundProfileBtn" style="display: inline-block; margin: 0;">This is not me, proceed without claiming</button>-->
						</form>
					
					<?php
				} else {
					if (have_posts()) : while (have_posts()) : the_post(); ?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
							<section class="entry-content cf" itemprop="articleBody">
								<?php echo '<h4>' . $user->first_name . ' ' . $user->last_name . '</h4>';?>
								<?php
								if($user->email != '') {
									echo '<p>' . $user->email . '</p>';								
								}
								if($user->phone != '') {
									echo '<p>' . $user->phone . '</p>';								
								}
								?>
								<hr>										
								<form method="post" id="invite-member-frm" style="width: 600px;">
									<input type="hidden" name="alumn_id" value="<?php echo $_GET['id'];?>">
									<input type="hidden" name="group_id" value="<?php echo $_GET['gid'];?>">
									<input type="hidden" name="sender_id" value="<?php echo $current_user->ID;?>">
								<div class="form-group">
									<label for="member_email">Email Address:</label>
									<input class="form-input" id="member_email" name="member_email" placeholder="Enter Email Address" type="text" value="<?php if($user->email != '')
	 echo $user->email; ?>">
								</div>
								
								<div class="form-group">
									<label for="member_phone">Phone:</label>
									<input class="form-input" id="member_phone" name="member_phone" placeholder="Enter Phone" type="text" value="<?php if($user->phone != '') 
	 echo $user->phone; ?>">
								</div>
								
								<button type="button" class="btn btn-primary sendInviteBtn">Send Invite</button>
								
								</form>
							</section>
						</article>
					<?php endwhile; endif; 
				} ?>
			</main>
		</div>
	</div>
<?php get_footer(); ?>
