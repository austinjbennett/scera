<?php
/*
Plugin Name: Hosting Utilities Dev Loader
Description: Allows you to work out of local SVN repos. Create a folder called 'hosting-utilities' in the root directory of your WordPress installation, and the dev loader will locate any plugins it finds, including plugins in SVN repos, and will allow you to activate those plugins from a new tab that will appear on the plugins menu called "hosting utilities".
Version: 1
*/

if ( ! defined( 'ABSPATH' ) ) {
   exit;
}


/* Used By the dev loader */
if ( ! defined('HOSTING_UTILITIES_DEV_DIR') )
    define( 'HOSTING_UTILITIES_DEV_DIR', dirname(dirname(__FILE__)). '/hosting-utilities' );

/* Define the plugin URL paths */
if ( ! defined('HU_OPTIONS_PLUGIN_URL') )
    define( 'HU_OPTIONS_PLUGIN_URL', '/wp-content/plugins/ow-plugin/v2-alpha/hosting-utilities/hosting-utilities-aaa-options/trunk/' );

if ( ! defined('HU_TICKETING_PLUGIN_URL') )
    define( 'HU_TICKETING_PLUGIN_URL', '/wp-content/plugins/ow-plugin/v2-alpha/hosting-utilities/hosting-utilities-ticketing/' );


// Not sure if the subplugins will try to update themselves, but if so:
// TODO: The automatic_updates_is_vcs_checkout filter can be used to stop the sub-plugins from trying to auto update themselves
// see line 150 of https://developer.wordpress.org/reference/classes/wp_automatic_updater/should_update/
// $this->is_vcs_checkout uses automatic_updates_is_vcs_checkout filter

/* Load a sleightly modified version of the wp-plugin-directories framework at https://github.com/chrisguitarguy/WP-Plugin-Directories */
require 'plugin-dirs.php';
require 'loader.php';
