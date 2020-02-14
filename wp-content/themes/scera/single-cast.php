<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header(); ?>

	<div class="page-top-wrap">
		<img class="page-top" src="<?php bloginfo('template_directory'); ?>/images/header.png">
		<p class="page-top-title">Cast</p>
	</div>
	<div class="page-bg event-bg">

		<div id="primary" class="site-content">
			<div id="content" role="main">

				<div class="entry-content cast-content">

					<div class="cast-list-wrapper">
						<div class="cast-wrap cast-list">

							<div class="picture-wrap">
								<img src="<?php the_field('picture'); ?>" alt="" />
							</div>

							<div class="cast-content-wrap">
								<h2><?php the_title(); ?></h2>
								<div class="cast-content entry-content"><?php global $post; echo $post->post_content; ?></div>
							</div>

						</div>
					</div>

				</div><!-- .entry-content -->

			</div><!-- #content -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>
		<div style="clear:both;"></div>
	</div>
	<img class="page-bottom" src="<?php bloginfo('template_directory'); ?>/images/page-bottom.png">

<?php get_sidebar(); ?>
<?php get_footer(); ?>
