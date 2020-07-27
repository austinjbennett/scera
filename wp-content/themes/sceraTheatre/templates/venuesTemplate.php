<?php
/*
Template Name: Venues Page
*/
get_header();
?>

<div class="venue-page-container">

<section class="venues-section">
		
		<div class="top-left-border"></div>
		<div class="top-right-border"></div>
		
		<h2 class="title gold">Experience Our Venues</h2>

		<div class="side-left-border"></div>
		<div class="side-right-border"></div>
		
		<div class="venues-images">
			<img src="<?php echo get_template_directory_uri(); ?>/img/scera-center-for-the-arts-gold.png" alt="">
			<img class="image-transition" src="<?php echo get_template_directory_uri(); ?>/img/gold-hr.png" alt="">
			<img src="<?php echo get_template_directory_uri(); ?>/img/scera-shell-gold.png" alt="">
			<img class="image-transition" src="<?php echo get_template_directory_uri(); ?>/img/gold-hr.png" alt="">
			<img src="<?php echo get_template_directory_uri(); ?>/img/orem-heritage-museum-gold.png" alt="">
		</div>

		<div class="bottom-left-border"></div>
		<div class="bottom-right-border"></div>
	</section>

</div>

<?php get_footer(); ?>