<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>
	<div class="page-top-wrap">
		<img class="page-top" src="<?php bloginfo('template_directory'); ?>/images/header.png">
		<p class="page-top-title"><?php the_title(); ?></p>
	</div>
	<div class="page-bg">

		<div id="primary" class="site-content">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>
				<div class="entry-content">
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<?php if(get_field('page_heading')){ ?><h1><?php the_field('page_heading'); ?></h1><?php } ?>
						<?php if(get_field('subheading')){ ?><h2><?php the_field('subheading'); ?></h2><?php } ?>
						<?php the_content(); ?>
					</article><!-- #post -->
				</div>
				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>
		<div style="clear:both;"></div>
	</div>
	<img class="page-bottom" src="<?php bloginfo('template_directory'); ?>/images/page-bottom.png">
<?php get_footer(); ?>