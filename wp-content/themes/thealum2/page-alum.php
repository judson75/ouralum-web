<?php
/*
 Template Name: Alum Page
 *
 * 
*/

$alum_slug = sanitize_title($alum_slug);
$sql = "SELECT group_banner FROM groups WHERE 1 AND group_slug = '$alum_slug'";
//echo $sql;
$group_banner = $wpdb->get_row($sql);
//print_r($group_banner);
if($group_banner->group_banner != '') {
	$banner = get_bloginfo('url') . '/images/alum_group_images/' . $group_banner->group_banner;
}
else {
	$banner = get_template_directory_uri(). '/lib/images/banner-placeholder03.jpg';
}
?>
<?php get_header(); ?>
	<div class="alum-banner" style="background-image: url('<?php echo $banner;?>');"></div>
	<div id="content">
		<div id="alum-content">
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
