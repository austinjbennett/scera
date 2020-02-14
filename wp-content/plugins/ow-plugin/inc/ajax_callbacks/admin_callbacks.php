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


function ow_setup_ajax_callback() {
 if ( check_ajax_referer( 'ow-setup', 'security', false) ) {

	 	try{
	 		$res = require __dir__.'/../setup/run_setup.php';
		} catch (Exception $e) {
			$setup = get_option('ow_setup_versions', array());
			$setup['success'] = -1;
			update_option('ow_setup_versions', $setup, 'no');
			http_response_code(500);
			exit('failed');
	 }

	 exit(json_encode($res));

	 if (isset($res['stages']['success']) && $res['stages']['success'] === true){
		 exit(json_encode($res));
	 } else{
		 http_response_code(500);
		 exit(json_encode($res));
	 }

 } else {
	 http_response_code(500);
   exit('Message could not be sent: security check failed');
 }
}
add_action( 'wp_ajax_ow_setup', 'ow_setup_ajax_callback' );


function ow_show_hidden_plugins() {
 if ( check_ajax_referer( 'ow-show-hidden-plugins', 'nonce', false) ) {

	 $urlparts = parse_url(home_url());
	 $the_domain = $urlparts['host'];

	 $expires = time()+(3600*12); /* will expire in 12 hour */

	 /* Also setting expiration date as cookie value so we can report to the user when the cookie expires */
	 setcookie('show_hidden_plugins', $expires, $expires, '/wp-admin', $domain=$the_domain, $secure=is_ssl());
	 exit('success');

 } else {
	 http_response_code(500);
   exit('Message could not be sent: security check failed');
 }
}
add_action( 'wp_ajax_ow_show_hidden_plugins', 'ow_show_hidden_plugins' );
