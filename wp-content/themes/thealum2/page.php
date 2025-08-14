<?php get_header(); ?>
<div id="inner-content">
	<div class="page-title">
		<div class="wrapper">
			<h1 itemprop="headline"><?php the_title(); ?></h1>
		</div>
	</div>
	<div class="wrapper">
		<main id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
					<section class="entry-content cf" itemprop="articleBody">
						<?php
							the_content();
						?>
					</section>
				</article>
			<?php endwhile; endif; ?>
		</main>
	</div>
</div>
<?php get_footer(); ?>