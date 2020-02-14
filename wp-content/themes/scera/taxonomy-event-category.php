<?php
/**
 * The template for displaying all events custom post type.
 *
 * This is the template that displays all events by default.
 */

get_header(); ?>
<div class="page-top-wrap">
	<img class="page-top" src="<?php bloginfo('template_directory'); ?>/images/header.png">
	<?php $queried_object = get_queried_object(); ?>
	<p class="page-top-title"><?php echo $queried_object->name;?></p>
</div>
<div class="page-bg event-bg">

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<div class="entry-content event-content">

				<?php $description = term_description();?>
				<?php if( $description) : ?>

					<div class="category-description">

						<?php if(get_field( 'description_image', $queried_object )) : ?>
							<img src="<?php the_field( 'description_image', $queried_object ); ?>" />
						<?php endif; ?>

						<!-- <div class="category-description-wrap"> -->

							<?php if(get_field( 'description_title', $queried_object )) : ?>
								<h1><?php the_field( 'description_title', $queried_object ); ?></h1>
							<?php endif; ?>

							<?php echo $description; ?>
						<!-- </div> -->

					</div>

				<?php endif; ?>

				<?php $high = get_field( 'highlighted_event', $queried_object); ?>
				<?php if( $high ): ?>

					<div class="highlighted-event">

						<?php foreach( $high as $p ): ?>
							<?php $terms = wp_get_post_terms( $p->ID); ?>

							<?php if( get_post_type($p) == 'events') {
								include(locate_template( 'highlighted-events.php'));
							} else {
								include(locate_template( 'highlighted-education.php'));
							} ?>

						<?php endforeach; ?>

					</div>

				<?php endif; ?>

				<div class="events-list-wrapper">
					<div class="events-list">
						<?php function scera_events_where( $where ) {

							$search = array("meta_key = 'event_dates__event_end_date'", "meta_key = 'event_dates__event_start_date'");
							$replace = array("meta_key LIKE 'event_dates_%_event_end_date'", "meta_key LIKE 'event_dates_%_event_start_date'");
							$where = str_replace($search, $replace, $where);

							return $where;
						}

						add_filter('posts_where', 'scera_events_where');

						$date = date('Ymd');
						$current_tax = $queried_object->slug;

						$args = array(
							'post_type' => 'events',
							'meta_key' => 'event_dates__event_start_date',
							'orderby' => 'meta_value_num',
							'order' => 'ASC',
							'event-category' => $current_tax,
							'meta_query' => array(
								array(
									'key' => 'event_dates__event_end_date',
									'compare' => '>=',
									'value' => $date,
									)
								)
							);

							$query = new WP_Query($args); ?>
							<?php if($query->have_posts()) : ?>
								<?php while ( $query->have_posts() ) : $query->the_post(); ?>

									<?php if( has_term( 'education', 'event-category' )) {
										get_template_part( 'list', 'education');
									} else {
										get_template_part( 'list', 'events');
									} ?>

								<?php endwhile; // end of the loop. ?>
							<?php else: ?>
								<div class="arch-event">
									<h3>There are no events currently in this category</h3>
									<a href="/events" class=" button" style="background-color:#8d0207; color:#fff;">See Current Events</a>
								</div>
							<?php endif; ?>
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
