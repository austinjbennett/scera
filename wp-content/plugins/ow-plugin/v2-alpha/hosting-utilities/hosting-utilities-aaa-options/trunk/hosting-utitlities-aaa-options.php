<?php
/*
Plugin Name: Hosting Utilities Roadmap & Misc Options
Description: This plugin allows you to stay up to date on changes to our suite of plugins, adds some general options that did not fit inside any of the other plugins, like the ability to add maintenance/migration notices, and it customizes the login page.
Version: 1
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit;
}

const HU_ROADMAP_AND_OPTIONS_VERSION = '1';
const DEFAULT_CSS_THEME = 'wordpress';

define( 'HU_OPTIONS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
if (!defined('HU_OPTIONS_PLUGIN_URL')) /* may have already been defined by our dev loader plugin */
    define( 'HU_OPTIONS_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

if ( is_admin() && ! defined('HU_COMMON_FUNCTIONS_LOADED') ){
  /* these will only be included on admin pages */
  include_once 'helper_functions.php';
  include_once 'create_admin_pages.php';
  include_once 'inc/admin_css.php';
  include_once 'inc/ajax_callbacks/admin_callbacks.php';
  define('HU_COMMON_FUNCTIONS_LOADED', true);
}

/* If we're on the login page, include our special styling/customizations */
global $pagenow;
if ($pagenow === 'wp-login.php'){
	$hu_settings = get_option('hu_admin_settings', array());
	if ( empty($hu_settings['default_login_page']) ){
		include_once 'pages/login_page/login_page_customizations.php';
	}
}

/* Include these on all pages */
include_once 'inc/all_pages.php';
include_once 'inc/ajax_callbacks/public_callbacks.php';
