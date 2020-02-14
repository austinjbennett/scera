<?php
/**
 * The template for displaying all events custom post type.
 *
 * This is the template that displays all events by default.
 */

function scera_events_where( $where ) {
  $search = array("meta_key = 'event_dates__event_end_date'", "meta_key = 'event_dates__event_start_date'");
  $replace = array("meta_key LIKE 'event_dates_%_event_end_date'", "meta_key LIKE 'event_dates_%_event_start_date'");
  $where = str_replace($search, $replace, $where);

  return $where;
}

add_filter('posts_where', 'scera_events_where');

get_header(); ?>
	<div class="page-top-wrap">
		<img class="page-top" src="<?php bloginfo('template_directory'); ?>/images/header.png">
		<p class="page-top-title">Movies</p>
	</div>
	<div class="page-bg event-bg">

		<div id="primary" class="site-content">
			<div id="content" role="main">

				<div class="entry-content event-content">

					<div class="highlighted-event">

						<?php $high = get_field('highlighted_movie', 'options');
						if( $high ):
							foreach( $high as $p ):

								if( get_field('poster_image', $p->ID) ):
									$attachment_id = get_field('poster_image', $p->ID);
									$size = "full";
									$image = wp_get_attachment_image_src( $attachment_id, $size );
									?><img class="poster-image" src="<?php echo $image[0]; ?>" alt="" /><?php
								endif; ?>

								<div class="title-info">
									<h1><?php echo get_the_title( $p->ID ); ?></h1>
								</div>

								<div class="event-info">
									<div class="event-date">
									<div class="movie-dates"><?php the_field('movie_dates', $p->ID); ?></div>
									</div>

									<?php // Short Description
									if( get_field('movie_description', $p->ID) ):?>
										<div class="short-description">
											<?php the_field('movie_description', $p->ID); ?>
										</div><?php
									endif; ?>
								</div>

								<div class="further-info">



						<?php if(get_field('movie_times', $p->ID)): ?>
							<div class="times">
								<h4><?php the_field('movie_times_title', $p->ID); ?></h4>
								<div><?php
								while(has_sub_field('movie_times', $p->ID)) : ?>
									<div class="date-wrap">
									<h5><?php the_sub_field('movie_days', $p->ID); ?></h5>
									<?php the_sub_field('show_times', $p->ID); ?>
									</div>
								<?php endwhile; ?>
								</div>
							</div>
						<?php endif; ?>


									<a class="button" style="background-color:#8d0207; color:#fff;" href="">BUY TICKETS</a>

									<a class="post-link" href="<?php echo get_permalink( $p->ID ); ?>">See Details >></a>

								</div>

							<?php
							endforeach;
						endif; ?>
					</div>

					<div class="events-list-wrapper">
						<div class="events-list">
							<?php

							$today = date('Ymd', strtotime('today'));

							$args = array(
								'post_type' => 'movies',
								'posts_per_page' => -1,
								'meta_key' => 'event_dates__event_start_date',
								'orderby' => 'meta_value_num',
								'order' => 'ASC',
								'meta_query' => array(
									array(
									'key' => 'event_dates__event_end_date', //where clause will add a wildcard to this
									'compare' => '>=',
									'value' => $today,
									)
								)
							);

							$movie_q = new WP_Query($args); 

							?>
							<?php while ( $movie_q->have_posts() ) : $movie_q->the_post(); ?>
								<?php get_template_part( 'list', 'movies'); ?>
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
