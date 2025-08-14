<?php
/*
 Template Name: Profile Page
 *
*/
?>
<?php get_header(); ?>
	<div class="profile-banner" style="background-image: url('<?php echo get_template_directory_uri();?>/lib/images/banner-placeholder03.jpg');"></div>
	<div id="content">
		<div id="profile-content">
			<main id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
					<section class="entry-content cf" itemprop="articleBody">
						<?php
							the_content();
						?>
					</section> <?php // end article section ?>
				</article>
				<?php endwhile; endif; ?>
			</main>	
		</div>
	</div>
<?php get_footer(); ?>
