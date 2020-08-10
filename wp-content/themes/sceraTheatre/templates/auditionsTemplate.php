<?php
/*
Template Name: Auditions Page
*/
get_header();
?>

<div class="auditions-container">
    <section class="title-section-center borderless-section">
        <h2 class="title blue">Auditions: Mama Mia!</h2>
        <p class="text-section">March 12 & 17</p>
        <p class="text-section">7-9PM</p>
        <p class="text-section">Scera Center for the Arts</p>
        <a class="show-map">Show Map</a>
        <p class="text-section">Audition Room 1</p>
        <p class="text-section">Ages 16+</p>

        <a href="#" class="scera-btn">Share Audition</a>
    </section>

    <section class="details-section bg-blue border-section">
        <div class="top-transition"></div>

        <h2 class="title gold">Details</h2>
        <p class="text-section">The opening show of Summer 2020 at SCERA Shell Outdoor Theatre will be “Mamma Mia!,” directed and choreographed by Shawn M. errera, with music direction by Brandalee Bluth Streeter. It plays on stage June 5-20, 2020 @ 8:00pm on Mondays, Tuesdays, Thursdays, Fridays and Saturdays.</p>
        <p class="text-section">Participants should prepare 16 bars of a song in the style of the show. An accompanist or iPod/MP3 hookup is available please bring your music and have it cued or marked. Please, no CD’s. Callbacks, for those invited, will be held Saturday, March 21, and will Include a dance section.</p>
        <p class="text-section">Auditions are done by appointment, and you can register online below. If this is your first time using Casting Manager, you will be asked to create an account, and upload a resume and headshot if you have them. If you don’t have a resume or Headshot, there is space where you can add in your education, training, experience, shows, etc. Casting manager also allows you to submit a video audition if you cannot attend auditions.</p>
        <p class="text-section">Rehearsals begin the week of March 23, 2020 and will generally be held Monday-Friday evenings and Saturday mornings. A more detailed schedule will be provided once casting is final.</p>

        <div class="center">
            <a href="#" class="scera-btn">Casting Manager</a>
        </div>

        <div class="bottom-transition"></div>
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