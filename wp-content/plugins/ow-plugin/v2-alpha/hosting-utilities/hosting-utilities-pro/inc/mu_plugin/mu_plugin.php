<?php
// This file gets included by a must used plugin
// And will execute the following code before any plugins have a chance to run

defined('ABSPATH') || die();

// Set the email that WordPress will send notifications to when it detects a PHP error to something other than the client's email
if ( ! defined('RECOVERY_MODE_EMAIL'))
    define('RECOVERY_MODE_EMAIL', 'russell@wordpressoverwatch.com'); // TODO use account manager email from info service, and use my email as backup

/* don't do anything else if ow-plugin is disabled */
if ( in_array('ow-plugin/ow-plugin.php', get_option('active_plugins'))){

    /* Allow WP to stop loading for this type of REST request */
    if ( defined('HU_MU_PLUGIN_REST_REQUEST') ){
        include 'ajax_request.php';
        return;
    }

    //include 'auto_updates.php';
    include 'exclude_plugins.php';
    include 'mission_control_dashboard_fix.php';

    /* Remove WP Rocket branding (As of version 3.0, they no longer let us to customize the branding beyond this) */
    define ('WP_ROCKET_WHITE_LABEL_FOOTPRINT', true);
    define ('WP_ROCKET_WHITE_LABEL_ACCOUNT', true);

    /* disable auto update emails */
    add_filter( 'auto_core_update_send_email', '__return_false' );
}
