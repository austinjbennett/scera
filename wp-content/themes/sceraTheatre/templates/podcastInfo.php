<?php
/*
Template Name: Podcast Info Page
*/
get_header();
?>

<div class="podcast-info-page-container">
    <section class="podcast-section">

        <section class="podcast-outlook">
            <img src="<?php echo get_template_directory_uri(); ?>/img/best-christmas.png"" alt="">
            <h2>Christmas Pageant</h2>
            <p><span>Host:</span> Rachel Gibson, Quin Swallow</p>
            <p><span>Guest:</span> Michael Carrasco, Stell Perry</p>
            <p>Dec 31, 2018</p>
        </section>

        <div>
            <audio src="" controls="controls"></audio>
        </div>

        <p>We have artists for you to meet: Michael Carrasco has talent for acting, leadership for directing, and nerves of steel for working with dozens of children! He brought his tricks to SCERA's The Best Christmas Pageant Ever The Musical. On this podcast, we talk with Michael about his love for theater, and how his stage Experiences have shaped his life.</p>

        <p>We'll also talk with Pageant's "Alice," Stella Parry, who very nearly steals the show on multiple occasions, with her hilarious snooty attitude. Snooty is not a thing for Stella in real-life, though, who couldn't have been nicer in talking with us about the show and why she loves to perform.</p>
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