<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (! function_exists('endsWith')){
	function endsWith($haystack,$needle){ // case insensitive version
	    $expectedPosition = strlen($haystack) - strlen($needle);
	    return strripos($haystack, $needle, 0) === $expectedPosition;
	}
}

/*
  Checks if we're the wpoverwatch user
  Or if the wpoverwatch get paramater is being used
  In the future I'll make it so there can be multiple wpoverwatch users

  Must be called during or after the init hook has fired (otherwise it will always return false)
*/
function is_wpoverwatch_user(){
  $ow_admin_settings = get_option('ow_admin_settings', array());
  return (
            ! isset($ow_admin_settings['wpoverwatch_userid']) /* If a designated wpoverwatch user has not been set */
            || get_current_user_id() == $ow_admin_settings['wpoverwatch_userid'] /* or if we are that user */
			|| endsWith( wp_get_current_user()->user_login, '-wpoverwatch' ) /* or if we are a wp-overwatch user */
            || isset($_GET['wpoverwatch']) /* alternatively the wpoverwatch paramater can be added to gain wpoverwatch privelages */
         );
}

/*
	Returns if we're currently on one of the plugin pages
	This can be spoofed and should be combined with current_user_can
*/
function is_ow_page(){
	$prefix = ow_branding('url friendly');

	return isset($_REQUEST['page']) && in_array( $_REQUEST['page'], array(
		"$prefix",
		"$prefix-tickets",
		"$prefix-submit-ticket",
		"$prefix-site-health-dashboard",
		"$prefix-plan",
		"$prefix-roadmap",
		"$prefix-options",
		"$prefix-my-tickets",
		"$prefix-ticket"
	) );
}

/*
	Returns if this website is whitelabeled under a different company
*/
function ow_are_we_whitelabeled(){
	$info = get_option('ow_whitelabel_info', false);
	return (!empty($info) && $info['default'] !== 'WP Overwatch');
}

$ow_whitelabeled_defaults = array(
	'url_friendly' => 'hosting',
	'url' => '/',
	'url_pricing' => '/wp-admin/admin.php?page=SiteSmash-plan',
	'tickets_url' => '',
	'logo' => 'https://wordpressoverwatch.com/logo.svg',
	'menu-icon' => 'https://wordpressoverwatch.com/logo.svg',
	'possessive' => "Hosting provider",
	'official' => '',
	'default' => 'Hosting',
);
global $ow_wpoverwatch_branding_defaults;
$ow_wpoverwatch_branding_defaults = array(
	'url_friendly' => 'wpoverwatch',
	'url' => 'https://wp-overwatch.com/',
	'url_pricing' => 'https://wp-overwatch.com/#PanelPricing',
	'tickets_url' => 'https://tickets.wp-overwatch.com',
	'logo' => 'https://wordpressoverwatch.com/logo.svg',
	'menu-icon' => 'https://wordpressoverwatch.com/logo.svg',
	'possessive' => "WP Overwatch's",
	'official' => 'WP Overwatch LLC',
	'default' => 'WP Overwatch',
);
/*
	Returns info that is used to whitelabel this plugin.
	On the rare occasion that the plugin has updated and the updated branding info failed to be retreived, then the $default value will be returned.
	You only need to worry about using $default when you are introducing a new branding field.
	The $default parameter might not always be a part of this function. I think there is a better way I could write this function.
*/
function ow_branding($modifier='default', $default=false){
	global $ow_whitelabeled_defaults;
	$info = get_option('ow_whitelabel_info', $ow_whitelabeled_defaults);

	switch($modifier){
		case 'url friendly':
			/* Ensure an agency is not able to accidentally bring down ow-plugin by entering in a bad url_friendly name */
			$ret = filter_var($info['url_friendly'] ?? $default, FILTER_SANITIZE_URL);
			return $ret;
		case 'url':
			return $info['url'] ?? $default;
		case 'pricing url':
			return $info['url_pricing'] ?? $default;
		case 'tickets url':
			return $info['tickets_url'] ?? $default;
		case 'logo':
			return $info['logo'] ?? $default;
		case 'menu icon':
			return $info['menu_icon'] ?? $default;
		case 'possessive':
			return $info['possessive'] ?? $default;
		case 'official':
			return $info['official'] ?? $default;
		case 'email':
			return $info['email'] ?? $default;
		case 'company':
		case 'default':
		default:
			return $info['default'] ?? $default;
	}
}