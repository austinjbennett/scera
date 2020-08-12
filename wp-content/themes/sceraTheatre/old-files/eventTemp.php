<?php
/*
Template Name: Event
*/
?>

<?php

$eventId = $_GET[id];

require_once(ABSPATH . 'wp-config.php');
$dbConnection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
mysqli_select_db($dbConnection, DB_NAME);

$query = "SELECT * FROM movies WHERE id=$eventId";
$result = mysqli_query($dbConnection, $query) or die('query failed');
$found = mysqli_fetch_array($result);
?>

<?php get_header(); ?>

<!-- THE LOOP -->
<?php while ( have_posts() ) : the_post(); ?>   
	<div class="container eventPage">
	<?php echo '<div class="aboveTitle"></div>'; ?>
	<?php the_title('<h1>','</h1>'); ?>
	<?php
		echo '<h1>'.$found['name'].'</h1>';
		echo '<div class="row">';
		echo '<div class="col-6">';
		echo '<h2>Description</h2>';
		echo '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>';
		echo '<h2>Times</h2>';
		echo '<p>Monday-Friday: 10:00am, 11:30am, 2:30pm, 4:30pm, 5:30pm, 7:00pm</p>';
		echo '<button>Tickets</button>';
		echo '</div>';
		echo '<div class="col-6">';
		echo '<img src="'.$found['photo'].'">';
		echo '</div>';
		echo '</div>';
		
	?>
	<?php the_content('<div class="content">','</div>'); ?>
	</div>
<?php endwhile; ?>

<?php get_footer(); ?>

