<div class="arch-event">
	<div class="arch-safelane">
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>

		<div class="event-date">
			<div class="movie-dates"><?php the_field('movie_dates'); ?></div>

		</div>

			<?php // Short Description
			if( get_field('movie_description') ):?>
				<div class="short-description">
					<?php the_field('movie_description'); ?>
				</div><?php
			endif; ?>
			<a class="post-link" href="<?php the_permalink(); ?>">See Details >></a>
			<div class="link-wrapper"><a class="buy-link" href="<?php if(get_field('buy_online_link')) the_field('buy_online_link');?>">Buy Tickets >></a></div>
	</div>

	<div class="arch-offlane">
		<?php if( get_field('poster_image') ):
			$attachment_id = get_field('poster_image');
			$size = 'thumbnail';
			$image = wp_get_attachment_image_src( $attachment_id, $size );
			?><div class="piw"><a href="<?php the_permalink(); ?>"><img class="poster-image" src="<?php echo $image[0]; ?>" /></a></div><?php
		endif; ?>

	<?php if(get_field('movie_times', $p->ID)): ?>
		<div class="times">
			<h4><?php the_field('movie_times_title', $p->ID); ?></h4><?php
			while(has_sub_field('movie_times', $p->ID)) : ?>
				<div class="date-wrap">
				<h5><?php the_sub_field('movie_days', $p->ID); ?></h5>
				<?php the_sub_field('show_times', $p->ID); ?>
				</div>
			<?php endwhile; ?>
		</div>
	<?php endif; ?>
	</div>
</div>
