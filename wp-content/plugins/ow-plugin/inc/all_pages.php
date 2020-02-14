<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wpoverwatch_settings = get_option('ow_admin_settings', array());

/*
 * replace all frontend pages with a maintenance page if maintenance_mode is set
*/
if ( ! empty($wpoverwatch_settings['maintenance_mode']) ){

  include_once OW_PLUGIN_PATH.'helper_functions.php';

  function maintenance_mode_lockout_notice() {
    if( ! is_wpoverwatch_user() && !is_admin()) {
      require 'lockout_notices/maintenance_page.php';
      exit();
    }
  }
  add_action('init', 'maintenance_mode_lockout_notice'); /* init is the earliest we can check if we are the wpoverwatch user */

  function ow_migration_mode_warning() {
    if( is_wpoverwatch_user()) {
      ?>
      <div class="notice notice-warning">
          <p><b>Maintenance mode is enabled:</b> No one else is able to view the website.</p>
      </div>
      <?php
    }
  }
  add_action( 'admin_notices', 'ow_migration_mode_warning' );
}

/* Once the HSTS header has been sent, the browser will remember it for 1 year, and there is no way of undoing this */
if ( ! empty($wpoverwatch_settings['hsts']) && $wpoverwatch_settings['hsts'] === true ){
	function ow_add_hsts_header() {
		// HSTS is a way to tell the browser to always use HTTPS
		// 31536000 = 1 year, includeSubDomains is necessarry for preload, preloaded sites are included with each Chrome update (I think). Check dnstrails.com to discover all subdomains, and then make sure they all load over HTTPS, before enabling this feature.
		// See https://hstspreload.org/ for more info
	    header( 'Strict-Transport-Security: max-age=31536000; includeSubDomains; preload' );
	}
	add_action( 'send_headers', 'ow_add_hsts_header' );
}

function alert_iframes_of_page_change(){
  ?>
  <script> if (window.top !== window){
	  window.top.postMessage({'event':'pageChange', 'page':location.href, 'origin':location.origin}, 'https://missioncontrol.wp-overwatch.com')
	  window.onbeforeunload = function(event) {
		  window.top.postMessage({'event':'navAway'}, 'https://missioncontrol.wp-overwatch.com')
	  }
	  window.addEventListener("load", function(){
		  window.top.postMessage({'event':'pageLoaded'}, 'https://missioncontrol.wp-overwatch.com')
	  })
  } </script>
  <?php
}
add_action( 'admin_footer', 'alert_iframes_of_page_change' );
add_action( 'wp_footer', 'alert_iframes_of_page_change' );

if (isset($_GET['gotoDashboardOnFailure'])){
	function redirect_failures(){
		wp_redirect(home_url('/wp-admin', 'https'));
		exit;
		//header('Location: /wp-admin', true, 302);
	}
	add_filter('wp_die_handler', 'redirect_failures');
}
