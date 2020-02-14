<?php
/*
Plugin Name: Hosting Utilities Billing
Description: This plugin allows you to view and make changes to your site care plan.
Version: 1
*/
defined( 'ABSPATH' ) || exit();

/*
    define constants
*/
const HOSTING_UTILITIES_BILLING_VERSION = '1';

define( 'HU_BILLING_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
if (!defined('HU_BILLING_PLUGIN_URL')) /* may have already been defined by our dev loader plugin */
    define( 'HU_BILLING_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

/*
    load hosting utilities common library if it hasn't been loaded already
*/
if (! defined('HU_COMMON_FUNCTIONS_LOADED'))
    require 'common/init.php';

/*
    Add menu
*/
add_action('hu_add_submenu_before_more_link', function($prefix){
    $pages_path = HU_BILLING_PLUGIN_PATH . 'pages/';

    add_submenu_page(
	  $prefix, /* parent slug */
	  'My Site Care Plan', /* page title */
	  'My Plan', /* menu title */
	  'manage_options', /* capability */
	  "$prefix-plan", /* slug */
	  function() use ($pages_path){ require $pages_path.'plan/my_plan.php'; } /* callback */
	);

});
