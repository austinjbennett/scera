<?php
/**
 * The template for displaying all events custom post type.
 *
 * This is the template that displays all events by default.
 */

get_header(); ?>
	<div class="page-top-wrap">
		<img class="page-top" src="<?php bloginfo('template_directory'); ?>/images/header.png">
		<p class="page-top-title">Events</p>
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
					<?php if( get_field('poster_image') ):
						$attachment_id = get_field('poster_image');
						$size = "full";
						$image = wp_get_attachment_image_src( $attachment_id, $size );
						?><img class="poster-image" src="<?php echo $image[0]; ?>" alt="" /><?php
					endif; ?>

					<div class="title-info <?php if (get_field( 'age_group' )) { echo 'age'; }?>">
						<?php if (get_field( 'age_group' )) :?>
						<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ages-bg.png" />
						<p class="age-text"><span><?php the_field('education_title')?></span><span><?php the_field('age_group'); ?></span></p>
						<?php endif; ?>
						<h1><?php the_title(); ?>
						<?php if( get_field('sponsored_by') ):?><div class="sponsored">SPONSORED BY: <span><?php the_field('sponsored_by');?></span></div><?php endif; ?>
						<?php if( get_field('spots_open') ):?><div class="spots-open"><span><?php the_field('spots_open');?></span></div><?php endif; ?></h1>
					</div>

				<?php // Left Column ?>
					<div class="event-info">

						<div class="event-date <?php if (get_field( 'age_group' )) { echo 'age'; }?>">
							<?php

							if( get_field('semester') ) {
								the_field('semester');
								echo' - ';
							}

							if(get_field('event_dates')):
								$count = 1;
								while(has_sub_field('event_dates')):

									if(get_sub_field('event_start_date')!=''):
									// the_sub_field('event_start_date');
										if($count > 1 ) {echo ' | ';}

										$date1 = DateTime::createFromFormat('Ymd', get_sub_field('event_start_date'));
										$date2 = DateTime::createFromFormat('Ymd', get_sub_field('event_end_date'));

										if(get_sub_field('event_end_date') && $date1 != $date2):
											if ( $date1->format('Y') == $date2->format('Y') ){
												echo $date1->format('F d - ');
											} else {
												echo $date1->format('F d, Y - ');
											}
											echo $date2->format('F d, Y');
											elseif(get_sub_field('event_start_date')):
											echo $date1->format('F d, Y');
										else:
											echo'';
										endif;
										$count ++;

									endif;
								endwhile;
							endif;
							?>

						</div>

						<?php // Short Description
						if( get_field('short_description') ):?>
							<div class="short-description">
								<?php the_field('short_description'); ?>
							</div><?php
						endif; ?>

            <?php if (!$eventIsOver): ?>
						  <?php if(get_field('buy_online_link')) : ?>
						  <div class="ticket-info">
							  <h3>Ticket Purchasing Options</h3>
							  <div class="online-option">
								  <h4>ONLINE:</h4>
								  Click the button below.
								  <a class="ticket-button button" href="<?php if(get_field('buy_online_link')) the_field('buy_online_link');?>"><span>BUY ONLINE</span></a>
							  </div>
							  <div class="hoz-line"></div>
							  <div class="other-option">
								  <h4>PHONE/IN PERSON:</h4>
								  <span>801.225.ARTS</span>
								  Open 10am-6pm weekdays & Saturdays from 12pm - 6pm. <?php if (get_field('seating_map')) { ?><a href="<?php the_field('seating_map')?>">Seating Map >></a> <?php } else { ?><span class="general-admission">General Admission</span><?php } ?>
							  </div>
						  </div>
						  <?php else : ?>
						  <div class="registration-info <?php if(!get_field('audition_info') && !get_field('register_online')) { echo 'no-audition'; } ?>">
							  <?php if(get_field('alternative_content')) :?>
								  <div class="alternative-content">
									  <?php the_field('alternative_content'); ?>
								  </div>
							  <?php else: ?>
								  <h3>Registration Options</h3>
								  <?php if(get_field('audition_info')) : ?>
								  <div class="audition-info">
									  <h4>Audition Info:</h4>
									  <?php the_field( 'audition_info' ); ?>
								  </div>
								  <div class="hoz-line"></div>
								  <?php endif; ?>
								  <?php if(get_field('register_online')) : ?>
								  <div class="audition-info">
									  <h4>Online:</h4>
									  Click the button below.
									  <a class="button" style="background-color:#8d0207; color:#fff;" href="<?php the_field('register_online'); ?>">REGISTER</a>
								  </div>
								  <div class="hoz-line"></div>
								  <?php endif; ?>
								  <div class="other-option">
									  <h4>PHONE/IN PERSON:</h4>
									  <span>801.225.ARTS</span>
									  Open 10am-6pm weekdays & Saturdays from 12pm - 6pm.
								  </div>
							  <?php endif; ?>
						  </div>
						  <?php endif; ?>
						<?php endif; ?>

						<?php // Long Description
						if( get_field('long_description') ): ?>
							<div class="long-description">
								<h3>Description</h3>
								<?php the_field('long_description'); ?>
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
							  <h4>Times</h4>
							  <h5>THIS EVENT HAS PASSED</h5>
						  </div>
					  <?php else: ?>

						  <?php // Instructor
						  if( get_field('main_instructor') ): ?>
							  <div class="instructor">
								  <h4>Instructor:</h4>
								  <?php the_field('main_instructor'); ?>
							  </div><?php
						  endif; ?>

						  <?php // Times
						  if(get_field('times')): ?>
							  <div class="times">
								  <h4>Times:</h4><?php
								  while(has_sub_field('times'))
								  {
									  the_sub_field('time');?><br /><?php
								  } ?>
							  </div>
						  <?php endif; ?>

						  <?php // Prices
						  if(get_field('prices') || get_field('prices_wysiwyg')): ?>
							  <div class="prices">
								  <h4>Prices:</h4>
								  <?php if (get_field('prices_wysiwyg')) {
									  the_field('prices_wysiwyg');
								  }	elseif (get_field('prices')) {
									  while(has_sub_field('prices'))
									  {
										  the_sub_field('price_types');
										  ?> - $<?php the_sub_field('amount');
										  ?><br><?php
									  }
								  } ?>

								  <?php // Group Rates
								  if(get_field('price_group_rates')): ?>
									  <div class="group-rates">
										  <h5>GROUP RATES</h5><?php
										  while(has_sub_field('price_group_rates'))
										  {
											  the_sub_field('rate');
											  ?><br><?php
										  } ?>
									  </div><?php
								  endif; ?>


							  </div><?php
						  endif; ?>

						  <?php // Prices
						  if(get_field('tuition')): ?>
							  <div class="tuition">
								  <h4>Tuition & Fees:</h4>
								  <h5>TUITION</h5><?php
								  while(has_sub_field('tuition'))
								  {
									  the_sub_field('tuition_price');
									  ?><br><?php
								  } ?>

								  <?php // Group Rates
								  if(get_field('fees')): ?>
									  <div class="fees">
										  <h5>FEES</h5><?php
										  while(has_sub_field('fees'))
										  {
											  the_sub_field('fee');
											  ?><br><?php
										  } ?>
									  </div><?php
								  endif; ?>

							  </div><?php
						  endif; ?>

						  <?php // Location
						  if( get_field('location') ): ?>
							  <div class="location">
								  <h4>Location:</h4>
								  <?php the_field('location'); ?>
								  <a href="<?php the_field('directions'); ?>">Get Directions >></a>
							  </div><?php
						  endif; ?>

						  <?php // Location
						  if( get_field('contacts') ): ?>
							  <div class="contacts">
								  <h4>Contacts:</h4>
								  <a href="mailto:<?php the_field('contacts'); ?>"><?php the_field('contacts'); ?></a>
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

					<?php if(get_field('buy_online_link')) : ?>
					<!-- <div class="ast">
						*Must be purchased in advance, <br>no refunds or exchanges
					</div> -->
					<?php endif; ?>

				</div>

				<?php if( has_term( 'education', 'event-category' )) {
					get_template_part( 'tabs', 'education');
				} else {
					get_template_part( 'tabs', 'events');
				} ?>

				<?php $posts = get_field('event_sponsors'); ?>
				<?php if(  $posts ): ?>
					<div class="sponsors">
					<h4>Sponsored by:</h4>
						<div class="flexslider">
							<ul class="slides">
								<?php foreach ( $posts as $post ) : ?>
								<?php setup_postdata($post); ?>
								<li class="sponsored-image"><a href="<?php the_field('sponsor_site'); ?>" target="_blank"><?php the_post_thumbnail( 'full' ); ?></a></li>
							<?php endforeach; ?>
							</ul>
						</div>
					</div>
				<?php endif; ?>

				<?php endwhile; // end of the loop. ?>

			</div><!-- #content -->
		</div><!-- #primary -->

		<?php get_sidebar(); ?>
		<div style="clear:both;"></div>
	</div>
	<img class="page-bottom" src="<?php bloginfo('template_directory'); ?>/images/page-bottom.png">
	<script src="<?php bloginfo('template_directory'); ?>/js/tabs.js"></script>
<?php get_footer(); ?>
