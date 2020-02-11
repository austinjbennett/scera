<?php get_header(); ?>

<!-- THE LOOP -->
<?php while ( have_posts() ) : the_post(); ?>   
	<div class="container"> 
	<!-- <div class="row"> -->
		<!-- <div class="column"> -->
			<?php echo '<div class="aboveTitle"></div>'; ?>
			<?php the_title('<h1>','</h1>'); ?>
			<?php the_content('<div class="container">','</div>'); ?>
	<!-- </div> -->
	<!-- </div> -->
	</div>
<?php endwhile; ?>

<?php get_footer(); ?>