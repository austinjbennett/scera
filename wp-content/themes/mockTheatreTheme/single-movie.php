<?php

?>
<?php get_header(); ?> 

<div class="container">
<?php while ( have_posts() ) : the_post(); ?>   
	<div class="singlePost">	
	<?php the_title('<h1>','</h1>'); ?>
	<div class="movieImgWrap">
	<?php 		
		$image = get_field('poster_image');
		$size = 'medium'; // (thumbnail, medium, large, full or custom size)
		if( $image ) {
			echo wp_get_attachment_image( $image, $size );
		}else{
			// echo get_the_title($image);
			echo '<p class="altText">Poster Image for '.get_the_title($image).'</p>';
		}
	?>
	</div>
	<?php the_field('movie_description'); ?>
	<?php
		$prices=get_field('ticket_prices');
		if($prices){
			echo '<p class="showTimesTitle">'.get_field('ticket_prices_title').'</p>';
			if(have_rows('ticket_prices')){
				while(have_rows('ticket_prices')):the_row();
					echo '$';
					the_sub_field('amount_movies');					
					echo ' - ';
					the_sub_field('price_types_movies');
					echo '<br>';

				endwhile;
			}
		}
	?>
	<?php
		$showtimes=get_field('movie_times');
		if($showtimes){
			echo '<p class="showTimesTitle">'.get_field('movie_times_title').'</p>';
			if(have_rows('movie_times')){
				while(have_rows('movie_times')):the_row();
					echo '<p class="subHead">';
					the_sub_field('movie_days');
					echo '</p>';
					the_sub_field('show_times');
					echo '<br>';					
					
				endwhile;
			}
		}else{
			echo '<p class="showTimesTitle">No Showtimes Available</p>';
		}
	?>
	</div>
<?php endwhile; ?>

<div class="keepOpen"></div>
</div>


<?php get_footer(); ?>