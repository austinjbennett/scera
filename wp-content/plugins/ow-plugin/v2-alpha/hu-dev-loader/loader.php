<?php
defined( 'ABSPATH' ) || exit();

new CD_APD_Bootstrap();

function set_custom_location_for_root_dir($default){
	return trailingslashit(HOSTING_UTILITIES_DEV_DIR);
}
// the "adp_root_{$root}" filter is applied in inc/api.php
add_filter( 'adp_root_custom_location', 'set_custom_location_for_root_dir' );

function register_hosting_utility_plugin_directories(){

	// Better abort - if we don't do this, we'll create an error on deactivation of the dev loader plugin.
	if ( ! function_exists( 'register_plugin_directory' ) )
		return;

	register_plugin_directory( array(
		'dir'   => HOSTING_UTILITIES_DEV_DIR,
		'label' => 'hosting utility plugins',
		'root'  => 'custom_location'
	) );

}
add_action( 'plugins_loaded', 'register_hosting_utility_plugin_directories', 0 );
