<?php
/*
Plugin Name: Hosting Utilities
Description: This plugin helps us with all of the goodies that come with your site care package.
Version: 2.1.1
*/

 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

 define('HU_PLUGIN_FILE', __FILE__);

if (defined('OW_USE_V2') && OW_USE_V2) {
    require 'v2-alpha/ow-plugin.php';
} else{
    require 'v1.php';
}
