<?php
/*
Template Name: Home Page
*/
get_header();
?>
<div class="home-container">

    <?php
        // WP_Query arguments
        $args = array (
            'post_type' => array( 'hero-slide' ),
        );

        // The Query
        $query_slider = new WP_Query( $args );

        // The Loop
        if ( $query_slider->have_posts() ) {
        	?>
            <div class="swiper-container">
                <div class="swiper-wrapper">
	            <?php while ( $query_slider->have_posts() ) {
	                $query_slider->the_post();?>
	                <div class="swiper-slide" style="background-image: url('<?php the_field( 'slide_image' );?>')">
		                <p class="slide-title"><?php the_title(); ?></p>
						<p class="slide-subtext"><?php the_field( 'slide_subtext' ); ?></p>
                        <?php if ( get_field( 'slide_button' ) == 1 ) : ?>
                            <?php $slide_button_link = get_field( 'slide_button_link' ); ?>
			                <a href="<?php echo esc_url( $slide_button_link['url'] ); ?>" target="<?php echo esc_attr( $slide_button_link['target'] ); ?>" class="slider-button scera-btn">
				                <?php the_field( 'slide_button_text' );?>
			                </a>
                        <?php endif; ?>
		                <div class="bottom-gradient"></div>
	                </div>
	            <?php
	            }
	            ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        <?php
        } else {
            // no posts found
        }

        // Restore original Post Data
        wp_reset_postdata();
		?>

	<?php
    $currentDate = date('Y/m/d');
    $lastWeek = date('Y/m/d', strtotime("-7 days"))

    // WP_Query arguments
	$args = array (
		'post_type' => 'any',
		'start_date' => '',
	);

	// The Query
	$query_slider = new WP_Query( $args );

	// The Loop
	if ( $query_slider->have_posts() ) {
	?>
	<section class="event-carousel-container">
		<div class="event-carousel-dates">
			<div class="swiper-wrapper">
				<div class="swiper-slide">
					<p class="weekday">Monday</p>
					<p class="date">27</p>
					<p class="month">April</p>
				</div>
				<div class="swiper-slide">
					<p class="weekday">Tuesday</p>
					<p class="date">28</p>
					<p class="month">April</p>
				</div>
				<div class="swiper-slide">
					<p class="weekday">Wednesday</p>
					<p class="date">29</p>
					<p class="month">April</p>
				</div>
				<div class="swiper-slide">
					<p class="weekday">Thursday</p>
					<p class="date">30</p>
					<p class="month">April</p>
				</div>
				<div class="swiper-slide">
					<p class="weekday">Friday</p>
					<p class="date">1</p>
					<p class="month">May</p>
				</div>
			</div>
		</div>
		<div class="event-carousel">
			<div class="swiper-wrapper">
				<div class="swiper-slide">
					<p><i class="fas fa-film"></i>Frozen 2</p>
					<p><i class="fas fa-theater-masks"></i>The Scarlet Pimpernel</p>
					<p><i class="fas fa-music"></i>Saltaire's Barbershop Chorus</p>
					<p><i class="fas fa-star"></i>A Night of Broadway</p>
					<p><i class="fas fa-graduation-cap"></i>Adult Ballet</p>
				</div>
				<div class="swiper-slide">
					<p><i class="fas fa-film"></i>Frozen 2</p>
					<p><i class="fas fa-theater-masks"></i>The Scarlet Pimpernel</p>
					<p><i class="fas fa-music"></i>Saltaire's Barbershop Chorus</p>
					<p><i class="fas fa-star"></i>A Night of Broadway</p>
					<p><i class="fas fa-graduation-cap"></i>Adult Ballet</p>
				</div>
				<div class="swiper-slide">
					<p><i class="fas fa-film"></i>Frozen 2</p>
					<p><i class="fas fa-theater-masks"></i>The Scarlet Pimpernel</p>
					<p><i class="fas fa-music"></i>Saltaire's Barbershop Chorus</p>
					<p><i class="fas fa-star"></i>A Night of Broadway</p>
					<p><i class="fas fa-graduation-cap"></i>Adult Ballet</p>
				</div>
				<div class="swiper-slide">
					<p><i class="fas fa-film"></i>Frozen 2</p>
					<p><i class="fas fa-theater-masks"></i>The Scarlet Pimpernel</p>
					<p><i class="fas fa-music"></i>Saltaire's Barbershop Chorus</p>
					<p><i class="fas fa-star"></i>A Night of Broadway</p>
					<p><i class="fas fa-graduation-cap"></i>Adult Ballet</p>
				</div>
				<div class="swiper-slide">
					<p><i class="fas fa-film"></i>Frozen 2</p>
					<p><i class="fas fa-theater-masks"></i>The Scarlet Pimpernel</p>
					<p><i class="fas fa-music"></i>Saltaire's Barbershop Chorus</p>
					<p><i class="fas fa-star"></i>A Night of Broadway</p>
					<p><i class="fas fa-graduation-cap"></i>Adult Ballet</p>
				</div>
			</div>
		</div>
	</section>

        <?php
    } else {
        // no posts found
    }

    // Restore original Post Data
    wp_reset_postdata();
    ?>


</div>

<?php get_footer(); ?>