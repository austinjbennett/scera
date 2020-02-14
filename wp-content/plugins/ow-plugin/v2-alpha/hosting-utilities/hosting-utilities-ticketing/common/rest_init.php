<?php
/*
For use in REST calls loaded outside of the WP environment (WP's built-in REST functionality is terrible performance-wise),
After including this file, call the res_init function. See that function for more details.
version: 1.0
*/

// prevent direct access
if (basename($_SERVER['PHP_SELF']) === basename(__FILE__)) {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    exit;
}

function hu_load_wp(){

    if( ! @include_once('../../../../../../../wp-load.php')) {
        if( ! @include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php')
        /* Make sure we loaded the correct WordPress installation
        (this is to stop the situation where the installation we want is in a subfolder,
        and there is a different installation in the document_root) */
        || parse_url( get_option('home') )['host'] !== $_SERVER['SERVER_NAME']
    ) {
        throw new Exception('unable to find wp-load.php');
    }
}

}

/* given an array of roles and/or capapabilities, verifies if the current user has one of those capabilities  */
function hu_rest_api_verify_permissions($roles_or_capabilities){
    $roles_or_capabilities = (array)$roles_or_capabilities;

    if ( $user = wp_get_current_user() ) {

        foreach ($roles_or_capabilities as $cap){
            if (user_can($user, $cap)){
                return true;
            }
        }

        http_response_code(403);
        exit('You are not allowed to perform this action');

    } else {
        http_response_code(401);
        show_error("Please login first");
        exit;
    }
}

/*
  Initializes the environment
  All of the options below are optional, and unfortunately against bad API design they are not all compatable with each other.
  If WP was not loaded, then none of the other options will work
  @$options['wp_load_mode']: what to load. Can be: 'no_plugins'|'shortinit'|'dont_load'
  @$options['approved_permissions']: array of roles and/or capabilities the current user must have
  @$options['approved_permissions']['nonce_name']: The name of a nonce field used by `wp_create_nonce` or one of the other nonce functions
  @$options['approved_permissions']['nonce']: The nonce value
*/
function rest_init($options){
    switch($options['wp_load_mode']){
        case 'no_plugins':

            define('DOING_AJAX', true);
            define('WP_USE_THEMES', false);
            define('HU_MU_PLUGIN_REST_REQUEST', true);

            hu_load_wp();

        case 'shortinit':

            define('DOING_AJAX', true);
            define('SHORTINIT', true);
            //add_filter('enable_loading_advanced_cache_dropin', function(){return false;}, 0, 9999999); /* no need to load caching code when we're not loading enough of WP for it to be used anywhere */

            hu_load_wp();

            break;
        case 'dont_load':
            break;
        default:
            exit('rest_init wp_load_mode not supported');
    }

    /* make sure we have permissions perform this action */
    /* requires wp_load_mode to be no_plugins */
    if ( isset($options['approved_permissions']) ){
        hu_rest_api_verify_permissions( $options['approved_permissions'] );
    }

    if ( isset($options['verify_nonce']) ){ /* does not work when wp_load_mode is shortinit or dont_load */
        if (! wp_verify_nonce( $options['verify_nonce']['nonce'], $options['verify_nonce']['nonce_name'] )){
            die('Failed to validate nonce');
        }
    }

    /* The helper functions are only usable when WordPress exists (ie ABSPATH is defined) */
    if ( defined('ABSPATH') ){
        if ( ! defined('HU_COMMON_FUNCTIONS_LOADED') ){
            include_once 'helper_functions.php';
            define('HU_COMMON_FUNCTIONS_LOADED', true);
        }
    }

}
