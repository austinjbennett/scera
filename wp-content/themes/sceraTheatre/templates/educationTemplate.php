<?php
/*
Template Name: Education Page
*/
get_header();
?>

<div class="education-page-container">

    <section class="title-section borderless-section">
        <h2 class="title blue">Education</h2>
    
        <p class="text-section">SCERA has fun ways that kids, teens, adults, senior citizens and homeschoolers can get creative, develop a new talent and get excited about art! SCERA offers affordable tuition, wonderful instructors and a quality arts education in drama, dance, art, music and more. Programs offered year-round, from week long summer camps to fall and winter semester classes during the school year.</p>

        <button class="scera-btn">Filter & Sort</button>
    </section>

    <section class="education-section bg-grey">
        <section class="image-section">
            <img src="<?php echo get_template_directory_uri(); ?>/img/art-club.png"" alt="">

            <div class="age-limit">
                <h3>Age</h3>
                <p>15+</p>
            </div>
        </section>

        <section class="class-outlook">
            <div class="outlook-text-section">
                <p class="outlook-text">April 16-May 29</p>
                <h3>Quarentine Art Club</h3>
                <p class="text-section">Art Using Household Materials</p>

                <button class="scera-btn">Details</button>
            </div>

        </section>
    </section>

    <section class="education-section bg-grey">
        <section class="image-section">
            <img src="<?php echo get_template_directory_uri(); ?>/img/ballet.png"" alt="">

            <div class="age-limit">
                <h3>Age</h3>
                <p>18+</p>
            </div>
        </section>

        <section class="class-outlook">
            <div class="outlook-text-section">
                <p class="outlook-text">April 16-May 29</p>
                <h3>Adult Ballet</h3>
                <p class="text-section">Learn Beginning Ballet</p>

                <button class="scera-btn">Details</button>
            </div>

        </section>
    </section>

    <section class="education-section bg-grey">
        <section class="image-section">
            <img src="<?php echo get_template_directory_uri(); ?>/img/hip-hop.png"" alt="">

            <div class="age-limit">
                <h3>Age</h3>
                <p>16+</p>
            </div>
        </section>

        <section class="class-outlook">
            <div class="outlook-text-section">
                <p class="outlook-text">May 6-June 24</p>
                <h3>Adult Hip Hop</h3>
                <p class="text-section">Learn Beginning Hip Hop</p>

                <button class="scera-btn">Details</button>
            </div>

        </section>
    </section>

    <section class ="center">
        <button class="scera-btn">More Classes</button>
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