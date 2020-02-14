<?php
/*
	Add protected by WP Overwatch link in the footer
	Load custom styles
	And check the remember me checkbox (we've changed it to a hidden input)
*/
function ow_login_footer() {
	/* If this client does not have the theme my plugin installed, we will add our own stylinh to the login page */
	if (! defined('THEME_MY_LOGIN_PATH')){

		?>
	  <p class="ow-login-footer">
	  	<a href="<?php echo login_whitelabeled_info('url'); ?>"><?php echo get_bloginfo('name') ? get_bloginfo('name').' is ' : ''; ?>protected by <?php echo login_whitelabeled_info('official'); ?></a>
	  </p>
		<?php

		echo '<style>';
		readfile(OW_PLUGIN_PATH."wpoverwatch_theme/css/login-page.css");
		?>
		#login h1 a, .login h1 a {
				background-image: url("<?php echo login_whitelabeled_info('logo') ?: 'https://info.wordpressoverwatch.com/wp-content/uploads/2018/10/Logo_Favicon2-light-small.png'; ?>");
				display: none;
		}
		<?php
		echo '</style>';

		echo "<script>document.getElementById('rememberme').checked = true;</script>";

	}
}
add_action('login_footer','ow_login_footer');

function ow_login_announcements_js(){
	wp_enqueue_script( 'ow-login-announcements-js', OW_PLUGIN_URL.'pages/login_page/login_announcements.js', false );
	wp_localize_script( 'ow-login-announcements-js', 'ow', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'ow-announcement-branding' )
	) );
}
add_action( 'login_enqueue_scripts', 'ow_login_announcements_js' );

function ow_login_announcements_css(){
	wp_enqueue_style( 'ow-login-announcements-css', OW_PLUGIN_URL.'pages/login_page/login_announcements.css', false );
}
add_action( 'login_enqueue_scripts', 'ow_login_announcements_css' );

/* Add dashicon support (for the login icons) */
function dashicons_login_page() {
	add_action( 'wp_enqueue_scripts', 'dashicons_login_page' );
}
add_action('login_head', 'dashicons_login_page');

/* Change the favicon on the login page (most of the time this won't actually do anything) */
function login_favicon() {
	/* Most browsers are using the favicon specified by WordPress instead */
	echo '<link rel="shortcut icon" href="https://wp-overwatch.com/favicon.ico" />';
}
add_action('login_head', 'login_favicon');


//TODO this is broken. It does not seem to be receiving the redirect_to paramater anymore.
//Perhaps because the login form is not including this query paramater on the form action?
/*
	Redirect to the WP Overwatch page instead of the dashboard
	Also redirect non-privelaged accounts (subscribers, customers, etc.) to the front of the website after logging in
*/
function admin_login_redirect( $redirect_to, $request, $user ) {
	global $user;

	/* At this point we are not able to use current_user_can, but we are able to manually check this info */
	if (isset( $user->allcaps ))
	if ($redirect_to == home_url('/wp-admin/')){
		if (isset( $user->allcaps ) && $user->allcaps['edit_posts']){
			$redirect_to = admin_url('wp-overwatch');
		} else{
			$redirect_to = home_url();
		}
	}
	return $redirect_to;


	global $user;
	if( isset( $user->roles ) && is_array( $user->roles ) ) {
		if( in_array( "administrator", $user->roles ) ) {
			return $redirect_to;
		} else {
			return admin_url('wp-overwatch');
		}
	}
	else {
		return $redirect_to;
	}

}
//add_filter("login_redirect", "admin_login_redirect", 10, 3);


function login_whitelabeled_info($modifier='default'){
	$defaults = array(
		'url' => 'https://wp-overwatch.com/',
		'official' => 'WP Overwatch LLC',
		'default' => 'WP Overwatch',
		'logo' => '',
	);
	$info = get_option('ow_whitelabel_info', $defaults);

	switch($modifier){
		case 'url':
			return $info['url'];
		case 'official':
			return $info['official'];
		case 'logo':
			return $info['logo'];
		default:
			return $info['default'];
	}
}

function display_logo(){
	$logo = get_option('ow_site_logo', false);
	if ($logo === false){
		$logo_url = login_whitelabeled_info('logo');
		if ( ! $logo_url){
			$logo = "<a href='". home_url() ."' class=custom-logo-link rel=home itemprop=url><img src=$logo_url class=custom-logo alt=logo itemprop=logo></a>";
		}
	}
	echo $logo;
}
