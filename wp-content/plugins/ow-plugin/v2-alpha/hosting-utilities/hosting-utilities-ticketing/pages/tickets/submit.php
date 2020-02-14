<?php

if ( ! defined('HU_PRO_PLUGIN_PATH') ) {
	?>
    <div class="notice notice-error">
        <p><b>Missing Plugin:</b> The ticketing plugin requires Hosting Utilities Pro to be installed.</p>
    </div>
  <?php
	return;
}

if ( ! defined( 'ABSPATH' ) ) {
  require_once "../../../../../wp-load.php";
  require_once '../../inc/wp_authenticator/auth.php';
} else{
  require_once HU_PRO_PLUGIN_PATH.'wp_authenticator/auth.php';
}
//wp_enqueue_style('ow-tickets', HU_TICKETING_PLUGIN_URL.'pages/tickets/ticketsing-pages.css');

include_once __dir__.'/credentials.php'; // Exports things like $domain, $notpassUser and $notpassKey
$notpassAuthHeader = 'Basic ' . base64_encode(base64_encode($notpassUser).':'.$notpassKey);

?>

<h1>Submit a Ticket</h1>

<div id="wrap">
<div id="wrap-inner">

<form class="ticket-form" action="#" method="POST">

  <p>Please let us know if you experience any problems with your website, need something changed, or just have a question. We're happy to help.</p>
  <?php if ( hu_branding('tickets url') ): ?>
      <p>And make sure you bookmark <a href='<?php echo hu_branding('tickets url'); ?>'>this alternative form</a> so you can still reach us if you become unable to login to the website (e.g. you become locked out or the website goes down).</p>
  <?php endif; ?>

  <label>Title: <input name=subject value='' class=title required/></label> <br />

  <label for=priority> Type: </label>
	<select class=priority disabled required></select>
  <br/><br/>

  <label> Question/Problem: </label><br/>
  <?php wp_editor($content='', $editor_id='ticketmessage', $settings = array(
    'textarea_name' => 'message',
    'teeny' => true,
    'quicktags' => false,
    'drag_drop_upload' => true,
  ) ); ?>
  <!-- <textarea id="ticketmessage" name="message" cols="45" rows="32" maxlength="65525" aria-required="true" required="required" style='border: 1px solid #aaa; width: 700px;'>
    <p data-placeholder="Write your question or problem here..." data-placeholder-replies="Leave a reply..." id="message-box" class="show-placeholder"></p>
  </textarea> -->
  <?php //wp_editor( $content='', $editor_id='ticketmessage', $settings = array('drag_drop_upload'=>true) ); ?>

  <!-- <select name="type">
    <option value="Question">Question</option>
    <option value="Problem">Problem</option>
  </select> -->
  <br />

  <input type=submit value='Submit Ticket' class='button ow-btn' />

  <?php $current_user = wp_get_current_user(); ?>

  <script>
  'use strict';
  ;{
    let $ = jQuery

  	function renderPriorityOptions({categories}) {
	  	let $priority = $('.priority')
	  	let isDivided = false
	  	for (let {id,description,priority} of categories) {
	  		$('<option/>')
	  		.attr('value', id)
	  		.text(description)
	  		.appendTo($priority)

	  		if (priority <= 2 && !isDivided) {
	  			isDivided = true
	  			$('<option/>')
					.html('<hr/>')
					.attr('disabled', true)
					.css({height:'1px', background:'#c1c1c1', minHeight:'1px', fontSize:'1px'})
					.appendTo($priority)
	  		}
	  	}

	  	$priority.attr('disabled', false)
	  }
		$.getJSON('https://tickets.wordpressoverwatch.com/v1/category-info', renderPriorityOptions)

    $(document).ready(function(){

      //Change text of add media button
      $('#insert-media-button').html('<span class="dashicons dashicons-upload"></span> Add Screenshot / Attachment')

      $('.ticket-form').submit( async function(e){
      	e.preventDefault()
        tinyMCE.triggerSave();

		var fetchOpts = {
			method: 'post',
			headers: {
				'Accept': 'application/json, text/plain, */*',
				'Content-Type': 'application/x-www-form-urlencoded',
				'Authorization': <?php echo json_encode($notpassAuthHeader); ?>
			},
			body: new URLSearchParams({
				title: $('.title').val(),
				message: $('#ticketmessage').val(),
				category: $('.priority').val(),
				source: 'OW_PLUGIN:TICKET_FORM',

				email: <?php echo json_encode($current_user->user_email); ?>,
				firstName: <?php echo json_encode($current_user->user_firstname); ?>,
				lastName: <?php echo json_encode($current_user->user_lastname); ?>,
				wpUserId: <?php echo get_current_user_id(); ?>,
				wpUserLogin: <?php echo json_encode($current_user->user_login); ?>,
				mobile: <?php echo json_encode(get_user_meta( get_current_user_id(),'phone_number',true )); ?>,
				phone: <?php echo json_encode(get_user_meta( get_current_user_id(),'phone_number',true )); ?>,
				domain: <?php echo json_encode($domain); ?>,
			}).toString(),
		}

		try {
			var response = await fetch('https://tickets.wordpressoverwatch.com/v1/ticket', fetchOpts)
			if (!response.ok){
				throw new Error(await response.text())
			}
		} catch (error) {
			console.log(error)
			try{

				/* only attempt backup method if a ticketing email exists */
				if (<?= hu_branding('ticketing email') ? 'false' : 'true' ?>)
					throw (error)

				var appendMsg = "<br/><br/><b>Note to <?= hu_branding('company') ?>:</b><br/> WordPress had to use a backup method to send in this ticket."
				appendMsg += "<br/>Some fields will be missing in the system. They are included below:";
				appendMsg += "<br/><b>Email:</b> " + <?php echo json_encode($current_user->user_email); ?>;
				appendMsg += "<br/><b>First Name:</b> " + <?php echo json_encode($current_user->user_firstname); ?>;
				appendMsg += "<br/><b>Last Name:</b> " + <?php echo json_encode($current_user->user_lastname); ?>;
				appendMsg += "<br/><b>User ID:</b> " + <?php echo get_current_user_id(); ?>;
				appendMsg += "<br/><b>User Login:</b> " + <?php echo json_encode($current_user->user_login); ?>;
				appendMsg += "<br/><b>Category:</b> " + $('.priority').val();

				var fetchOpts = {
					method: 'post',
					headers: {
						'Accept': 'application/json, text/plain, */*',
						'Content-Type': 'application/x-www-form-urlencoded',
					},
					body: new URLSearchParams({
						title: $('.title').val(),
						message: $('#ticketmessage').val() + ' ',
						nonce: <?= json_encode( wp_create_nonce( 'email_hu_ticket' ) ); ?>
					}).toString(),
				}

				var response = await fetch('/wp-content/plugins/ow-plugin/v2-alpha/hosting-utilities/hosting-utilities-ticketing/rest/backup_submit_ticket.php', fetchOpts)
				if (!response.ok)
					throw new Error(await response.text())

			} catch (error) {
				var submission_failed_msg = 'There was an error submitting your ticket. If this problem persists, please call us at <?= hu_branding('phone', '(385) 204-6763') ?>'
				<?php if ( hu_branding('tickets url') ): ?>
					submission_failed_msg += '.\n\nOr try the public ticket form at ' + <?= json_encode(hu_branding('tickets url')) ?>;
					submission_failed_msg += ' , and please let us know that this ticket form isn\'t working so we can fix it.';
				<?php endif; ?>
				alert(submission_failed_msg)
				throw error
			}
		}

        console.log('successfully created ticket');
        <?php
          $name = $current_user->user_firstname;
          $style_url = urlencode( HU_OPTIONS_PLUGIN_URL.'pages/tickets/tickets.css' );
          $redirect_uri = urlencode( admin_url('admin.php?page='.hu_branding('url friendly')) );
          $query_string = "?name=$name&style_url=$style_url&redirect_uri=$redirect_uri";
        ?>
        window.location.replace( '<?php echo HU_TICKETING_PLUGIN_URL . 'pages/tickets/ticket_created.php' . $query_string; ?>' );

      })
    })

  }
  </script>

