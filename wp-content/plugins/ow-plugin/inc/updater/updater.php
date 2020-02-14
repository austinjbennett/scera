<?php

define( 'RKV_UPDATE_URL', 'https://repo.wordpressoverwatch.com/update' );
define( 'RKV_ITEM', 'WP Overwatch Utilities' ); // The item name on repo.wordpressoverwatch.com
define( 'YOUR_PLUGIN_UNIQUE', '0N0iN69IbjieA24p' );


function ow_myplugin_load_updater() {
  if ( ! class_exists( 'RKV_Remote_Updater' ) ) {
    include( 'RKV_Remote_Updater.php' );
  }
}
add_action( 'plugins_loaded', 'ow_myplugin_load_updater' );


/**
 * load our auto updater function
 */
function rkv_auto_updater() {
	// Setup the updater
	$updater = new RKV_Remote_Updater( RKV_UPDATE_URL, OW_PLUGIN_FILE, array(
		'item'		=> RKV_ITEM,
		'version' => RKV_VERS,
    'unique' => YOUR_PLUGIN_UNIQUE
		)
	);
}
add_action ( 'admin_init', 'rkv_auto_updater' );
