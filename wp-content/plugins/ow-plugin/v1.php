<?php

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

// This needs to be updated whenever a new version is added to repo.wordpressoverwatch.com
// And kept in sync with the version number on the item page at repo.wordpressoverwatch.com
// Otherwise, the plugin will continue to report it needs to be updated, even after it is already up to date
const RKV_VERS = '1.5.19';
const DEFAULT_CSS_THEME = 'wordpress';

define( 'OW_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'OW_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
define( 'OW_PLUGIN_FILE', __FILE__  );

if ( is_admin() ){

  /* these will only be included on admin pages */
  include_once 'helper_functions.php';
  include_once 'create_admin_pages.php';
  include_once 'pages/options/update_site_health_dashboard.php';
  include_once 'inc/admin_customizations/admin_customizations.php';
  include_once 'inc/ajax_callbacks/admin_callbacks.php';
  include_once 'inc/updater/updater.php'; /* From https://github.com/norcross/reaktiv-remote-repo */

  /* Load our admin CSS */
  function ow_load_styles() {
    wp_enqueue_style('ow-admin-customizations', OW_PLUGIN_URL."wpoverwatch_theme/css/all-admin-pages.css", $vers=RKV_VERS);
    if (is_ow_page()){
      $css_theme = get_user_meta(get_current_user_id(), 'ow_css_theme', true);
      $css_theme = empty($css_theme) ? DEFAULT_CSS_THEME : $css_theme;
      wp_enqueue_style('ow-page', OW_PLUGIN_URL."wpoverwatch_theme/css/admin-theme-$css_theme.css", $deps=array('ow-admin-customizations'), $vers=RKV_VERS);
    }
  }
  add_action( 'admin_enqueue_scripts', 'ow_load_styles' );
}

/* If we're on the login page, include our special styling/customizations */
global $pagenow;
if ($pagenow === 'wp-login.php'){
	$wpoverwatch_settings = get_option('ow_admin_settings', array());
	if ( empty($wpoverwatch_settings['default_login_page']) ){
		include_once 'pages/login_page/login_page_customizations.php';
	}
}

/* Include these files on all pages */
include_once 'inc/all_pages.php';
include_once 'inc/ajax_callbacks/public_callbacks.php';

/* Update/Activation stuff */
register_activation_hook( __FILE__, function(){
    include_once 'helper_functions.php';
    include_once 'inc/setup/run_setup_if_needed.php';
});
add_action( 'upgrader_post_install', function(){ /* upgrader_post_install is specific to the RKV Updater library we are using */
    include_once 'helper_functions.php';
    include_once 'inc/setup/run_setup_if_needed.php';
});