</form>

</div>
</div>













<?php return; //TODO maybe implement below code as an optional inline editor? ?>


<script type="text/javascript" src="<?php echo includes_url( 'js/tinymce/tinymce.min.js' ); ?>"></script>
<script type="text/javascript">
  tinyMCE.init({
      selector: "#ticketmessage",
      //plugins : 'advlist autolink link image lists charmap print preview'
      //theme : "inlite",
      mode: "specific_textareas",
      menubar: false,
      elementpath: false,
      skin: "lightgray",
      selection_toolbar: 'bold italic underline | h2 h4 | bullist numlist | quicklink',
      selection_toolbar2: 'link unlink | quickimage | indent outdent ',
      //elements: 'comment',
      //inline: true,
      //insert_toolbar: 'quickimage | indent outdent',
      branding: false,
      insert_toolbar: '',
      min_height: 200,
      invalid_elements : "script",
      hidden_input: false,

      setup: function (editor) {
          editor.on('init', function(){

            //show placeholder once the editor loads
            if (tinyMCE.activeEditor.getContent() == ''){
              jQuery(tinyMCE.activeEditor.bodyElement).find("#message-box").addClass("show-placeholder")
              //jQuery(tinyMCE.activeEditor.bodyElement).find("#message-box").attr("data-placeholder", "My personal thoughts on what $author has written...")
              //tinyMCE.activeEditor.setContent("<p id='imThePlaceholder' data-placeholder='></p>");
            }
          });

          //maybe show placeholder when we loose focus
          editor.on('blur',function(){
            if (tinyMCE.activeEditor.getContent().replace(/\s/g,'') == ''){
              jQuery(tinyMCE.activeEditor.bodyElement).find("#message-box").addClass("show-placeholder")
            }
          });

          //remove placeholder on focus
          editor.on('focus',function(){
            jQuery(tinyMCE.activeEditor.bodyElement).find("#message-box").removeClass("show-placeholder");
          });
          //or remove placeholder on click, to make it easier to resolve a problematic state that can occur
          //if the comment box gains focuses before these JS event are registered
          editor.on('click',function(){
            jQuery(tinyMCE.activeEditor.bodyElement).find("#message-box").removeClass("show-placeholder");
          });
        }

  });
</script>

<style>
/* add a placeholder on the comment box */
#tinymce > p:empty:not(:focus):before,
#message-box.show-placeholder:before{
  content: attr(data-placeholder);
  color: #555;
}
</style>
