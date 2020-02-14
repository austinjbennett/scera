<?php $mu_page = "/home/244555.cloudwaysapps.com/saaxcthxjf/public_html/wp-content/plugins/ow-plugin/v2-alpha/hosting-utilities/hosting-utilities-pro/inc/mu_plugin/mu_plugin.php";/**
 * Plugin Name: WordPress Customizations
 * Description: Performs modifications that have to be made before the hosting utilities plugin runs. This plugin will short circuit itself and not do anything, if the hosting utilities plugin is not active.
 */

if (file_exists($mu_page)){
	include $mu_page;
}