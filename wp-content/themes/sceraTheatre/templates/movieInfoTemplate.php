<?php
/*
Template Name: Movie Info Page
*/
get_header();
?>

<div class="movie-info-container">
<img src="<?php echo get_template_directory_uri(); ?>/img/frozen-banner.png" alt="">
    <section class="title-section-center borderless-section">
        <h2 class="title blue">Frozen 2</h2>
        <p class="text-section">PG</p>
        <p class="text-section">SCERA Shell Outdoor Theater</p>
        <p class="text-section">$3-$4 General Admissions</p>
        <p class="text-section"><i class=""></i>103 Minutes</p>

        <a href="#" class="scera-btn">Buy Tickets</a>
    </section>

    <section class="details-section bg-blue border-section">
        <div class="top-transition-blue"></div>

        <h2 class="title gold">Overview</h2>
        <p class="text-section">Anna, Elsa, Kristoff, Olaf and Sven leave Arendelle to travel to an ancient, autumn-bound forest of an enchanted land. They set out to find the origin of Elsa’s powers in order to save their kingdom.</p>

        <section class="trailer-section">
            <h3>Watch Trailer</h3>

            <iframe class="trailer-video" src="https://www.youtube.com/embed/Zi4LMpSDccc" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </section>
        
        <div class="bottom-transition-blue"></div>
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

    <section class="movies-section bg-red border-section">
        <div class="top-transition-red"></div>
        <h2 class="title gold">Upcoming Movies</h2>

        <section class="events-container">
            <div class="events-info">
                <img src="<?php echo get_template_directory_uri(); ?>/img/jumanji.png" alt="">
                <div class="events-outlook">
                    <div class="events-text">
                        <h3>Jumanji Next Level</h3>
                        <p class="text-section">July 8</p>
                    </div>

                    <div class="ticket-section">
						<img class="golden-ticket" src="<?php echo get_template_directory_uri(); ?>/img/decoratives/ticket.svg" alt="">
					</div>
                </div>
            </div>

            <div class="events-info">
                <img src="<?php echo get_template_directory_uri(); ?>/img/greatest-showman.png" alt="">
                <div class="events-outlook">
                    <div class="events-text">
                        <h3>The Greatest Showman</h3>
                        <p class="text-section">July 15</p>
                    </div>

                    <div class="ticket-section">
						<img class="golden-ticket" src="<?php echo get_template_directory_uri(); ?>/img/decoratives/ticket.svg" alt="">
					</div>
                </div>
            </div>

            <div class="events-info">
                <img src="<?php echo get_template_directory_uri(); ?>/img/frozen-2.png" alt="">
                <div class="events-outlook">
                    <div class="events-text">
                        <h3>Frozen 2</h3>
                        <p class="text-section">June 10, Outdoor</p>
                    </div>

                    <div class="ticket-section">
						<img class="golden-ticket" src="<?php echo get_template_directory_uri(); ?>/img/decoratives/ticket.svg" alt="">
					</div>
                </div>
            </div>

            <div class="events-info">
                <img src="<?php echo get_template_directory_uri(); ?>/img/live-aladdin.png" alt="">
                <div class="events-outlook">
                    <div class="events-text">
                        <h3>Live Action Aladdin</h3>
                        <p class="text-section">June 17</p>
                    </div>

                    <div class="ticket-section">
						<img class="golden-ticket" src="<?php echo get_template_directory_uri(); ?>/img/decoratives/ticket.svg" alt="">
					</div>
                </div>
            </div>
        </section>

        <div class="center">
            <a href="#" class="scera-btn">More</a>
        </div>

        <div class="bottom-transition-red"></div>
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