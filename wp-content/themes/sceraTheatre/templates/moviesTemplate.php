<?php
/*
Template Name: Movies Page
*/
get_header();
?>

<div class="movies-page-container">
    <img src="<?php echo get_template_directory_uri(); ?>/img/movies-banner.png" alt="">
    <section class="movies-section bg-red border-section">
        <div class="top-transition-red"></div>
        
        <h2 class="title gold">Movies</h2>

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

            <div class="events-info">
                <img src="<?php echo get_template_directory_uri(); ?>/img/toy-story-4.png" alt="">
                <div class="events-outlook">
                    <div class="events-text">
                        <h3>Toy Story 4</h3>
                        <p class="text-section">Aug 5</p>
                    </div>

                    <div class="ticket-section">
						<img class="golden-ticket" src="<?php echo get_template_directory_uri(); ?>/img/decoratives/ticket.svg" alt="">
					</div>
                </div>
            </div>

            <div class="events-info">
                <img src="<?php echo get_template_directory_uri(); ?>/img/end-game.png" alt="">
                <div class="events-outlook">
                    <div class="events-text">
                        <h3>Avengers End Game</h3>
                        <p class="text-section">Aug 12</p>
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