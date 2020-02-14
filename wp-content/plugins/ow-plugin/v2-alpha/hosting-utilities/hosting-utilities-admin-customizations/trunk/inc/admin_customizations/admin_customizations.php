<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$wpoverwatch_settings = get_option('hu_admin_settings', array());

if (is_hu_page()){
	function hu_add_body_classes($classes){
		$hu_page_prefix = strtolower( hu_branding('url friendly') );
		$add_class = str_replace($hu_page_prefix, 'wpoverwatch', strtolower($_REQUEST['page']) );
		return $classes . ' ' . $add_class . ' ';
	}
	add_filter( 'admin_body_class', 'hu_add_body_classes', 999 );
}

/* TODO add following to menu items that have been recently viewed: border-left: 3px solid #96901255; */
/* And then create a recently viewed menu containing these items */

/*
 * Lockout the backend when migration_mode is set
*/
if ( ! empty($wpoverwatch_settings['migration_mode']) ){

  function migration_mode_lockout_notice() {
    if( ! is_hosting_utilities_user()) {
      require 'lockout_notices/migration_page.php';
      exit();
    }
  }
  add_action('init', 'migration_mode_lockout_notice'); /* init is the earliest we can check if we are the wpoverwatch user */

  function hu_maintenance_mode_warning() {
    if( is_hosting_utilities_user()) {
      ?>
      <div class="notice notice-warning">
          <p><b>Migration mode is enabled:</b> All other users are getting locked out.</p>
      </div>
      <?php
    }
  }
  add_action( 'admin_notices', 'hu_maintenance_mode_warning' );
}

/*
 * Hide upgrade notices
*/
if ( ! empty($wpoverwatch_settings['hide_update_notices']) ){
	define('DISABLE_NAG_NOTICES', true);

	function hide_notices_until_js_filters_through_them(){
		?>
		<style>
			.wrap:not(.doneFilteringNotices) > div.notice, .wrap:not(.doneFilteringNotices) > div.updated, .wrap:not(.doneFilteringNotices) > div.warning, .wrap:not(.doneFilteringNotices) > div.error,
			#wpbody-content:not(.doneFilteringNotices) > div.notice, #wpbody-content:not(.doneFilteringNotices) > div.updated, #wpbody-content:not(.doneFilteringNotices) > div.warning, #wpbody-content:not(.doneFilteringNotices) > div.error{
			    display: none;
			}
		</style>
		<?php
	}
	add_action( 'admin_enqueue_scripts', 'hide_notices_until_js_filters_through_them' );

	function hu_hide_update_notices() {
    if( is_hosting_utilities_user()) {
      ?>
      <script>
	  	document.addEventListener("DOMContentLoaded", function() {
		  // Hide any notices telling us we need to update a plugin. We can already find this info on the plugins page.

		  // var noticeRegex = new RegExp("((update(?!d)|upgrade|expired)(?![\s]+(setting|option|database)))" +
		  // "|(((leav(e|ing)|give)\s(us\s)?(a\s)?(<a(.*))?(review)))" +
		  // "|(rate us)/i.test(notice.innerHTML)", 'i')

          for (var notice of document.querySelectorAll(
			  '.wrap > div.notice, .wrap > div.updated, .wrap > div.warning, .wrap > div.error, ' +
		   	  '#wpbody-content > div.notice, #wpbody-content > div.updated, #wpbody-content > div.warning, #wpbody-content > div.error') ){

						if (
							/*
							    Always show the notice if it contains the word database followed
								by the word update or upgrade or the phrase "needs to be updated". (case insensitive)
							*/
							! /database\s+(update|upgrade|needs to be udpated)/i.test(notice.innerHTML)
							/*
							    Otherwise, hide the notice if we find:
									the words update, upgrade, or expired
									but not the word updated.
									And these words are NOT followed by the words settings, options, or database

								or if we find
									the word leave, leaving, or give
									followed by
									- whitespace
									- the optional word 'us' + whitespace
									- the optional word 'a' + whitespace
									- an optional a tag
									- and the word review

								or if we find
									the phrase rate us

								This check is case insensitive

								Also, hide the notice if we find one of the following phrases:
								 - "This theme requires the following plugins" (Envato plugin)
								 - "Connect Jetpack to activate" (Jetpack plugin)
							*/
							&& (
								/((update(?!d)|upgrade|expired)(?![\s]+(setting|option|database)))|(((leav(e|ing)|give)\s(us\s)?(a\s)?(<a(.*))?(review)))|(rate us)/i.test(notice.innerHTML)
								|| /(This theme requires the following plugins|Connect Jetpack to activate)/.test(notice.innerHTML)
							)
						){
							notice.style.display = 'none';
						}
					}

					/* To prevent a flash of content, the CSS initially hides all notices.
					We need to add a class to .wrap to let the CSS know that it is safe to display the remaining notices  */
					document.querySelectorAll('.wrap, #wpbody-content').forEach( function(el){
						el.classList.add('doneFilteringNotices')
					});
		});
      </script>
      <?php
    }
  }
  add_action( 'admin_notices', 'hu_hide_update_notices', 99999 );
}

include_once 'sidebar_menu.php';
include_once 'woocommerce.php';


/*
	Display a message on dev and staging sites
	TODO Would be nice to change the favicons instead, since favicons are naturally what we use to identify websites
*/
/* staging sites */
/*
if(preg_match('~(.wp-overwatch.com)~', home_url())) {
	function hu_dev_site_warning() {
    if( is_hosting_utilities_user()) {
      ?>
      <div class="notice notice-warning">
          <p><b>Staging Site:</b> Changes won't appear on the live site.</p>
      </div>
      <?php
    }
  }
  add_action( 'admin_notices', 'hu_staging_site_warning' );
}
*/
/* dev sites */
/*
if(preg_match('~(.dev|.local|.ow)~', home_url())) {
	function hu_dev_site_warning() {
    if( is_hosting_utilities_user()) {
      ?>
      <div class="notice notice-success">
          <p><b>Dev Site:</b> Feel free to destroy this site.</p>
      </div>
      <?php
    }
  }
  add_action( 'admin_notices', 'hu_dev_site_warning' );
}
*/
