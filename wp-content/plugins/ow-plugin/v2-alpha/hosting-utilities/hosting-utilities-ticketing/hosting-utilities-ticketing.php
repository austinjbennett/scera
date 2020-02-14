<?php
/*
Plugin Name: Hosting Utilities Ticketing
Description: This plugin allows you to submit tickets to us
Version: 1
*/
defined( 'ABSPATH' ) || exit();

/*
    define constants
*/
const HOSTING_UTILITIES_TICKETING_VERSION = '1';

define( 'HU_TICKETING_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
if (!defined('HU_TICKETING_PLUGIN_URL')) /* may have already been defined by our dev loader plugin */
    define( 'HU_TICKETING_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

/*
    load hosting utilities common library if it hasn't been loaded already
*/
if (! defined('HU_COMMON_FUNCTIONS_LOADED'))
    require 'common/init.php';

/*
    load styles,
    replacing .toplevel_page_wpoverwatch and #toplevel_page_wpoverwatch with the branding being used
*/
add_filter('hu_pages_stylesheet_files', function($stylesheets, $css_theme){
    $stylesheets[] = HU_TICKETING_PLUGIN_PATH."styling/ticketing-pages.css";
    return $stylesheets;
}, 15, 2);
add_filter('hu_admin_pages_stylesheet_files', function($stylesheets){
    $stylesheets[] = HU_TICKETING_PLUGIN_PATH."styling/change-menu-styling.css";
    return $stylesheets;
}, 15);

/*
    Add menus
*/
$pages_path = HU_TICKETING_PLUGIN_PATH . 'pages/';

add_action('hu_add_top_level_menu', function($prefix, $img) use ($pages_path){
    add_menu_page(
        'Submit Ticket', /* page title */
        'Submit Ticket', /* menu title */
        'hu_submit_tickets', /* required capability */
        $prefix.'-tickets', /* slug */
        function() use ($pages_path){ require $pages_path.'tickets/submit.php'; }, /* callback */
        $img,
        -9999999 /* menu position */
    );
}, 10, 2);

add_action('hu_add_submenu_before_more_link', function($prefix) use ($pages_path){

    add_submenu_page(
        $prefix, /* parent slug */
        'Tickets', /* page title */
        'Submit Ticket', /* menu title */
        'hu_submit_tickets', /* capability */
        $prefix.'-tickets', /* slug */
        function() use ($pages_path){ /*require $pages_path.'tickets/submit.php';*/ } /* callback */
    );

    add_submenu_page(
        $prefix, /* parent slug */
        'All Tickets', /* page title */
        'View Tickets', /* menu title */
        'hu_submit_tickets', /* capability */
        "$prefix-my-tickets", /* slug */
        function() use ($pages_path){ include $pages_path.'tickets/my_tickets.php'; } /* callback */
    );

    add_submenu_page(
        '', /* parent slug */ /* Making this an empty string prevents the page from showing up on the admin menu */
        'Ticket', /* page title */
        'Ticket', /* menu title */
        'hu_submit_tickets', /* capability */
        "$prefix-ticket", /* slug */
        function() use ($pages_path){ include $pages_path.'tickets/ticket.php'; } /* callback */
    );

});
