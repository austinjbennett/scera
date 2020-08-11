<?php
/*
Template Name: Our Story Page
*/
get_header();
?>

<div class="our-story-container">
    <img src="<?php echo get_template_directory_uri(); ?>/img/our-story.png" alt="">
    <section class="title-section borderless-section">
        <h2 class="title blue">Our Story</h2>
        <p class="text-section">In 1933, Orem was a sprawling town of 3,000 struggling through the devastating effects of the Great Depression. Many had lost farms and homes, and spirits were low. Concerned about the morale of their fellow citizens, a group of local leaders pooled their resources and their vision to create SCERA. They envisioned SCERA as the community’s gathering place—a place for neighbors and families to come together, create friendships, have fun, and be enriched and uplifted.</p>

        <img src="<?php echo get_template_directory_uri(); ?>/img/our-story2.png" alt="">

        <p class="text-section">From those humble beginnings has come a non-profit organization dedicated to the arts, to the family, and to youth. SCERA has now evolved into Utah Valley’s arts leader. With our focus on family-friendly entertainment, we have remained dedicated to serving and unifying the community through involvement in the arts. SCERA’s educational programs and Performing arts seasons have been enjoyed by millions of Utah County citizens over our 85 years of service.</p>

        <img src="<?php echo get_template_directory_uri(); ?>/img/our-story3.png" alt="">

        <p class="text-section">SCERA was founded in a spirit of volunteerism and loyalty that remains today. Over 400 young people ages 12 and older – adults and seniors, too — serve at SCERA facilities, sharing talents, learning skills and developing a love of community service. More than 500 volunteer cast members perform in our stage productions in the true spirit of community theatre. Each year, these nearly 1,000 volunteers give more than 172,000 hours of service – we invite you to become part of SCERA volunteer family!</p>

        <p class="text-section">As SCERA grows, the dream of our founders has a reality that continues the family tradition and dedication to the arts. We have a saying at SCERA: “Every time the curtain rises, so does the quality of our lives.” We strive to lengthen the legacy of SCERA so generations to come can be proud of this unique organization that has attention statewide and nationally.</p>
    </section>

    <section class="staff-section bg-grey borderless-section">
        <h2 class="title blue">Meet Our Staff</h2>

        <section class="staff-info">
            <img src="<?php echo get_template_directory_uri(); ?>/img/adam.png" alt="">
            <h3>Adam J Robertson</h3>
            <p class="text-section">President & CEO/Marketing &</p>
            <p class="text-section">Development</p>
            <p class="text-section">(801) 225-ARTS ext. 1014</p>
            <p class="text-section">adam@scera.org</p>
        </section>
    </section>

    <section class="title-section borderless-section">
        <h2 class="title blue">Join Our Team</h2>
        <p class="text-section">SCERA was founded in a spirit of volunteerism that remains today. We invite you to become part of our family as a volunteer, intern, or employee!</p>

        <a class="scera-btn" href="#">See Employment</a>

    </section>

</div>

<?php get_footer(); ?>