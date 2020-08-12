<?php get_header(); ?> 

<div class="content">

<h1>Events</h1>

<?php while ( have_posts() ) : the_post(); ?>   
	<div class="container eventRow"> 
	<?php echo '<div class="aboveTitle"></div>'; ?>
	<?php echo '<div class="row justify-content-between"><div class="column small-12 medium-6">'; ?>
	<?php the_title('<h2>','</h2>'); ?>
	<?php the_field('long_description'); ?>
	<?php
		$showtimes=get_field('event_dates');
		if($showtimes){
			echo '<p class="showTimesTitle"> Event Dates</p>';
			if(have_rows('event_dates')){
				while(have_rows('event_dates')):the_row();
					the_sub_field('event_start_date');
					echo ' - ';					
					the_sub_field('event_end_date');
				endwhile;
			}
		}else{
			echo 'No Dates Available';
		}
	?>
	<? echo '</div>'; ?>

	<?php echo '<div class="column small-12 medium-6">'; ?>	
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
	<?php echo '</div></div>'; ?>

		
	</div>
<?php endwhile; ?>
</div>

<?php echo '<script type="text/javascript">
	var evDiv=document.querySelectorAll(".eventRow");
	console.log(evDiv);
	evDiv.forEach(ev=>{
		ev.addEventListener("click",function(){
			var evTxt = this.querySelector("h2").innerText;
			console.log(evTxt);
			evTxt = evTxt.replace(/\s+/g, "-").toLowerCase();
			window.location="/event/"+evTxt;
		});
	});
	</script>
' ?>

<?php get_footer(); ?> 