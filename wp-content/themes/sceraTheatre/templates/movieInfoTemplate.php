<?php
/*
Template Name: Movie Info Page
*/
get_header();
?>

<div class="movie-info-container">
    <section class="title-section-center borderless-section">
        <h2 class="title blue">Frozen 2</h2>
        <p class="text-section">PG</p>
        <p class="text-section">SCERA Shell Outdoor Theater</p>
        <p class="text-section">$3-$4 General Admissions</p>
        <p class="text-section"><i class=""></i>103 Minutes</p>

        <a href="#" class="scera-btn">Buy Tickets</a>
    </section>

    <section class="details-section bg-blue border-section">
        <h2 class="title gold">Overview</h2>
        <p class="text-section">Anna, Elsa, Kristoff, Olaf and Sven leave Arendelle to travel to an ancient, autumn-bound forest of an enchanted land. They set out to find the origin of Elsa’s powers in order to save their kingdom.</p>

        <section class="trailer-section">
            <h3>Watch Trailer</h3>
        </section>
    </section>

    <section class="time-selection-section borderless-section">
        <h2 class="title blue">Movie Times</h2>
        <div class="time-box">
            <p class="text-section">June 10, @ Dusk</p>
            <a href="#" class="scera-btn">Buy</a>
        </div>

        <div class="time-box">
            <p class="text-section">June 10, @ Dusk</p>
            <a href="#" class="scera-btn">Buy</a>
        </div>
    </section>

    <section class="sponsors-section">
		<h2 class="title blue">Thanks to our Sponsors</h2>

		<div class="sponsor-images">
			<img src="<?php echo get_template_directory_uri(); ?>/img/orem-care.png" alt="">
			<img src="<?php echo get_template_directory_uri(); ?>/img/seal-of-transparency.png" alt="">
			<img src="<?php echo get_template_directory_uri(); ?>/img/utah-arts-council.png" alt="">
		</div>
	</section>
</div>

<?php get_footer(); ?>