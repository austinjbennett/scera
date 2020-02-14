<?php

if ( ! defined( 'ABSPATH' ) ) {
  require_once "../../../../../wp-load.php";
  require_once '../../inc/wp_authenticator/auth.php';
} else{
  require_once HU_PRO_PLUGIN_PATH.'wp_authenticator/auth.php';
}
wp_enqueue_script( 'DOMPurify', HU_TICKETING_PLUGIN_URL.'static/purify.min.js', array(), '1.0.11' );
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
	"https://tickets.wordpressoverwatch.com/v1/ticket/$ticket_id?extended-results",
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
       <p><b>Communication error:</b> I was unable to fetch the ticket.</p>
     </div>
     <script> console.error('Error while retrieving the ticket', '<?php echo addslashes($resp['body']); ?>') </script>
  <?php
  return;
} else {
   $ticket = json_decode( $resp['body'], true );
}
if (! $ticket){
	echo '<h1>Whoops, we seem to be having difficulty retreiving your tickets</h1>';
	return;
}

/* Try to remove email signature from tickets */
function filter_ticket_text($txt){
  $sig_pos = strpos($txt, '<div class="signature">');
  if ($sig_pos)
    return substr($txt, 0, $sig_pos);
  return $txt;
}

$status = $ticket['status'];
$thread = $ticket['conversation'];
$subject = $ticket['title'];
$updated_on = date('m/d/Y H:i:s', end($ticket['conversation'])['timestamp']);
$created_on = date('m/d/Y H:i:s', $ticket['creationTimestamp']);

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

  $from = $msg['from'];
  $sender = trim(
		  ( isset( $from['firstName'] ) ? $from['firstName'] : '' )
		. ' '
		. ( isset( $from['lastName'] ) ? $from['lastName'] : '')
	);
	if (!$sender)
		$sender = $from['email'];
	if (!$sender)
		$sender = $from['phone'];

	$isAdmin = preg_match('/@wordpressoverwatch.com$/', $from['email']);

  ?>
  <section <?php echo $is_last?'id=last-message':''; ?> class="message<?php echo $isAdmin?' admin':''; ?>">
		<div class=created-by><?php echo htmlspecialchars($sender); ?> said:</div>
		<div class=text id='ticket-message-<?php echo $k;?>'></div>
		<script>
			jQuery( function($) {
				var dirty = <?php echo json_encode($msg['message']); ?>;
				var clean = DOMPurify.sanitize(dirty, {FORBID_TAGS:['style'], SAFE_FOR_JQUERY: true})
				$('#ticket-message-<?php echo $k;?>').html(clean)
			} )
		</script>
  </section>
  <?php
}

echo '<form class=reply-form>';
echo '<div class=created-by>Reply</div>';
echo '<p style="margin-left:18px"><strong>Note:</strong> This will reply as the person who originaly created the ticket.</p>';

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

  jQuery(function($){

    //Change text of add media button
    $('#insert-media-button').html('<span class="dashicons dashicons-upload"></span> Add Screenshot / Attachment')

    $('.reply-form').submit( function(e){
    	e.preventDefault()
      tinyMCE.triggerSave();

      let message = `
<pre>
Name: <?php echo htmlspecialchars($current_user->user_firstname.' '.$current_user->user_lastname); ?>\n\
Email: <?php echo htmlspecialchars($current_user->user_email); ?>\n\

${$('#ticketmessage').val()}
</pre>`

      $.post({
        'url': 'https://tickets.wordpressoverwatch.com/v1/ticket/<?php echo $ticket_id; ?>/reply',
        'data': {
		      'message': '<pre>'+message+'</pre>',
		      'replyAsCreator': true,
		    },
        'headers': {
          'Authorization': <?php echo json_encode($notpassAuthHeader); ?>,
        }
      })
      .fail( function(xhr, status, err){
        console.log(status + ' ' + err);
        console.log(xhr);
        alert('Dagnabit. Your ticket response did not go through. If you give us a call at (385) 204-6763, we\'ll happily look into this.');
      })
		  .success( function(){
		    location.reload()
		  })
	  })
  })

</script>
