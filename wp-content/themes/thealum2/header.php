<?php
$logout = get_query_var('logout');
if($logout == 1) {
	//exit;
	wp_logout();
	wp_redirect( get_bloginfo('url')) ;
	exit;
}
$pagename = get_query_var('pagename');
//echo "PAGENAME: " .  $pagename;
$home_nav_class = ($pagename == '') ? 'active' : '';
$about_nav_class = ($pagename == 'about-us') ? 'active' : '';
$alums_nav_class = ($pagename == 'alums' || $pagename == 'alum') ? 'active' : '';
$profile_nav_class = ($pagename == 'profile') ? 'active' : '';
?>
<!doctype html>
<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
	<head>
		<meta charset="utf-8">
		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title><?php wp_title(''); ?></title>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<meta name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/lib/images/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/lib/images/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/lib/images/favicon-16x16.png">
		<link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/lib/images/site.webmanifest">
		<link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/lib/images/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">
		
		<link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.png">
		<!--[if IE]>
			<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/lib/images/favicon.ico">
		<![endif]-->
		<?php // or, set /favicon.ico for IE10 win ?>
		<meta name="msapplication-TileColor" content="#f01d4f">
		<meta name="msapplication-TileImage" content="<?php echo get_template_directory_uri(); ?>/library/images/win8-tile-icon.png">
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/lib/css/slick.css">
  		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/lib/css/slick-theme.css">
		<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
		<?php wp_head(); ?>
		<link href="<?php echo get_template_directory_uri(); ?>/lib/css/jquery.dataTables.min.css" type="text/css" rel="stylesheet" media="all">
		<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/lib/css/style.css">
		<link type="text/css" rel="stylesheet" media="all" href="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/alumn/css/jquery-jvectormap-2.0.3.css">
		<script type="text/javascript" src="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/alumn/js/jquery-jvectormap-2.0.3.min.js"></script>
		<script type="text/javascript" src="<?php echo get_bloginfo('url'); ?>/wp-content/plugins/alumn/js/jquery-jvectormap-us-lcc.js"></script>
		<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900" rel="stylesheet">
		<meta name="apple-itunes-app" content="app-id=1455246991">
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=UA-114660183-1"></script>
		<script>
		  window.dataLayer = window.dataLayer || [];
		  function gtag(){dataLayer.push(arguments);}
		  gtag('js', new Date());
		
		  gtag('config', 'UA-114660183-1');
		</script>
	</head>
	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">
		<div id="outer">
			<div id="header">
				<div id="header-inner" class="wrapper">
					<div id="logo">
						<a href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/lib/images/alum_logo.jpg" alt=""></a>
					</div>
					<div id="top-nav">
						<ul>
							<li class="<?php echo $home_nav_class;?>"><a href="<?php echo home_url(); ?>">Home</a></li>
							<li class="<?php echo $about_nav_class;?>"><a href="<?php echo get_bloginfo('url');?>/about">About</a></li>
							<li class="<?php echo $alums_nav_class;?>"><a href="<?php echo get_bloginfo('url');?>/alums">Alum Chapters</a></li>
							<?php if(is_user_logged_in()) { ?>
							<li class="<?php echo $profile_nav_class;?>"><a href="<?php echo get_bloginfo('url');?>/profile">My Profile</a></li>
							<li><a href="<?php echo get_bloginfo('url');?>/logout">Logout</a></li>
							<?php } else { ?>
							<li><a href="<?php echo get_bloginfo('url');?>/claim-profile">Find Profile</a></li>
							<li><a href="<?php echo get_bloginfo('url');?>/login">Login</a></li>
							<?php } ?>
						</ul>
						<div id="mobile-nav"><i class="fas fa-bars"></i></div>
					</div>
				</div>
			</div>
			<?php if(is_front_page() || is_home()) { ?>
			<div id="banner">
				<div class="wrapper">
					<div id="banner-inner">
						<?php if(!is_user_logged_in()) { ?>
							<div class="banner-links">
								<ul>
									<li><a href="#find-profile" class="scroll">Ole Miss &amp; Millsaps Kappa Sigma, Click To Find Your Profile.</a></li>
									<li><a href="<?php echo get_bloginfo('url');?>/login">If you have claimed your profile, click here to login</a></li>
								</ul>
							</div>
						<?php } ?>
						<h2>Connecting Brothers with Brothers</h2>
						<p>Providing our Brothers with a reliable, confidential, and safe method of sharing and communicating with all of our Kappa Sigma Brothers.</p>
					</div>
				</div>
				<div id="banner-img"><img src="<?php echo get_template_directory_uri(); ?>/lib/images/banner_sig.jpg" alt=""></div>
			</div>
			<?php } ?>
			