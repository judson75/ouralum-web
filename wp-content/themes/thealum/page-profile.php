<?php
/*
 Template Name: Profile Page
 *
 * This is your custom page template. You can create as many of these as you need.
 * Simply name is "page-whatever.php" and in add the "Template Name" title at the
 * top, the same way it is here.
 *
 * When you create your page, you can just select the template and viola, you have
 * a custom page template to call your very own. Your mother would be so proud.
 *
 * For more info: http://codex.wordpress.org/Page_Templates
*/
?>
<?php get_header(); ?>
	<div class="profile-banner" style="background-image: url('<?php echo get_template_directory_uri();?>/images/banner-placeholder03.jpg');">
		<div class="inner-header">
			<div class="header">
				<?php include('top_menu.php');?>
			</div>
		</div>
	</div>
	<div id="content">
		<div id="profile-content" class="wrap cf">
			<main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
				<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
					<section class="entry-content cf" itemprop="articleBody">
						<?php
							the_content();
						?>
					</section> <?php // end article section ?>
					<footer class="article-footer cf"></footer>
				</article>
				<?php endwhile; endif; ?>
			</main>	
		</div>
	</div>
<?php get_footer(); ?>
