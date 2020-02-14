<?php

if ( ! defined( 'ABSPATH' ) ) {
  require_once "../../../../../wp-load.php";
  require_once '../../inc/wp_authenticator/auth.php';
} else{
  require_once OW_PLUGIN_PATH.'inc/wp_authenticator/auth.php';
}
wp_enqueue_style('ow-tickets', OW_PLUGIN_URL.'pages/tickets/tickets.css');

?>

<h1>Submit a Ticket</h1>

<div id="wrap">
<div id="wrap-inner">

<form class="ticket-form" action="#" method="POST">

  <p>Please let us know if you experience any problems with your website, need something changed, or just have a question. We're happy to help.</p>
  <?php if ( ow_branding('tickets url') ): ?>
      <p>And make sure you bookmark <a href='<?php echo ow_branding('tickets url'); ?>'>this alternative form</a>, so you can still reach us if for whatever reason you become unable to login to your website.</p>
  <?php endif; ?>

  <label>Title: <input name=subject value='' class=title /></label> <br />

  <label for=priority> Type: </label><select name=priority class=priority />
    <option value=high>Red Alert! Website will not load</option> <!-- highest -->
    <option value=high>Checkout system is not working properly</option>
    <option value=high>Contact/lead form is not working</option>
    <option value=medium>Website is not rendering properly</option>
    <option value=normal selected="selected">Misc problem</option>
    <option value=normal style='height:1px;background:#c1c1c1;min-height:1px;font-size:1px;'><hr/></option>
    <option value=low class='low'>Setup ecommerce promotion</option>
    <option value=low class='low'>Website configuration or content change</option>
    <option value=low class='low'>Low priority task</option> <!-- Lowest -->
  </select>
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

  <input type="hidden" name="type" value="question" />
  <!-- <select name="type">
    <option value="Question">Question</option>
    <option value="Problem">Problem</option>
  </select> -->
  <br />

  <input type=submit value='Submit Ticket' class='button ow-btn' />

  <?php $current_user = wp_get_current_user(); ?>

  <script>

    jQuery(document).ready(function($){

      //Change text of add media button
      $('#insert-media-button').html('<span class="dashicons dashicons-upload"></span> Add Screenshot / Attachment')

      $('.ticket-form').submit( function(e){
        tinyMCE.triggerSave();

        /* Problem type IDs start at 21. We get the index of the currently selected option, and that to 21 to get the ID for the appropriate problem type.  */
        var problemID = String( 21 + Array.from( $('.priority option') ).indexOf( document.querySelector('.priority option:checked') ) );

        data = {

          'subject': $('.title').val(),
          'message': $('#ticketmessage').val(),

          'customer': {
              'email': "<?php echo str_replace('"', '', $current_user->user_email); ?>",
              'firstName': "<?php echo str_replace('"', '', $current_user->user_firstname); ?>",
              'lastName': "<?php echo str_replace('"', '', $current_user->user_lastname); ?>",
              //TODO The phone number from info.wordpressoverwatch.com would be more reliable if we had a way of matching up those contacts with Wordpress users.
              'mobile': "<?php echo str_replace('"', '', get_user_meta( get_current_user_id(),'phone_number',true )); ?>",
              'phone': "<?php echo str_replace('"', '', get_user_meta( get_current_user_id(),'phone_number',true )); ?>"
          },

          'priority': $('.priority').val(),

          'inboxId': 3363,
          'assignedTo': -1,
          'source': "Ticketing Plugin",
          'status': 'active',
          'notifyCustomer': true,
          'tags': [],
          'editMethod': "html",

          "fields":[
          {"id":20,"agentLabel":"WordPress Username","customerLabel":"WordPress Username","description":"","defaultValue":"","placeholder":"","displayOrder":0,"kind":"text","customerRequired":false,"agentRequired":false,"addToNewInboxes":true,"options":[],"apps":[{"id":58,"name":"portal","canViewData":false,"canEditData":false},{"id":59,"name":"contact form","canViewData":false,"canEditData":false},{"id":60,"name":"helpdocs","canViewData":false,"canEditData":false}],"inboxes":[3440,3363,3442,3350],"locked":false,"enabled":true,"data":"","isValid":true,"value":"<?php echo str_replace('"', '', $current_user->user_login); ?>"},
          {"id":21,"agentLabel":"Problem Type","customerLabel":"Type of Problem","description":"","defaultValue":"","placeholder":"Choose one...","displayOrder":0,"kind":"dropdown","customerRequired":true,"agentRequired":true,"addToNewInboxes":false,"options":
            [
              {"id":21,"name":"Red Alert! Website will not load","displayOrder":0,"selected":false},
              {"id":22,"name":"Checkout system is not working properly","displayOrder":1,"selected":false},
              {"id":23,"name":"Contact/lead form not working","displayOrder":2,"selected":false},
              {"id":24,"name":"Website is not rendering properly","displayOrder":3,"selected":false},
              {"id":25,"name":"Misc problem","displayOrder":4,"selected":true},
              {"id":26,"name":"question","displayOrder":5,"selected":false},
              {"id":27,"name":"Setup e-commerce promotion","displayOrder":6,"selected":false},
              {"id":28,"name":"Change website configuration","displayOrder":7,"selected":false},
              {"id":29,"name":"Difficult configuration/customization","displayOrder":8,"selected":false},
              {"id":30,"name":"Misc website customization","displayOrder":9,"selected":false}
            ],
              "apps":[{"id":61,"name":"portal","canViewData":true,"canEditData":true},{"id":62,"name":"contact form","canViewData":true,"canEditData":true},{"id":63,"name":"helpdocs","canViewData":true,"canEditData":true}],"inboxes":[3363],"locked":false,"enabled":true,"data":"","isValid":true,"value":problemID},
          {"id":22,"agentLabel":"Domain","customerLabel":"Domain","description":"Your website's domain. Example: xyz.com","defaultValue":"","placeholder":"","displayOrder":0,"kind":"text","customerRequired":false,"agentRequired":true,"addToNewInboxes":false,"options":[],"apps":[{"id":64,"name":"portal","canViewData":true,"canEditData":true},{"id":65,"name":"contact form","canViewData":true,"canEditData":true},{"id":66,"name":"helpdocs","canViewData":true,"canEditData":true}],"inboxes":[3363],"locked":false,"enabled":true,"data":"","isValid":true,"value":"<?php echo home_url(); ?>"}
          ]
        }

        fetch('https://wpoverwatch.teamwork.com/desk/v1/tickets.json', {
          method: 'post',
          headers: {
            'Accept': 'application/json, text/plain, */*',
            'Content-Type': 'application/json',
            'Authorization': 'Basic Z2U4T0hPZGVZSXVyTXMwT0JjZDZmOXF5Q2VrRUhYTEVQeG5tR3VqWlpZNDh2V1FxMkc6'
          },
          body: JSON.stringify(data)
      }).then(response=>{
          if (!response.ok) {
              alert('There was an error submitting your ticket. If this problem persists, please call us at (385) 204-6763');
              console.log(res)
          }
          console.log('successfully created ticket');
          <?php
            $name = $current_user->user_firstname;
            $style_url = urlencode( OW_PLUGIN_URL.'pages/tickets/tickets.css' );
            $redirect_uri = urlencode( admin_url('admin.php?page='.ow_branding('url friendly')) );
            $query_string = "?name=$name&style_url=$style_url&redirect_uri=$redirect_uri";
          ?>
          window.location.replace( '<?php echo OW_PLUGIN_URL . 'pages/tickets/ticket_created.php' . $query_string; ?>' );
      }).catch(function(error) {
          console.log(error)
          alert('Encountered the following problem when submitting your ticket: "' + error.message + '". Check your internet connection, and give us a call/text at (385) 204-6763 if you determine the problem is not due to your internet connection.');
      });

      return false;

      })
    })

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
