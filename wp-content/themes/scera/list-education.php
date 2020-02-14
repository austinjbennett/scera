<div class="arch-event arch-education">
	<div class="arch-safelane <?php if (get_field( 'age_group' )) { echo 'age'; }?>">
		<?php if (get_field( 'age_group' )) :?>
		<img class="list-image" src="<?php echo get_stylesheet_directory_uri(); ?>/images/ages-list-bg.png" />
		<p class="list-age"><span><?php the_field('education_title')?></span><?php the_field('age_group'); ?></p>
		<?php endif; ?>
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<div class="event-date">
			<?php

			if( get_field('semester') ) {
				the_field('semester');
				echo' - ';
			}

			if(get_field('event_dates')):
				$count = 1;
				while(has_sub_field('event_dates')):
					// the_sub_field('event_start_date');
					if($count > 1 ) {echo ' | ';}
					    if (get_sub_field('event_start_date') && get_sub_field('event_end_date')){
						$date1 = DateTime::createFromFormat('Ymd', get_sub_field('event_start_date'));
						$date2 = DateTime::createFromFormat('Ymd', get_sub_field('event_end_date'));
						if($date1 != $date2):
							if ( $date1->format('Y') == $date2->format('Y') ){
								echo $date1->format('F d - ');
							} else {
								echo $date1->format('F d, Y - ');
							}
							echo $date2->format('F d, Y');
						else:
							echo $date1->format('F d, Y');
						endif;
						$count ++;
					    }
				endwhile;
			endif;
			?>
		</div>

		<?php if( get_field('poster_image', $p->ID) ):
		$attachment_id = get_field('poster_image', $p->ID);
		$size = "list-edu";
		$image = wp_get_attachment_image_src( $attachment_id, $size );
		?><img class="poster-image" src="<?php echo $image[0]; ?>" alt="" /><?php
		endif; ?>

		<?php // Long Description
		if( get_field('short_description') ): ?>
			<div class="long-description">
				<?php the_field('short_description'); ?>
			</div><?php
		endif; ?>

		<div class="link-wrapper"><a class="post-link" href="<?php the_permalink(); ?>">See Details >></a>
		<?php if(get_field('register_online')) { ?><a class="buy-link" href="<?php the_field('register_online');?>">Register Now Online >></a><?php } ?>
		</div>
	</div>

	<div class="arch-offlane">

	<?php // Instructor
	if( get_field('main_instructor') ): ?>
		<div class="instructor">
			<h4>Instructor:</h4>
			<div><?php the_field('main_instructor'); ?></div>
		</div><?php
	endif; ?>

	<?php // Times
	if(get_field('times')): ?>
		<div class="times">
			<h4>Times:</h4><?php
			while(has_sub_field('times'))
			{
				echo'<div>';
				the_sub_field('time');
				echo'</div>';
			} ?>
		</div>
	<?php endif; ?>
	<?php // Location
	if( get_field('location') ): ?>
		<div class="location">
			<h4>Location:</h4>
			<div><?php the_field('location'); ?>
			<a href="<?php the_field('directions'); ?>">View Map Location >></a>
			</div>
		</div><?php
	endif; ?>
	</div>
</div>
