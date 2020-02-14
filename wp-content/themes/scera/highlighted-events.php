<?php if( get_field('poster_image', $p->ID) ):
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
		<?php
		if(get_field('event_dates', $p->ID)):
			$count = 1;
			while(has_sub_field('event_dates', $p->ID)):
				// the_sub_field('event_start_date');
				if($count > 1 ) {echo ' | ';}
					$date1 = DateTime::createFromFormat('Ymd', get_sub_field('event_start_date', $p->ID));
					$date2 = DateTime::createFromFormat('Ymd', get_sub_field('event_end_date', $p->ID));
					if(get_sub_field('event_end_date', $p->ID)):
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

	<?php // Short Description
	if( get_field('short_description', $p->ID) ):?>
		<div class="short-description">
			<?php the_field('short_description', $p->ID); ?>
		</div><?php
	endif; ?>
</div>

<div class="further-info">

	<?php // Times
	if(get_field('times', $p->ID)): ?>
		<div class="times">
			<h4>Times:</h4>
			<div><?php
			while(has_sub_field('times', $p->ID))
			{
				the_sub_field('time', $p->ID);
			} ?>
			</div>
		</div>
	<?php endif; ?>

        <?php $buy_link = get_field('buy_online_link', $p->ID); ?>
        <?php if (!empty($buy_link)): ?>
                <a class="button" style="background-color:#8d0207; color:#fff;" href="<?php echo $buy_link; ?>">BUY TICKETS</a>
        <?php endif; ?>

	<a class="post-link" href="<?php echo get_permalink( $p->ID ); ?>">See Details >></a>

</div>
