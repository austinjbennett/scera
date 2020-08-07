<?php
/*
Template Name: Event Info Page
*/
get_header();
?>

<div class="event-info-container">
    <section class="title-section-center borderless-section">
        <h2 class="title blue">Roald Dahl's Willy Wonka</h2>

        <p class="text-section">July 03 - July 18, 2020</p>
        <p class="text-section">Scera Shell Outdoor Theater</p>
        <p class="text-section">Monday-Saturday 8 pm</p>
        <p class="text-section">$10-$15</p>
        <p class="text-section"><i class="fas fa-theater-masks"></i>Play/Musical</p>

        <button class="scera-btn">Share Event</button>
    </section>

    <section class="details-section bg-blue border-section">
        <h2 class="title gold">Overview</h2>

        <p class="text-section">Imagination abounds in Roald Dahl’s tale of the mysterious candy man in this stage adaptation of “Charlie and The Chocolate Factory.” Willy Wonka and his band of Oompa Loompas lead Charlie and other children through life lessons through the candy factory.</p>

        <section class="info">
            <h3>Directed By:</h3>
            <p class="text-section">Julie Bonifay</p>
        </section>

        <section class="info">
            <h3>Music Direction By:</h3>
            <p class="text-section">Dana Cardon</p>
        </section>

        <section class="info">
            <h3>Choreography By:</h3>
            <p class="text-section">Dani LeCompte</p>
        </section>

        <section class="info">
            <h3>Rainout Policy</h3>
            <p class="text-section">Events will not be a “rainout” before showtime. Shows continue regardless of any type of weather, cold, rain or snow. Come prepared, or plan to attend on another evening! If the show is declared a rainout by SCERA management prior to intermission, rainchecks will be given for one of the remaining nights.</p>
        </section>
    </section>

    <section class="time-selection-section borderless-section">
        <h2 class="title blue">Event Times</h2>
        <div class="time-box">
            <p class="text-section">July 3, 8:00 PM</p>
            <a href="#" class="scera-btn">Buy</a>
        </div>

        <div class="time-box">
            <p class="text-section">July 4, 8:00 PM</p>
            <a href="#" class="scera-btn">Buy</a>
        </div>

        <div class="time-box">
            <p class="text-section">July 5, 8:00 PM</p>
            <a href="#" class="scera-btn">Buy</a>
        </div>

        <div class="time-box">
            <p class="text-section">July 6, 8:00 PM</p>
            <a href="#" class="scera-btn">Buy</a>
        </div>

        <div class="time-box">
            <p class="text-section">July 7, 8:00 PM</p>
            <a href="#" class="scera-btn">Buy</a>
        </div>
    </section>

    <section>
        <h2 class="title blue">Gallery</h2>
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