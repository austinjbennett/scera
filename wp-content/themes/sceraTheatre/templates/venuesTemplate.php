<?php
/*
Template Name: Venues Page
*/
get_header();
?>

<div class="venue-page-container">

	<section class="venues-section">
		
		<div class="section-corner-decoration"></div>
		<div class="section-corner-decoration section-corner-right"></div>
		
		<h2 class="title gold">Experience Our Venues</h2>

		<div class="section-side-decoration"></div>
		<div class="section-side-decoration section-side-right"></div>
		
		<div class="venues-images">
			<img src="<?php echo get_template_directory_uri(); ?>/img/scera-center-for-the-arts-gold.png" alt="">
			<img class="image-transition" src="<?php echo get_template_directory_uri(); ?>/img/gold-hr.png" alt="">
			<img src="<?php echo get_template_directory_uri(); ?>/img/scera-shell-gold.png" alt="">
			<img class="image-transition" src="<?php echo get_template_directory_uri(); ?>/img/gold-hr.png" alt="">
			<img src="<?php echo get_template_directory_uri(); ?>/img/orem-heritage-museum-gold.png" alt="">
		</div>

		<div class="section-corner-decoration section-corner-bottom"></div>
		<div class="section-corner-decoration section-corner-bottom section-corner-right"></div>
	</section>

</div>

<?php get_footer(); ?>