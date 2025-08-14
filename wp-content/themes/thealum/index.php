<?php get_header(); global $alum; ?>
<!--/about -->
<div id="about" class="about all_pad w3ls">
	<div class="container">
		<div class="ser-top-grids">
			<div class="col-md-4 ser-grid wow flipInY" data-wow-duration="1.5s" data-wow-delay="0s">
				<div class="con-left text-center">
					<div class="spa-ico"><span><i class="fa fa-book" aria-hidden="true"></i></span></div>
					<h5>Alumni Groups</h5>
					<p>A central listing for alumni associations across the nation.</p>				
				</div>
			</div>
			<div class="col-md-4 ser-grid wow flipInY" data-wow-duration="1.5s" data-wow-delay="0s">
				<div class="con-left text-center">
					<div class="spa-ico"><span><i class="fa fa-pencil" aria-hidden="true"></i></span></div>
					<h5>Update</h5>
					<p>Keep your information up-to-date, easily and simply. Our database is updated frequently.</p>
					
				</div>
			</div>
			<div class="col-md-4 ser-grid wow flipInY" data-wow-duration="1.5s" data-wow-delay="0s">
				<div class="con-left text-center">
					<div class="spa-ico"><span><i class="fa fa-user" aria-hidden="true"></i></span></div>
					<h5>Connect</h5>
					<p>Search for members of your alum, all in one easy to find location.</p>
					
				</div>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<!--//about -->
<div class="blue-box" id="member-count">
	<div class="wrap members-count">
		<i class="fa fa-users" aria-hidden="true"></i>
		<h3><?php echo $alum->getMemberCount();?></h3>
		<p>Alumni Members</p>
	</div>
</div>

<!--/services -->
<!--
<div class="services" id="services">
	<div class="w3-services-head">
		<h3>Services</h3>
	</div>
	<div class="clearfix"></div>
</div>-->
<!--//services -->
<!--/contact -->
<?php if(!is_user_logged_in()) { ?>
<div class="bg-agile w3-admission" id="admission">
	<div class="book-appointment" id="claim_profile">
		<div class="w3-appoint-head">
			<h3>Find Your Profile</h3>
		</div>
		<form action="#" method="post" id="claim-form">
			<div class="left-agileits-w3layouts same" style="float: none; margin: 0 auto;">
				<div class="gaps">
					<p>First Name</p>
					<input type="text" name="first_name" placeholder="" required=""/>
				</div>
				<div class="gaps">
					<p>Middle Initial</p>
					<input type="text" name="middle_name" placeholder="" required=""/>
				</div>
				<div class="gaps">
					<p>Last Name</p>
					<input type="text" name="last_name" placeholder="" required=""/><br>
					<small style="display: block; margin-top: -15px; margin-bottom: 20px;">Last name only, do not enter suffix</small>
				</div>	
				<div class="gaps">	
					<p>Initiation Year (1999)</p>
					<input type="text" name="initiation_date" placeholder="" required="" maxlength="4"  />
				</div>
			</div>
			<div class="clearfix"></div>
		  	<button type="button" class="sendClaimProfile">Find My Profile</button>
		</form>
	
	</div>	
</div>
<?php } ?>
<div style="height: 120px; display: block;"></div>
<?php get_footer(); ?>
