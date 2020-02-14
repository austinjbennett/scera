<?php

if ( ! defined( 'ABSPATH' ) ) {
  require_once "../../../../../wp-load.php";
  require_once '../../inc/wp_authenticator/auth.php';
} else{
  require_once HU_PRO_PLUGIN_PATH.'wp_authenticator/auth.php';
}
//wp_enqueue_style('ow-tickets', HU_TICKETING_PLUGIN_URL.'pages/tickets/ticketsing-pages.css');

if ( ! defined('HU_PRO_PLUGIN_PATH') ) {
	?>
    <div class="notice notice-error">
        <p><b>Missing Plugin:</b> The ticketing plugin requires Hosting Utilities Pro to be installed.</p>
    </div>
  <?php
	return;
}

include_once HU_PRO_PLUGIN_PATH.'wp_authenticator/auth.php';

$domain = parse_url( home_url() )['host'];
$notpassUser = 'https://' . $domain;
$notpassKey = get_hu_api_key($domain);
$notpassAuthHeader = 'Basic ' . base64_encode(base64_encode($notpassUser).':'.$notpassKey);

$current_user = wp_get_current_user();

?>

<h1 class='ow-title'>Recent Tickets</h1>

<div id="wrap">
<div id="wrap-inner">
<?php

$resp = wp_remote_get(
	'https://tickets.wordpressoverwatch.com/v1/tickets?extended-results',
	array(
		'headers' => array(
			'Authorization' => $notpassAuthHeader
		)
	)
);

if ( is_wp_error( $resp ) || $resp['response']['code']>=300 ) {
   $error_message = is_wp_error( $resp ) ? $resp->get_error_message() : $resp['body'];
   ?>
     <div class="notice notice-error">
       <p><b>Communication error:</b> I was unable to fetch tickets.</p>
     </div>
    <script> console.error('Error while retrieving tickets', '<?php echo addslashes($resp['body']); ?>') </script>
  <?php
  return;
} else {
   $tickets = json_decode( $resp['body'], true );
}
if (! $tickets){
	echo '<h1>Whoops, we seem to be having difficulty retreiving your tickets</h1>';
	return;
}

if ( empty($tickets) ){
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

$page_prefix = hu_branding('url friendly');

usort( $tickets, function($a, $b){
	return -($a['creationTimestamp'] <=> $b['creationTimestamp']);
} );
echo '<section class=tickets>';
foreach($tickets as $ticket){
	$id = $ticket['ticketId'];
	$preview = ''; // Used to be used, not it isn't and coult probably be removed.
	$status = $ticket['status'];
	$subject = $ticket['title'];
	$updated_on = date('m/d/Y H:i:s', end($ticket['conversation'])['timestamp']);
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
			<h3 class='title'><?php echo htmlspecialchars( $subject ); ?></h3>
			<p class='ticket-preview'><?php echo htmlspecialchars( $preview ); ?></p>
			<span class='date'><?php echo $updated_on_pretty; ?></span>
		</a>
	<?php
}
echo '</section>';

?>
</div>
</div>
