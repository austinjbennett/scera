<div class="title-info <?php if (get_field( 'age_group' )) { echo 'age'; }?>">
	<?php if (get_field( 'age_group' )) :?>
	<img src="<?php echo get_stylesheet_directory_uri(); ?>/images/ages-bg.png" />
	<p>+<?php the_field('age_group'); ?><span>AGES</span></p>
	<?php endif; ?>
	<h1><?php the_title(); ?></h1>
</div>

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
				// the_sub_field('event_start_date');
				if($count > 1 ) {echo ' | ';}
					$date1 = DateTime::createFromFormat('Ymd', get_sub_field('event_start_date'));
					$date2 = DateTime::createFromFormat('Ymd', get_sub_field('event_end_date'));
					if(get_sub_field('event_end_date')):
					echo $date1->format('F d - ');
					echo $date2->format('F d, Y');
					else:
					echo $date1->format('F d, Y');
					endif;
					$count ++;
			endwhile;
		endif;
		?>
	</div>

	<?php if( get_field('poster_image', $p->ID) ):
	$attachment_id = get_field('poster_image', $p->ID);
	$size = "list-edu";
	$image = wp_get_attachment_image_src( $attachment_id, $size );
	?><img class="poster-image education-image" src="<?php echo $image[0]; ?>" alt="" /><?php
	endif; ?>

	<?php // Long Description
	if( get_field('long_description') ): ?>
		<div class="long-description">
			<?php the_field('long_description'); ?>
	<?php if(get_field('register_online')){ ?><a class="button" style="background-color:#8d0207; color:#fff;" href="<?php the_field('register_online'); ?>">Register</a><?php }?>
	<a class="button post-link" style="background-color: #173880; color: #fff;" href="<?php echo get_permalink( $p->ID ); ?>">Details</a>
		</div><?php
	endif; ?>

</div>

<div class="further-info">

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
