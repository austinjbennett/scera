<?php
/*
	Controls auto-updating
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$wpoverwatch_settings = get_option('hu_admin_settings', array());

/* theme updates */
if ( ! empty($wpoverwatch_settings['auto_update_themes']) ){
  add_filter( 'auto_update_theme', '__return_true' );
}

/* core updates */
if ( ! empty($wpoverwatch_settings['auto_update_core']) ){
  add_filter( 'auto_update_core', '__return_true' );
}

/* plugin updates */
if ( ! empty($wpoverwatch_settings['auto_update_plugins']) ){
	if ( ! empty($wpoverwatch_settings['exclude_plugin_updates']) ){
		/* exclude plugins from udpating */
		/*
		If we wanted to permamently stop someone from updating a plugin, even if they try to do so directly, it might be possible by setting an invalid destination with the upgrader_package_options filter https://developer.wordpress.org/reference/classes/wp_upgrader/run/
		*/
	  function exclude_plugins_from_auto_updating( $update, $item ) {

	      // Array of plugin slugs to never auto-update
	      $exclude = explode(',', $wpoverwatch_settings['exclude_plugin_updates']);

	      if ( in_array( $item->slug, $exclude ) ) {
	          return false; // Never update plugins in this array
	      } else {
	          return $update; // Else, use the normal API response to decide whether to update or not
	      }
	  }
	  add_filter( 'auto_update_plugin', 'exclude_plugins_from_auto_updating', 10, 2 );
	} else {
	  add_filter( 'auto_update_plugin', '__return_true' );
	}
}
