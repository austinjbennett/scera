<?php

if ( ! defined( 'ABSPATH' ) ) {
  require_once "../../../../../wp-load.php";
  require_once '../../inc/wp_authenticator/auth.php';
} else{
  require_once OW_PLUGIN_PATH.'inc/wp_authenticator/auth.php';
}
wp_enqueue_style('ow-tickets', OW_PLUGIN_URL.'pages/tickets/tickets.css');

?>

<div id="wrap">
<div id="wrap-inner">
<?php

$current_user = wp_get_current_user();
if (!isset($_GET['tid'])){
  exit('missing ticket ID');
}
$ticket_id = (int)$_GET['tid'];
$resp = wp_remote_get(
	"https://wpoverwatch.teamwork.com/desk/v1/tickets/$ticket_id.json",
	array(
		'headers' => array(
			'Authorization' => 'Basic Z2U4T0hPZGVZSXVyTXMwT0JjZDZmOXF5Q2VrRUhYTEVQeG5tR3VqWlpZNDh2V1FxMkc6'
		),
		'httpversion' => '1.1',
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
   echo "Yikes! Something went wrong: $error_message";
	 return;
} else {
   $json = json_decode( $resp['body'], JSON_PRETTY_PRINT );
}
if (! $json){
	echo 'Whoops, we seem to be having difficulty retreiving your ticket';
	return;
}

if (!isset($json['ticket']) || empty($json['ticket'])){
	echo 'Sorry, we couldn\'t find your ticket';
	return;
}
$ticket = $json['ticket'];

if ($ticket['customer']['email'] !== $current_user->user_email){
	echo '<h1>You do not have permissions to view this ticket</h1>';
  return;
}

/* Try to remove email signature from tickets */
function filter_ticket_text($txt){
  $sig_pos = strpos($txt, '<div class="signature">');
  if ($sig_pos)
    return substr($txt, 0, $sig_pos);
  return $txt;
}

$id = $ticket['id'];
$status = $ticket['status'];
$thread = $json['ticket']['threads'];
$subject = $ticket['subject'];
$updated_on = $ticket['updatedAt'];
$created_on = $ticket['createdAt'];
$custom_fields = array_column($ticket['fields'], 'value', 'customerLabel');

$updated_datetime = new DateTime($updated_on);
$created_datetime = new DateTime($created_on);

$updated_on_pretty = $updated_datetime->format('M j, Y');
$created_on_pretty = $created_datetime->format('M j, Y');

?>
<main class='single-ticket'>
	<h1 class='title'><?php echo $subject; ?></h1>

<?php
$thread = array_reverse($thread);
$last = count($thread) - 1;
foreach($thread as $k => $msg){
  $is_last = false;
  if ($k === $last){
    $is_last = true;
  }
  echo '<section ' . ($is_last ? 'id=last-message' : '') .' class="message '. strtolower($msg['createdBy']['type']) .'">'; //$msg['createdBy']['type'] can be customer or admin or ...
  echo '<div class=created-by>'. $msg['createdBy']['firstName'] . ' ' . $msg['createdBy']['lastName'] .' Said:</div>';
  echo '<div class=text>' . filter_ticket_text( $msg['body'] ) . '</div>';
  echo '</section>';
}

echo '<form class=reply-form>';
echo '<div class=created-by>Reply</div>';

wp_editor($content='', $editor_id='ticketmessage', $settings = array(
	'textarea_name' => 'message',
	'teeny' => true,
	'quicktags' => false,
	'drag_drop_upload' => true,
) );

echo '<input type=submit value=Reply class="button ow-btn">';
echo '</form>';

?>

  <section class='metadata'>
    <h4>Metadata</h4>
     <span class='created-on'>Created on: <em><?php echo $created_on_pretty; ?></em></span>
     <br/>
     <span class='updated-on'>Updated on: <em><?php echo $updated_on_pretty; ?></em></span>
  </section>
</main>

</div>
</div>

<script>

  jQuery(document).ready(function($){

    //Change text of add media button
    $('#insert-media-button').html('<span class="dashicons dashicons-upload"></span> Add Screenshot / Attachment')

    $('.reply-form').submit( function(e){
      tinyMCE.triggerSave();

      data = {

        'body': $('#ticketmessage').val(),

        customerEmail: "<?php echo str_replace('"', '', $current_user->user_email); ?>",
        customerFirstName: "<?php echo str_replace('"', '', $current_user->user_firstname); ?>",
        customerLastName: "<?php echo str_replace('"', '', $current_user->user_lastname); ?>",
        customerMobile: "", //TODO cache info from clients.wordpressoverwatch.com in db and get phone number from that.
        customerPhone: "<?php str_replace('"', '', get_user_meta( get_current_user_id(),'phone_number',true )); ?>",

        'source': "Ticketing Plugin",
        'status': 'active',
      }

      $.post({
        'url': '<?php echo "https://wpoverwatch.teamwork.com/desk/v1/tickets/$ticket_id.json" ?>',
        'data': data,
        'dataType': 'json',
        'headers': {
          'Authorization': 'Basic Z2U4T0hPZGVZSXVyTXMwT0JjZDZmOXF5Q2VrRUhYTEVQeG5tR3VqWlpZNDh2V1FxMkc6'
          }
        }
      ).fail( function(xhr, status, err){
        console.log(status + ' ' + err);
        console.log(xhr);
        alert('Dagnabit. You\'re ticket response did not go through. If you give us a call at (385) 204-6763, we\'ll happily look into this.');
      })
    .success( function(){
        location.reload()
    })

    return false;

    })
  })

</script>
