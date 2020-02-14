<?php
/**
 * The template for displaying all events custom post type.
 *
 * This is the template that displays all events by default.
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
						<div class="cast-list">
							<?php while ( have_posts() ) : the_post(); ?>
							<div class="arch-cast">
								<div class="arch-safelane">
								<?php if( get_field('picture', $p->ID) ): ?>
								<div class="picture-wrap">
									<img src="<?php the_field('picture', $p->ID); ?>" alt="" />
								</div><?php
								endif; ?>
									<h2><?php the_title(); ?></h2>

									<div class="link-wrapper"><a class="post-link" href="<?php the_permalink(); ?>">Read Full Bio >></a></div>
								</div>
							</div>
							<?php endwhile; // end of the loop. ?>
						</div>
					</div>

				</div><!-- .entry-content -->

			</div><!-- #content -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>
		<div style="clear:both;"></div>
	</div>
	<img class="page-bottom" src="<?php bloginfo('template_directory'); ?>/images/page-bottom.png">
	<script src="<?php bloginfo('template_directory'); ?>/js/tabs.js"></script>
<?php get_footer(); ?>