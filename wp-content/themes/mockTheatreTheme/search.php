<!-- SEARCH.PHP -->

<?php get_header(); ?>
	<div class="container">
<!-- <div class="row"> -->
	<!-- <div class="column small-12"> -->
		<h1>Search Results</h1>
		<!-- THE LOOP -->
		<?php while ( have_posts() ) : the_post(); ?>    
			<?php the_title('<h2>','</h2>'); ?>
			<?php the_excerpt(); ?>
		<?php endwhile; ?>
	<!-- </div> end column -->
<!-- </div> end row -->
	</div>

<?php get_footer(); ?> 