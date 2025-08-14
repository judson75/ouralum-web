
	<nav class="navbar navbar-default">
		<div class="container">
			<div class="navbar-header navbar-search">
				<i class="fa fa-search showSearchBtn"></i>
				<div id="top-search">
					<form action="<?php echo get_bloginfo('url'); ?>/members" method="get">
						<div class="ts-input">
							<input type="text" name="search" value="<?php echo $_GET['s'];?>"><button class="btn btn-primary"><i class="fa fa-search"></i></button>
						</div>
					</form>
				</div>
			</div>
			<div class="navbar-header navbar-left">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<div class="w3_navigation_pos">
					<h1><a href="<?php echo get_bloginfo('url'); ?>/">The Alum</a></h1>
				</div>
			</div>
			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse navbar-right" id="bs-example-navbar-collapse-1">
				<nav class="link-effect-2" id="link-effect-2">
					<ul class="nav navbar-nav">
						<li class="active"><a href="<?php echo get_bloginfo('url'); ?>/">Home</a></li>
						<li><a href="<?php echo get_bloginfo('url'); ?>/about-us" class="">About</a></li>
						<li><a href="<?php echo get_bloginfo('url'); ?>/alums" class="">Alum Chapters</a></li>
						<li><a></a></li>
						<?php
						if ( is_user_logged_in() ) {
						    echo '<li><a href="' . get_bloginfo('url') . '/profile" class="">My Profile</a></li>';
						    echo '<li><a href="' . wp_logout_url( get_bloginfo('url') ) . '" class="">Logout</a></li>';
						} 
						else {
						   // echo '<li><a href="' . get_bloginfo('url') . '/register" class="">Register</a></li>';
							echo '<li><a href="' . get_bloginfo('url') . '/claim-profile" class="">Claim Profile</a></li>';
							echo '<li><a href="' . get_bloginfo('url') . '/login" class="">Login</a></li>';
						}
						?>
						
					</ul>
				</nav>
			</div>
		</div>
	</nav>			
