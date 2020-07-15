<?php
/*
Template Name: Home Page Old
*/
get_header();
?>
<div class="container">
<!-- <h1>Welcome to SCERA</h1> -->
<?php echo do_shortcode('[smartslider3 slider=2]'); ?>

<div class="homeSection calendar">
	<!-- <h2>Calendar</h2> -->
	<!-- <div class="testSq"></div> -->
	<!-- <div class="calWrap"> -->
		<?php echo do_shortcode('[myEventCalendar]'); ?>
	<!-- </div> -->
</div>

<!-- <div class="eventsBg"> -->
<div class="homeSection events">
	<div class="content">
		<h2>UPCOMING<br>EVENTS</h2>
		<?php
			for($i=0;$i<4;$i++){
				echo '<div class="event">';
				echo '<div class="eventImg"><i class="fas fa-image fa-3x"></i></div>';
				echo '<div class="caption"><i class="fas fa-ticket-alt fa-lg"></i>';
				echo '<p class="title">Event Title</p>';
				echo '<p class="dates">Run Dates</p>';
				echo '</div></div>';
			}
		?>
		<div class="audition">
			<p><i class="fas fa-microphone"></i> Audition for an Upcoming Show</p>
		</div>
	</div>
</div>

<div class="homeSection features">
	<div class="content">
		<h2>FEATURE<br>SECTION</h2>
		<p>This section can be used to highlight classes, gift cards, merchandise, or donation requests. It even comes with a handy call to action button.</p>
		<!-- <button onclick="window.location.href= '#';">CTA</button> -->
		<button>CTA</button>
	</div>
</div>

<div class="homeSection venues">
	<div class="content">
		<h2>EXPERIENCE<br>OUR VENUES</h2>
		<div class="venue">
			<div class="venueImg"><i class="fas fa-image fa-3x"></i></div>		
			<p>SCERA Center for the Arts</p>
		</div>
		<div class="venue">
			<div class="venueImg"><i class="fas fa-image fa-3x"></i></div>		
			<p>SCERA Shell Outdoor Theatre</p>
		</div>
		<div class="venue">
			<div class="venueImg"><i class="fas fa-image fa-3x"></i></div>		
			<p>Orem Heritage Museum</p>
		</div>
	</div>
	<!-- <img class="d-block w-100 colTopImg homeImg" src="<?php echo get_template_directory_uri(); ?>/img/shell.jpg" alt="SCERA Shell Theatre">	 -->
</div>

<div class="homeSection sponsors">
	<h2>THANKS TO OUR<br>SPONSORS</h2>
	<div class="sliderWrap">
	<div class="sponsorsSlider">
		<?php
			// MAX NUM OF SPONSORS IS 12
			// $maxNum=14;
			// $sponsCount=7;		
			$maxNum=24;
			$sponsCount=12;
			$toMax=$maxNum/$sponsCount;
			for($i=0;$i<$maxNum;$i++){
				if($i==0){
					echo '<div class="sponsorImg first">1<i class="fas fa-image fa-3x"></i></div>';
				}else{
					if($i>($maxNum/2)-1){
						$j = (($i-($maxNum/2))%$sponsCount)+1;	
					}else{
						// $j = $i+1;
						$j = ($i%$sponsCount)+1;
					}
					echo '<div class="sponsorImg '.$j.' ">'.$j.'<i class="fas fa-image fa-3x"></i></div>';
				}
			}
		?>		
	</div>
	</div>
</div>

<div class='keepOpen'></div>
</div>

<?php get_footer(); ?>