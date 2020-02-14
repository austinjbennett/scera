<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* callback for the ajax request made in the feedback-form.php file */
function ow_roadmap_feedback_ajax_callback() {
 if ( check_ajax_referer( 'ow-roadmap-feedback', 'security', $false) ) {

   $from_name = str_replace(array('"', '\\'), '', $current_user->display_name);
   $from_email = filter_var(wp_get_current_user()->user_email, FILTER_SANITIZE_EMAIL);
   $headers[] = "From: \"$from_name\" <$from_email>";
   $body = strip_tags($_POST['message']);

   wp_mail( 'russell@wordpressoverwatch.com', 'Feedback Form from ' . get_bloginfo(), $body, $headers );

   exit('success');

 } else {
   wp_die('Message could not be sent: security check failed');
 }
}
add_action( 'wp_ajax_ow_roadmap_feedback', 'ow_roadmap_feedback_ajax_callback' );


function ow_basic_options_ajax_callback() {
 if ( check_ajax_referer( 'basic-settings', 'nonce', false) ) {

	update_user_meta(get_current_user_id(), 'ow_css_theme', $_GET['css_theme']);
	exit('{"result":"success", "changed": ["css_theme"] }');

 } else {
	 wp_die('Message could not be sent: security check failed');
 }
}
add_action( 'wp_ajax_ow_basic_options', 'ow_basic_options_ajax_callback' );
