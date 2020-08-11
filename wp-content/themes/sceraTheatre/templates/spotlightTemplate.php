<?php
/*
Template Name: Spotlight Page
*/
get_header();
?>

<div class="spotlight-page-container">
    <img src="<?php echo get_template_directory_uri(); ?>/img/spotlight-banner.png" alt="">
    <section class="spotlight-section bg-red border-section">
        <div class="top-transition-red"></div>
        <h2 class="title gold">Scera Spotlight Podcast</h2>

        <section class="spotlight-container">
            <div class="spotlight-info">
                <img src="<?php echo get_template_directory_uri(); ?>/img/best-christmas.png"" alt="">
                <div class="spotlight-text">
                    <h3>Best Christmas Pageant Ever</h3>
                    <p class="text-section">Dec, 31 2020</p>
                </div>
            </div>

            <div class="spotlight-info">
                <img src="<?php echo get_template_directory_uri(); ?>/img/costume-shop.png"" alt="">
                <div class="spotlight-text">
                    <h3>SCERA Costume Shop</h3>
                    <p class="text-section">Dec, 7 2018</p>
                </div>
            </div>

            <div class="spotlight-info">
                <img src="<?php echo get_template_directory_uri(); ?>/img/shes-a-wiz.png"" alt="">
                <div class="spotlight-text">
                    <h3>She's A Wiz!</h3>
                    <p class="text-section">Oct 5, 2018</p>
                </div>
            </div>

            <div class="spotlight-info">
                <img src="<?php echo get_template_directory_uri(); ?>/img/bye-birdie.png"" alt="">
                <div class="spotlight-text">
                    <h3>Bye Bye, Bye Bye Birdie!</h3>
                    <p class="text-section">Aug 16, 2018</p>
                </div>
            </div>
        </section>

        <div class="center">
            <a href="#" class="scera-btn">More</a>
        </div>

        <div class="bottom-transition-red"></div>
    </section>

    <section class="podcast-section borderless-section">
        <img src="<?php echo get_template_directory_uri(); ?>/img/spotlight-podcast.png"" alt="">

        <h2 class="title blue">Scera Spotlight Podcast</h2>

        <div class="listen-section">
            <img src="<?php echo get_template_directory_uri(); ?>/img/google-music.png"" alt="">
            <img src="<?php echo get_template_directory_uri(); ?>/img/itunes-music.png"" alt="">
        </div>
        <p class="feed-link">Podcast Feed Link: http://sceraspotlight.libsyn.com/rss</p>
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