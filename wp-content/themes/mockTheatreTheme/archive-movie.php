<?php get_header(); ?> 

<div class="container">
<h1>Movies</h1>

<div class="row">
<?php while ( have_posts() ) : the_post(); ?>   
	<div class="column small-4 medium-4 movieRow"> 
		<a class="templateLink" href="<?php the_permalink()  ?>">
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
			<?php the_title('<h2>','</h2>'); ?>

			<?php the_field('movie_description'); ?>
			<?php
				$showtimes=get_field('movie_times');
				if($showtimes){
					echo '<p class="showTimesTitle">'.get_field('movie_times_title').'</p>';
					if(have_rows('movie_times')){
						while(have_rows('movie_times')):the_row();
							the_sub_field('movie_days');
							the_sub_field('show_times');
						endwhile;
					}
				}else{
					echo '<p class="showTimesTitle">No Showtimes Available</p>';
				}
			?>
		</a>
	</div>
<?php endwhile; ?>
</div>

<div class="keepOpen"></div>
</div>
<?php get_footer(); ?> 