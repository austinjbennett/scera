<?php
/**
 * The template for displaying all events custom post type.
 *
 * This is the template that displays all events by default.
 */

get_header(); ?>
	<div class="page-top-wrap">
		<img class="page-top" src="<?php bloginfo('template_directory'); ?>/images/header.png">
		<p class="page-top-title">Movies</p>
	</div>
	<div class="page-bg event-bg">

		<div id="primary" class="site-content">
			<div id="content" role="main">

				<?php while ( have_posts() ) : the_post(); ?>
				<div class="entry-content event-content">
				
				<?php
				$today = intval(date('Ymd', strtotime('today')));
				$eventDate = intval(end(get_post_meta(get_the_ID(), 'event_dates_0_event_end_date')));
				$eventIsOver = $today>$eventDate;
				if ($eventIsOver) {
				  update_post_meta( get_the_ID(), _yoast_wpseo_meta-robots-noindex, 1 );
				}
				?>

				<?php // Poster Image ?>
					<div class="category-description">
					<?php if( get_field('poster_image') ):
							$attachment_id = get_field('poster_image');
							$size = "full";
							$image = wp_get_attachment_image_src( $attachment_id, $size );
							?><img class="poster-image" src="<?php echo $image[0]; ?>" alt="" /><?php
						endif; ?>
						<div class="trailer">
						<h4>View the Trailer</h4>
					<?php if( get_field('before_title') ): ?>
						<?php the_field('before_title');?>
						<?php endif; ?>
						</div>
					</div>

					<div class="title-info <?php if (get_field( 'movie_rating' )) { echo 'rating'; }?>"><?php
						$field = get_field_object( 'movie_rating' );
						$value = get_field( 'movie_rating' );
						$label = $field['choices' ][ $value ];

						if ($label === 'PG' ) : ?>
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/rating-pg.png" />
						<?php elseif($label === 'G') : ?>
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/rating-g.png" />
						<?php elseif($label === 'PG-13') : ?>
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/rating-pg-13.png" />
						<?php elseif($label === 'R') : ?>
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/rating-r.png" />
						<?php elseif($label === 'NC-17') : ?>
							<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/rating-nc-17.png" />
						<?php endif; ?>
						<h1><?php the_title(); ?> <span class="runtime"><?php the_field( 'runtime'); ?></span></h1>
					</div>

				<?php // Left Column ?>
					<div class="event-info">

            <?php if (!$eventIsOver): ?>
  						<div class="movie-date <?php if (get_field( 'movie_rating' )) { echo 'rating'; }?>"><?php the_field('movies_subtitle'); ?></div>

						  <div class="ticket-info">
							  <h3>Ticket Purchasing Options</h3>
							  <div class="online-option">
								  <h4>ONLINE:</h4>
								  Click the button below.
								  <a class="ticket-button button" href="<?php if(get_field('buy_online_link')) the_field('buy_online_link');?>"><span>BUY ONLINE</span></a>
							  </div>
							  <div class="hoz-line"></div>
							  <div class="other-option">
								  <h4>IN PERSON:</h4>
								  Open 10am-6pm weekdays & Saturdays from 12pm - 6pm.<br/>
								  Tickets can also be purchased at the box office 30 minutes prior to each showtime.

  </a>
							  </div>
						  </div>
						<?php endif; ?>
						
						<?php // Long Description
						if( get_field('movie_description') ): ?>
							<div class="long-description">
								<h3>Description</h3>
								<?php the_field('movie_description'); ?>
							</div><?php
						endif; ?>
						<div class="sharing">
						<h3>Share This</h3>
							<script charset="utf-8" type="text/javascript">var switchTo5x=true;</script>
							<script charset="utf-8" type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
							<script charset="utf-8" type="text/javascript">stLight.options({"publisher":"wp.a1232dc0-22e7-4fb7-a74c-5c7e3bae4f23"});var st_type="wordpress3.8.1";</script>
							<span class='st_facebook_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
							<span st_via='@SCERAupdate' st_username='SCERAupdate' class='st_twitter_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
							<span class='st_pinterest_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
							<span class='st_googleplus_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
							<span class='st_blogger_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
							<span class='st_reddit_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
							<span class='st_linkedin_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
							<span class='st_email_large' st_title='<?php the_title(); ?>' st_url='<?php the_permalink(); ?>'></span>
						</div>

					</div>

				<?php // Right Column ?>
					<div class="further-info">
					
					  <?php if ($eventIsOver): ?>
					    <div class="times">
							  <h4>SHOWTIMES</h4>
							  <h5>THIS FILM IS NO LONGER BEING SHOWN</h5>
						  </div>
					  <?php else: ?>

						  <?php // Times
						  if(get_field('movie_times')): ?>
							  <div class="times">
								  <h4><?php the_field('movie_times_title'); ?></h4><?php
								  while(has_sub_field('movie_times')) : ?>
									  <h5><?php the_sub_field('movie_days'); ?></h5>
									  <?php the_sub_field('show_times'); ?>
								  <?php endwhile; ?>
							  </div>
						  <?php endif; ?>

						  <?php // Prices
						  if(get_field('ticket_prices')): ?>
							  <div class="prices">
								  <h4><?php the_field('ticket_prices_title'); ?></h4>
								  <h5>Prices:</h5>
									  <?php while(has_sub_field('ticket_prices')) : ?>
									  $<?php the_sub_field('amount_movies');?> <?php the_sub_field('price_types_movies'); ?><br />
									  <?php endwhile; ?>
							  </div><?php
						  endif; ?>

						  <?php // Location
						  if( get_field('location') ): ?>
							  <div class="location">
								  <h4><?php the_field('location_title'); ?></h4>
								  <?php the_field('location'); ?>
							  </div><?php
						  endif; ?>
						
						<?php endif; ?>

					</div>

					<?php // Custom Message
					if( get_field('custom_message') ): ?>
						<div class="message">
							<?php the_field('custom_message'); ?>
						</div><?php
					endif; ?>

					<div class="ast">
						<!-- *Must be purchased in advance, <br>no refunds or exchanges -->
					</div>

				</div>

				<div class="event-tabs">
					<div class="tab-menu">
						<?php if(get_field('tab_1_title') && get_field('tab_1_content')): ?><a class="tabber current" data-target="#tab1"><?php the_field('tab_1_title'); ?></a><?php endif; ?>
						<?php if(get_field('tab_2_title') && get_field('tab_2_content')): ?><a class="tabber" data-target="#tab2"><?php the_field('tab_2_title'); ?></a><?php endif; ?>
						<?php if(get_field('tab_3_title') && get_field('tab_3_content')): ?><a class="tabber" data-target="#tab3"><?php the_field('tab_3_title'); ?></a><?php endif; ?>
						<?php if(get_field('tab_4_title') && get_field('tab_4_content')): ?><a class="tabber" data-target="#tab4"><?php the_field('tab_4_title'); ?></a><?php endif; ?>
						<?php if(get_field('tab_5_title') && get_field('tab_5_content')): ?><a class="tabber" data-target="#tab5"><?php the_field('tab_5_title'); ?></a><?php endif; ?>
					</div>

					<?php if(get_field('tab_1_content') && get_field('tab_1_content')) : ?>
					<div id="tab1" class="tab-block current">
						<?php the_field('tab_1_content'); ?>
					</div>
					<?php endif; ?>
					<?php if(get_field('tab_2_content') && get_field('tab_2_content')) : ?>
					<div id="tab2" class="tab-block">
						<?php the_field('tab_2_content'); ?>
					</div>
					<?php endif; ?>
					<?php if(get_field('tab_3_content') && get_field('tab_3_content')) : ?>
					<div id="tab3" class="tab-block">
						<?php the_field('tab_3_content'); ?>
					</div>
					<?php endif; ?>
					<?php if(get_field('tab_4_content') && get_field('tab_4_content')) : ?>
					<div id="tab4" class="tab-block">
						<?php the_field('tab_4_content'); ?>
					</div>
					<?php endif; ?>
					<?php if(get_field('tab_5_content') && get_field('tab_5_content')) : ?>
					<div id="tab5" class="tab-block">
						<?php the_field('tab_5_content'); ?>
					</div>
					<?php endif; ?>
				</div>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>
		<div style="clear:both;"></div>
	</div>
	<img class="page-bottom" src="<?php bloginfo('template_directory'); ?>/images/page-bottom.png">
	<script src="<?php bloginfo('template_directory'); ?>/js/tabs.js"></script>
<?php get_footer(); ?>
