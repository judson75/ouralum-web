<?php
/* Template Name: HomePage */
get_header(); 
?>
<div id="content">
	<div id="" class="wrapper">
		<main id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
			<?php endwhile; ?>
			<?php endif; ?>
		</main>
	</div>
	<div class="wrapper" id="member-count">
		<div class="members-count">
			<i class="fa fa-users" aria-hidden="true"></i>
			<h3><?php echo $alum->getMemberCount();?></h3>
			<p>Alumni Members</p>
		</div>
	</div>
	<?php if(!is_user_logged_in()) { ?>
	<!-- Find Profile -->
	<div id="find-profile">
		<div id="find-profile-inner" class="wrapper">
			Find Your Profile 
		</div>
	</div>
	<div id="cp-form" class="wrapper">
		<form action="#" method="post" id="claim-form">
			<div class="">
				<div class="form-group">
					<input class="form-input" type="text" name="first_name" placeholder="First Name" required=""/>
				</div>
				<div class="form-group">
					<input class="form-input" type="text" name="middle_name" placeholder="Middle Initial" required=""/>
				</div>
				<div class="form-group">
					<input class="form-input" type="text" name="last_name" placeholder="Last Name" required=""/>
					<div class="helper">Last name only, do not enter suffix</div>
				</div>	
				<div class="form-group">	
					<input class="form-input" type="text" name="initiation_date" placeholder="Initiation Year (1999)" required="" maxlength="4"  />
				</div>
			</div>
			<div class="clearfix"></div>
		  	<button type="button" class="sendClaimProfile">Find My Profile</button>
		</form>
	</div>
	<?php } ?>
</div>
<?php get_footer(); ?>
