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
        if ( $query_slider->have_posts() ) { ?>
            <div class="swiper-container">
                <div class="swiper-wrapper">
	            <?php while ( $query_slider->have_posts() ) {
	                $query_slider->the_post(); ?>
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
	            } ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        <?php
        } else {
            // no posts found
        }

        // Restore original Post Data
        wp_reset_postdata(); ?>

	<?php
    $currentDate = date('Y/m/d');
    $lastWeek = date('Y/m/d', strtotime("-7 days"));
    $twoWeeks = date('Y/m/d', strtotime('+2 weeks'));

    // WP_Query arguments
	$args = array (
		'post_type' => 'any',
		'start_date' => $lastWeek,
		'end_date' => $twoWeeks,
	);

	// The Query
	$query_eventsCalendar = new WP_Query( $args );

	// The Loop
	if ( $query_eventsCalendar->have_posts() ) {
	?>
	<section class="event-carousel-container">
		<div class="event-carousel-dates">
			<div class="swiper-wrapper">
		        <?php
		        $day = $lastWeek;
		        while ($day != $twoWeeks) {
		            $formattedDay = date( "Y/F/d/D", strtotime($day));
		            $dateArray = explode('/', $formattedDay)?>
					<div class="swiper-slide">
						<p class="weekday"><?php echo $dateArray[3]; ?></p>
						<p class="date"><?php echo $dateArray[2]; ?></p>
						<p class="month"><?php echo $dateArray[1]; ?></p>
					</div>
                <?php
                    $day = date("Y/m/d", strtotime("+1 day", strtotime($day)));
                } // End While ?>
			</div>
		</div>
		<div class="event-carousel">
			<div class="swiper-wrapper">
                <?php
                $day = $lastWeek;
                while ($day != $twoWeeks) {?>
	                <div class="swiper-slide">
		                <p><i class="fas fa-film"></i>Frozen 2</p>
		                <p><i class="fas fa-theater-masks"></i>The Scarlet Pimpernel</p>
		                <p><i class="fas fa-music"></i>Saltaire's Barbershop Chorus</p>
		                <p><i class="fas fa-star"></i>A Night of Broadway</p>
		                <p><i class="fas fa-graduation-cap"></i>Adult Ballet</p>
	                </div>
                <?php
                    $day = date("Y/m/d", strtotime("+1 day", strtotime($day)));
                } // End While ?>
			</div>
		</div>
	</section>

	<section class="venues-container">
		
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

	<section class="rentals-container">
		<h2 class="title blue">Rentals</h2>
			<div class="rentals-section-container">
				<div class="rentals-props">
					<img src="<?php echo get_template_directory_uri(); ?>/img/costumes.png" alt="">
					<p>Props/Costumes</p>
					<button class="scera-btn">Rent</button>
				</div>
				<div class="rentals-facilities">
					<img src="<?php echo get_template_directory_uri(); ?>/img/facilities.png" alt="">
					<p>Facilities</p>
					<button class="scera-btn">Rent</button>
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