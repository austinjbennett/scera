<?php

if ( ! defined( 'ABSPATH' ) ) {
  require_once "../../../../../wp-load.php";
  require_once '../../inc/wp_authenticator/auth.php';
} else{
  require_once OW_PLUGIN_PATH.'inc/wp_authenticator/auth.php';
}
wp_enqueue_style('ow-tickets', OW_PLUGIN_URL.'pages/tickets/tickets.css');

?>

<h1 class='ow-title'>Recent Tickets</h1>

<div id="wrap">
<div id="wrap-inner">
<?php

$current_user = wp_get_current_user();
$resp = wp_remote_post(
	'https://wpoverwatch.teamwork.com/desk/v1/tickets/search.json',
	array(
		'headers' => array(
			'Authorization' => 'Basic Z2U4T0hPZGVZSXVyTXMwT0JjZDZmOXF5Q2VrRUhYTEVQeG5tR3VqWlpZNDh2V1FxMkc6'
		),
		'httpversion' => '1.1',
		'body' => array(
			 'search' => $current_user->user_firstname,
			 'sortBy' => 'customerName'
		)
	)
);

if ( is_wp_error( $resp ) ) {
   $error_message = $resp->get_error_message();
   /* Remove branding from error */
   if (ow_are_we_whitelabeled()){
     $error_message = str_replace(': wpoverwatch.teamwork.com', ' ', $error_message);
     $error_message = str_replace('wpoverwatch.teamwork.com', '', $error_message);
     $error_message = str_replace('wpoverwatch', '', $error_message);
   }
   echo "<h1>Yikes! This wasn't suppose to happen</h1> $error_message";
	 return;
} else {
   $json = json_decode( $resp['body'], JSON_PRETTY_PRINT );
}
if (! $json){
	echo '<h1>Whoops, we seem to be having difficulty retreiving your tickets</h1>';
	return;
}

if ( !isset($json['tickets']) || empty($json['tickets']) ){
  ?>
    <h1>Put us to Work</h1>
    <div class='no-tickets-text' style='margin: 2em 1.5em'>
        <p>Our system failed to find any tickets submitted by you,</p>
        <p>but if you simply click on "Submit Ticket" from the left-hand sidebar,</p>
        <p>We'll be happy to jump right into resolving any problems you're having.<p>
    </div>
    <style>
        .ow-title {display: none;}
        .no-tickets-text p {line-height: .82em;}
        body.wpoverwatch-my-tickets #wrap-inner {padding: 2em !important; padding-top: .75em !important;}
        @media (max-width: 1200px){ body.wpoverwatch-my-tickets h1 {margin-left: 17px !important;} }
    </style>
  <?php
  return;
}
$tickets = $json['tickets'];

$page_prefix = ow_branding('url friendly');

usort( $tickets, function($a, $b){
	return ( -($a['updatedAt'] <=> $b['updatedAt']) );
} );
echo '<section class=tickets>';
foreach($tickets as $ticket){
	if ($ticket['customer']['email'] !== $current_user->user_email)
		continue;

	$id = $ticket['id'];
	$preview = $ticket['preview'];
	$status = $ticket['status'];
	$subject = $ticket['subject'];
	$updated_on = $ticket['updatedAt'];
	$recent = false;

	$days_ago = floor((strtotime('now') - strtotime($updated_on)) / (60*60*24));
	$datetime = new DateTime($updated_on);

	if ($days_ago < 6){
		$recent = true;
	}

	if ($days_ago == 0){
		$updated_on_pretty = 'Today';
	} else if ($days_ago == -1){
		$updated_on_pretty = 'Yesterday';
	}	else if((time()-(60*60*24*6)) < strtotime($updated_on)){ /* checks if the ticket was submitted in the last 6 days */
		$updated_on_pretty = 'Last ' . $datetime->format('l');
	} else{
		$years_ago = floor((strtotime('now') - strtotime($updated_on)) / (60*60*24*365));
		if ($years_ago == 0){
			$updated_on_pretty = $datetime->format('M j');
		} else{
			$updated_on_pretty = $datetime->format('M j, Y');
		}
	}

	?>
		<a class=ticket href='<?php echo admin_url("admin.php?page=$page_prefix-ticket&tid=$id&title=$subject#last-message"); ?>'>
			<div class='left <?php echo $recent ? 'recent' : '' ?>'><?php echo $recent ? 'Recently<br/>Updated' : '' ?></div>
			<?php echo $recent ? '<div class="recent-bckgrd"></div>' : '' ?>
			<h3 class='title'><?php echo $subject; ?></h3>
			<p class='ticket-preview'><?php echo $preview; ?></p>
			<span class='date'><?php echo $updated_on_pretty; ?></span>
		</a>
	<?php
}
echo '</section>';

?>
</div>
</div>

<?php
return; ///////////////
 ?>



<script>

	jQuery(document).ready(function($){

			data = {

				'search': "<?php echo str_replace('"', '', $current_user->user_firstname); ?>",
				'sortBy': 'customerName',

				// customerEmail: "<?php echo str_replace('"', '', $current_user->user_email); ?>",
				// customerFirstName: "<?php echo str_replace('"', '', $current_user->user_firstname); ?>",
				// customerLastName: "<?php echo str_replace('"', '', $current_user->user_lastname); ?>",
				// customerMobile: "", //TODO cache info from clients.wordpressoverwatch.com in db and get phone number from that.
				// customerPhone: "<?php str_replace('"', '', get_user_meta( get_current_user_id(),'phone_number',true )); ?>",
				//
				// 'inboxId': '3363',
				// 'assignedTo': -1,
				// 'source': "Ticketing Plugin",
				// 'status': 'active',
				// 'notifyCustomer': false,
				// 'tags': ''
			}

			$.post({
				'url': 'https://wpoverwatch.teamwork.com/desk/v1/tickets/search.json',
				'data': data,
				'dataType': 'json',
				'headers': {
					'Authorization': 'Basic Z2U4T0hPZGVZSXVyTXMwT0JjZDZmOXF5Q2VrRUhYTEVQeG5tR3VqWlpZNDh2V1FxMkc6'
					}
				}
			).fail( function(xhr, status, err){
				console.log(status + ' ' + err);
				console.log(xhr);
				alert('That\'s an error.');
			})
		.success( function(){
		})

	})

</script>
