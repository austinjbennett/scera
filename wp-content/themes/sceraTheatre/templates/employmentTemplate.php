<?php
/*
Template Name: Employment Page
*/
get_header();
?>

<div class="employment-container">

    <section class="hiring-section borderless-section">
        <h2 class="title blue">Now Hiring</h2>

        <section class="job-outlook">
            <div class="outlook-image">
                <img src="<?php echo get_template_directory_uri(); ?>/img/projectionist-manager.png" alt="">
            </div>
            <div class="outlook-text-section">
                <h3>Projectionist/Manager</h3>
                <p class="outlook-text">Part Time/Year Round</p>
                <p class="outlook-text">Day and Evening Shifts</p>
                <p class="outlook-text">SCERA Center For The Arts</p>
                <p class="outlook-text">June 1st, 2020</p>
            </div>
        </section>
        
        <section class="info-section">
            <h3>Job Description</h3>
            <p class="text-section">Shift manager and Movie Projectionist for the SCERA theater in Orem. Part time with day and evening shifts.</p>
        </section>

        <section class="info-section">
            <h3>Responsibilities</h3>
            <p class="text-section">Duties vary depending on the shift and include: General office/front of house jobs including answering phones, selling tickets, reports, helping customers, etc. Managing concessions, supervising youth and adult volunteers, and running films.</p>
        </section>

        <section class="qualifications-section">
            <h3>Qualifications</h3>
            <ul>
                <li class="text-section">Must be 21 or older</li>
                <li class="text-section">Proficient in MS Office and Computers</li>
                <li class="text-section">Self-Motivated</li>
                <li class="text-section">Professional and Friendly</li>
                <li class="text-section">Detail Oriented</li>
            </ul>
        </section>

        <section class="info-section">
            <h3>Compensation</h3>
            <p class="text-section">This is a minimum wage paying job at $7.50 an hour</p>
        </section>

        <section class="info-section">
            <p class="text-section bold">To apply send resume or inquiries to linda@scera.org</p>
        </section>

        <section class="job-outlook">
            <div class="outlook-image">
                <img src="<?php echo get_template_directory_uri(); ?>/img/hill-supervisor.png" alt="">
            </div>
            <div class="outlook-text-section">
                <h3>Hill Supervisor</h3>
                <p class="outlook-text">Part Time</p>
                <p class="outlook-text">Evening Shifts</p>
                <p class="outlook-text">SCERA Outdoor Theater</p>
                <p class="outlook-text">June 1st, 2020</p>
            </div>
        </section>

    </section>

    <section class="volunteer-section bg-grey borderless-section">
        <h2 class="title blue">Volunteer & Internship</h2>

        <p class="text-section">Click here to Apply to be an Intern or Volunteer at the SCERA Theater in Orem Utah. Get experience in the arts. High school and college students welcome to apply!</p>

        <button class="scera-btn">Get Involved</button>
    </section>
</div>

<?php get_footer(); ?>