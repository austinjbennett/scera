<?php
/*
	Add protected by link in the footer
	Load custom styles
	And check the remember me checkbox (we've changed it to a hidden input)
*/
function hu_login_footer() {
	/* If this client does not have the theme my plugin installed, we will add our own stylinh to the login page */
	if (! defined('THEME_MY_LOGIN_PATH')){

		if ( defined('HU_PRO_VERSION') ){
			?>
			  <p class="ow-login-footer">
			  	<a href="<?php echo login_whitelabeled_info('url'); ?>"><?php echo get_bloginfo('name') ? get_bloginfo('name').' is ' : ''; ?>protected by <?php echo login_whitelabeled_info('official'); ?></a>
			  </p>
			<?php
		} else {
			?>
			<p class="ow-login-footer">
			  <a href=<?= home_url('/') ?>>Back to Homepage</a>
			</p>
			<?php
		}

		echo '<style>';
		readfile(HU_OPTIONS_PLUGIN_PATH."plugin_themes/css/login-page.css");
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
add_action('login_footer','hu_login_footer');

function ow_login_announcements_js(){
	wp_enqueue_script( 'ow-login-announcements-js', HU_OPTIONS_PLUGIN_URL.'pages/login_page/login_announcements.js', false );
	wp_localize_script( 'ow-login-announcements-js', 'ow', array(
		'ajax_url' => admin_url( 'admin-ajax.php' ),
		'nonce' => wp_create_nonce( 'ow-announcement-branding' )
	) );
}
add_action( 'login_enqueue_scripts', 'ow_login_announcements_js' );

function hu_login_announcements_css(){
	wp_enqueue_style( 'ow-login-announcements-css', HU_OPTIONS_PLUGIN_URL.'pages/login_page/login_announcements.css', false );
}
add_action( 'login_enqueue_scripts', 'hu_login_announcements_css' );

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


function login_whitelabeled_info($modifier='default'){
	$defaults = array(
		'url' => '#',
		'official' => 'Hosting Utilities',
		'default' => 'Hosting Utilities',
		'logo' => '',
	);
	$info = get_option('hu_whitelabel_info', $defaults);

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
	$logo = get_option('hu_site_logo', false);
	if ($logo === false){
		$logo_url = login_whitelabeled_info('logo');
		if ( ! $logo_url){
			$logo = "<a href='". home_url() ."' class=custom-logo-link rel=home itemprop=url><img src=$logo_url class=custom-logo alt=logo itemprop=logo></a>";
		}
	}
	echo $logo;
}
