<?php
/*
Template Name: Season Tickets Page
*/
get_header();
?>

<div class="season-tickets-container">
    <section class="title-section borderless-section">
        <h2 class="title blue">Summer Season Tickets</h2>

        <section class="season-tickets-section">
            <h3 class="padding">How to Buy Season Tickets</h3>
            <section class="info">
                <p class="text-section">Box Office Hours:</p>
                <p class="text-section">Weekdays 10am-6pm</p>
                <p class="text-section">Saturdays Noon-6pm</p>
            </section>

            <section class="info">
                <p class="text-section">By Phone:</p>
                <p class="text-section">(801) 225-ARTS (2787)</p>
            </section>

            <section class="info">
                <p class="text-section">In Person:</p>
                <p class="text-section">Main Office at SCERA Center for the Arts</p>
                <p class="text-section">745 South State, Orem Utah 84058</p>
            </section>
        
            <h3 class="padding-none">Additional Information</h3>
            <p class="text-section padding-none">Season tickets include 3 musicals and 6 concerts and are 47& off the single ticket price. BONUS! Season ticket holders recieve the outdoor Movie Series absolutely FREE!</p>
            <p class="text-section">Season tickets are available through June 20 only or until seating is sold out. After June 20, you may purchase individual event tickets only.</p>

            <section class="map-section">
                <h3>Seating Map</h3>
                <img src="<?php echo get_template_directory_uri(); ?>/img/outdoor-map.png" alt="">
            </section>

            <h3>General Admission Season Ticket</h3>
            <section class="info">
                <p class="text-section">Adult: $105</p>
                <p class="text-section">Chile (age 3-11): $95</p>
                <p class="text-section">Senior (age 65+): $95</p>
            </section>
            <p class="text-section padding-none">There are specific sections for chairs and lankets. Patrons may rent a chair at the door that is specially cut to sit on the slopped hill for $1.00 (limited quantities available). If you want to be guaranteed a chair at each of the three musicals and all seven concerts, we are offering a Rental Chair Punch Card for $20 per person. Only 100 of these cards are available! One chair per event. Chairs must be used in the general admission chair section.</p>

            <h3>Reserved Section B Season Ticket</h3>
            <section class="info">
                <p class="text-section">Adult: $135</p>
                <p class="text-section">Chile (age 3-11): $125</p>
                <p class="text-section">Senior (age 65+): $125</p>
            </section>
            <p class="text-section padding-none">Section B is a reserved roped-off area of chairs. You are guaranteed a seat within the roped off area, but seats are not individually reserved. They will be held until 8:30pm and then be released.</p>

            <h3>Reserved Section A Season Ticket</h3>
            <section class="info">
                <p class="text-section">Adult: $165</p>
                <p class="text-section">Chile (age 3-11): $155</p>
                <p class="text-section">Senior (age 65+): $155</p>
            </section>
            <p class="text-section padding-none">Section A is a reserved roped-off area of chairs. You are guaranteed a seat within the roped off area, but seats are not individually reserved. They will be held until 8:30pm and then be released.</p>
        </section>
    </section>
</div>

<?php get_footer(); ?>