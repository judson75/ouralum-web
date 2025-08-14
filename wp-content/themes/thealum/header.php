<!DOCTYPE html>
<html lang="en">
<head>
<title><?php wp_title('|', true, 'right'); ?></title>
<!-- Meta tag Keywords -->
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="keywords" content="" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false);
function hideURLbar(){ window.scrollTo(0,1); } </script>
<!--// Meta tag Keywords -->
<!-- css files -->
<link href="<?php echo get_template_directory_uri(); ?>/css/bootstrap.min.css" rel="stylesheet" type="text/css" media="all">
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/css/jquery-ui.css" />
<link href="<?php echo get_template_directory_uri(); ?>/css/font-awesome.min.css" rel="stylesheet" type="text/css" media="all">
<link href="<?php echo get_template_directory_uri(); ?>/css/carousel.css" type="text/css" rel="stylesheet" media="all">
<link href="<?php echo get_template_directory_uri(); ?>/fonts/stylesheet.css" type="text/css" rel="stylesheet" media="all">
<link href="<?php echo get_template_directory_uri(); ?>/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="all">

<!-- gallery -->
<link href="<?php echo get_template_directory_uri(); ?>/css/lsb.css" rel="stylesheet" type="text/css">
<!-- //gallery -->
<!-- /fonts -->
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic" rel="stylesheet">
<!-- //fonts -->
<!-- //css files -->
<!-- js -->
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/jquery-2.1.4.min.js"></script>
<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/js/bootstrap-3.1.1.min.js"></script>
<!-- //js -->
<?php wp_head(); ?>
<link type="text/css" rel="stylesheet" media="all" href="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/alumn/css/jquery-jvectormap-2.0.3.css">
<script type="text/javascript" src="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/alumn/js/jquery-jvectormap-2.0.3.min.js"></script>
<script type="text/javascript" src="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/alumn/js/jquery-jvectormap-us-lcc.js"></script>
<link href="<?php echo get_template_directory_uri(); ?>/css/style.css" rel="stylesheet" type="text/css" media="all">
</head>
<body>
<?php if(is_home() || is_front_page()) { ?>
<!--header-banner-section-starts-here -->
<section class="banner-header" id="home">
	<!--header-->
	<div class="header">
		<?php include('top_menu.php');?>
		<?php if(!is_user_logged_in()) { ?>
			<div class="w3-banner-links">
				<ul class="banner-agileits">
					<li><a href="#admission" class="scroll">Ole Miss Kappa Sigma, Click To Find Your Profile.</a></li>
					<li style="margin-top: -10px;"><a href="javascript.Void(0);" class="scroll">If you have claimed your profile, <span onclick="location.href='<?php echo get_bloginfo('url');?>/login;'">click here to login</span></a></li>
				</ul>
			</div>
		<?php } ?>			
	</div>
	<!--//header-->
	<!-- banner -->
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
		<!-- Indicators -->
		<!--<ol class="carousel-indicators">
			<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
			<li data-target="#myCarousel" data-slide-to="1" class=""></li>
			<li data-target="#myCarousel" data-slide-to="2" class=""></li>
			<li data-target="#myCarousel" data-slide-to="3" class=""></li>
			<li data-target="#myCarousel" data-slide-to="4" class=""></li> 
		</ol>-->
		<div class="carousel-inner" role="listbox">
			<div class="item active"> 
				<div class="container">
					<div class="carousel-caption">
						<h2>Connecting Alums, Classmates, and Friends with their Organizations, Groups, and Schools.</h2>
						<p>Providing our members with a single, reliable, confidential and safe method of updating your personal information to all your membership or alumni organizations.</p>
						<!--<button class="btn btn-primary" data-target="#myModal" data-toggle="modal">Read more</button>-->
					</div>
				</div>
			</div>
			<!--
			<div class="item item2"> 
				<div class="container">
					<div class="carousel-caption">
						<h3> Proin finibus facilis</h3>
						<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
						<button class="btn btn-primary" data-target="#myModal" data-toggle="modal">Now Open</button>
					</div>
				</div>
			</div>
			<div class="item item3"> 
				<div class="container">
					<div class="carousel-caption">
						<h3>Nullam ut dapibus </h3>
						<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
						<button class="btn btn-primary" data-target="#myModal" data-toggle="modal">Now Open</button>
					</div>
				</div>
			</div>
			<div class="item item4"> 
				<div class="container">
					<div class="carousel-caption">
						<h3>In ultrices mauris.</h3>
						<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
						<button class="btn btn-primary" data-target="#myModal" data-toggle="modal">Now Open</button>
					</div>
				</div>
			</div>
			<div class="item item5"> 
				<div class="container">
					<div class="carousel-caption">
						<h3>Aenean quis velit.</h3>
						<p>Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.</p>
						<button class="btn btn-primary" data-target="#myModal" data-toggle="modal">Now Open</button>
					</div>
				</div>
			</div> 
			-->
		</div>
		<!--
		<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
			<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
			<span class="sr-only">Previous</span>
		</a>
		<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
			<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
			<span class="sr-only">Next</span>
		</a>
		-->
		<!-- The Modal -->
		<div id="myModal" class="modal wthree-modal" tabindex="-1"> 
			<!-- Modal content -->
			<div class="modal-content">
				<div class="modal-header">
					<span class="close" data-dismiss="modal" >&times;</span>
					<h3>TheAlum Portal</h3>
				</div>
				<div class="col-md-6 modal-img">
					<img src="<?php echo get_template_directory_uri(); ?>/images/ban1.jpg" class="img-responsive" alt="w3layouts" title="w3layouts">
				</div>
				<div class="col-md-6 modal-text">
					<p class="banner-p1">As a member of TheAlum.net you will be able to:
						<ol>
							<li>Update all your personal information on each of your membership and alumni organizations.</li>
							<li>Select only the information you want each of your organizations to view.</li>
							<li>View map to locate members of your organization</li>
							<li>Search your members by name, location, or industry.</li>
							<li>View calendar of events from each of your organizations.</li>
							<li>Elect to receive updates and messages from your organizations.</li>
							<li>Post job listings to hire from within your organization.</li>
							<li>Post your occupation to promote trade within your organization.</li>
						</ol>
					</p>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<?php if(!is_user_logged_in()) { ?>		
			<div class="thim-click-to-bottom">
				<a href="#admission" class="scroll">
					<i class="fa  fa-chevron-down"></i>
				</a>
			</div>
		<?php } ?>
    </div> 
	<!-- //banner -->
</section>
<!--//header-banner-section-end-here -->
<?php } else { ?>
<!--//header-->
<?php } ?>