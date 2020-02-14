<?php

const USE_SSL = true;

const DAY_IN_SECONDS = 86400;
const HOUR_IN_SECONDS = 3600;
define( 'ABSPATH', dirname(__FILE__, 7) . '/' ); # This needs to change if this file is ever moved
set_include_path(get_include_path() . PATH_SEPARATOR . ABSPATH);
require 'functions.php';

require ABSPATH.'wp-includes/cache.php';
$wp_object_cache = new WP_Object_Cache();
function is_multisite(){return false;}
require ABSPATH.'wp-includes/class-wp-user.php';


$wp_config = file_get_contents( ABSPATH.'wp-config.php' );

/* strip items from the wp-config file that are known to cause problems */
str_replace($wp_config, "'wp-salt.php'", "'".ABSPATH."wp-salt.php'");
str_replace($wp_config, '"wp-salt.php"', "'".ABSPATH."wp-salt.php'");

// /* actually this just generates a notice */
// /* remove the line cotnaining the constant DISALLOW_FILE_EDIT (otherwise we get errors about it getting redefined) */
// $file_edit_pos = strpos($wp_config, 'DISALLOW_FILE_EDIT');
// if ($file_edit_pos !== false){
//     $start_pos = strrpos($wp_config, '\n', $file_edit_pos); /* notice the extra r in strrpos, this one is in reverse */
//     $end_pos = strpos($wp_config, '\n', $file_edit_pos);
//     $end_string = substr($wp_config, $end_pos);
//     $wp_config = substr($wp_config, 0, $start_pos) . $end_string;
// }

// eval() requires to include the beginning php tag
// we need to consider that the php can now be <? instead of <?php
$start = strpos($wp_config, '<?') + strlen('<?');
if ( strpos($wp_config, 'php', $start) === $start ){
  $start += strlen('php');
}

// we want to remove everything after the "That's all, stop editing" comment
$needle = "/* That's all, stop editing! Happy blogging. */";
$length = strpos($wp_config, $needle) - $start;
$wp_config = substr( $wp_config, $start, $length );

eval($wp_config);

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function apply_filters($tag, $val){ return $val; }

function get_option( $option, $default=false ) {
    global $mysqli, $table_prefix;
    $passed_default = func_num_args() > 1;

    $options_table = $table_prefix.'options';

    $stmt = $mysqli->prepare("SELECT option_value FROM $options_table WHERE option_name = ? LIMIT 1");
    $stmt->bind_param( 's', $option );
    $stmt->execute();
    $option_value = $stmt->get_result()->fetch_row()[0];
    $stmt->close();

    $option_value = rtrim( $option_value, '/\\' ); // from the untrailingslashit function
    return $option_value;
}

function wp_constants() {
        global $blog_id;

        if ( ! isset($blog_id) )
                $blog_id = 1;

        // Add define('WP_DEBUG', true); to wp-config.php to enable display of notices during development.
        if ( !defined('WP_DEBUG') )
                define( 'WP_DEBUG', false );

        // Add define('WP_DEBUG_DISPLAY', null); to wp-config.php use the globally configured setting for
        // display_errors and not force errors to be displayed. Use false to force display_errors off.
        if ( !defined('WP_DEBUG_DISPLAY') )
                define( 'WP_DEBUG_DISPLAY', true );

        // Add define('WP_DEBUG_LOG', true); to enable error logging to wp-content/debug.log.
        if ( !defined('WP_DEBUG_LOG') )
                define('WP_DEBUG_LOG', false);
        if ( !defined('WP_CACHE') )
            define('WP_CACHE', false);

        // Add define('SCRIPT_DEBUG', true); to wp-config.php to enable loading of non-minified,
        // non-concatenated scripts and stylesheets.
        if ( ! defined( 'SCRIPT_DEBUG' ) )
            define('SCRIPT_DEBUG', false);


        $siteurl = get_option( 'siteurl' );

        if ( !defined('WP_CONTENT_DIR') )
            define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );


        if ( !defined('WP_CONTENT_URL') )
            define( 'WP_CONTENT_URL', $siteurl . '/wp-content'); // full url - WP_CONTENT_DIR is defined further up

        /**
         * Allows for the plugins directory to be moved from the default location.
         *
         */
        if ( !defined('WP_PLUGIN_URL') )
            define( 'WP_PLUGIN_URL', WP_CONTENT_URL . '/plugins' ); // full url, no trailing slash


        /**
         * Used to guarantee unique hash cookies
         *
         */
          if ( $siteurl )
              define( 'COOKIEHASH', md5( $siteurl ) );
          else
              define( 'COOKIEHASH', '' );

        if ( !defined('USER_COOKIE') )
            define('USER_COOKIE', 'wordpressuser_' . COOKIEHASH);

        if ( !defined('PASS_COOKIE') )
            define('PASS_COOKIE', 'wordpresspass_' . COOKIEHASH);

        if ( !defined('AUTH_COOKIE') )
            define('AUTH_COOKIE', 'wordpress_' . COOKIEHASH);

        if ( !defined('SECURE_AUTH_COOKIE') )
            define('SECURE_AUTH_COOKIE', 'wordpress_sec_' . COOKIEHASH);

        if ( !defined('LOGGED_IN_COOKIE') )
            define('LOGGED_IN_COOKIE', 'wordpress_logged_in_' . COOKIEHASH);

        if ( !defined('TEST_COOKIE') )
            define('TEST_COOKIE', 'wordpress_test_cookie');

        if ( !defined('COOKIEPATH') )
            define('COOKIEPATH', preg_replace('|https?://[^/]+|i', '', get_option('home') . '/' ) );

        if ( !defined('SITECOOKIEPATH') )
            define('SITECOOKIEPATH', preg_replace('|https?://[^/]+|i', '', $siteurl . '/' ) );

        if ( !defined('ADMIN_COOKIE_PATH') )
            define( 'ADMIN_COOKIE_PATH', SITECOOKIEPATH . 'wp-admin' );

        if ( !defined('PLUGINS_COOKIE_PATH') )
            define( 'PLUGINS_COOKIE_PATH', preg_replace('|https?://[^/]+|i', '', WP_PLUGIN_URL)  );

        if ( !defined('COOKIE_DOMAIN') )
            define('COOKIE_DOMAIN', null);
}

class My_WP_User{
    public $user_login;
    public $user_pass;
}

function get_username(){
    return 'auto-generated-test-tmp-7'; # TODO ###########################################################################################
}
/* look up the user ID. Returns false if the user ID does not exist returns false*/
function lookup_user_id($name){
    global $mysqli, $table_prefix;

    $users_table = $table_prefix.'users';
    $username = $name;//?:get_username();

    $stmt = $mysqli->prepare("SELECT ID FROM $users_table WHERE user_login = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_row()[0];
    $stmt->close();

    if ($result === null)
        return false;

    return $result;
}
// function get_userdata( $user_id ) {
//     $user = new My_WP_User();
//     $user->user_login = get_username();
//     $user->user_pass = 'auto-generated'; # TODO OOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO
//     $user->ID = $user_id;
//     return $user;
// }

function get_userdata( $user_id ) {
    global $mysqli, $table_prefix;

    $users_table = $table_prefix.'users';

    $stmt = $mysqli->prepare("SELECT * FROM $users_table WHERE ID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_object('My_WP_User');
    $stmt->close();

    if ($result === null)
        return false;

    return $result;
}

// function get_userdata( $user_id ) {
//     $field = 'id';
//     //$userdata = WP_User::get_data_by( $field, $user_id );
//     $userdata = get_user_by_id($user_id);
//
//     if ( !$userdata )
//             return false;
//
//     //$user = new WP_User;
//     //$user->init( $userdata );
//
//     return $userdata;
//     //return $user;
// }

function wp_generate_auth_cookie( $user_id, $expiration, $scheme = 'auth', $token = '' ) {
    $user = get_userdata($user_id);

    if ( ! $user ) {
        return '';
    }

    if ( ! $token ) {
        $manager = WP_Session_Tokens::get_instance( $user_id );
        $token = $manager->create( $expiration );
    }

    $pass_frag = substr($user->user_pass, 8, 4);

    $key = wp_hash( $user->user_login . '|' . $pass_frag . '|' . $expiration . '|' . $token, $scheme );

    // If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
    $algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
    $hash = hash_hmac( $algo, $user->user_login . '|' . $expiration . '|' . $token, $key );

    $cookie = $user->user_login . '|' . $expiration . '|' . $token . '|' . $hash;

    return $cookie;
}

function wp_set_auth_cookie( $user_id, $remember = false ) {
        if ( $remember ) {
                $expiration = time() + 14 * DAY_IN_SECONDS;

                /*
                 * Ensure the browser will continue to send the cookie after the expiration time is reached.
                 * Needed for the login grace period in wp_validate_auth_cookie().
                 */
                $expire = $expiration + ( 12 * HOUR_IN_SECONDS );
        } else {
                /** This filter is documented in wp-includes/pluggable.php */
                $expiration = time() + 2 * DAY_IN_SECONDS;
                $expire = 0;
        }

        // Front-end cookie is secure when the auth cookie is secure and the site's home URL is forced HTTPS.
        $secure = $secure_logged_in_cookie = USE_SSL;

        if ( $secure ) {
                $auth_cookie_name = SECURE_AUTH_COOKIE;
                $scheme = 'secure_auth';
        } else {
                $auth_cookie_name = AUTH_COOKIE;
                $scheme = 'auth';
        }

        require ABSPATH."wp-includes/class-wp-session-tokens.php";
        require ABSPATH."wp-includes/class-wp-user-meta-session-tokens.php";
        $manager = WP_Session_Tokens::get_instance( $user_id );
        $token   = $manager->create( $expiration );

        $auth_cookie = wp_generate_auth_cookie( $user_id, $expiration, $scheme, $token );
        $logged_in_cookie = wp_generate_auth_cookie( $user_id, $expiration, 'logged_in', $token );

        setcookie($auth_cookie_name, $auth_cookie, $expire, PLUGINS_COOKIE_PATH, COOKIE_DOMAIN, $secure, true);
        setcookie($auth_cookie_name, $auth_cookie, $expire, ADMIN_COOKIE_PATH, COOKIE_DOMAIN, $secure, true);
        setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure_logged_in_cookie, true);
        if ( COOKIEPATH != SITECOOKIEPATH )
                setcookie(LOGGED_IN_COOKIE, $logged_in_cookie, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, $secure_logged_in_cookie, true);

        //var_dump(AUTH_COOKIE);
}

function wp_parse_auth_cookie($cookie = '', $scheme = '') {
    if ( empty($cookie) ) {
        switch ($scheme){
            case 'auth':
                $cookie_name = AUTH_COOKIE;
                break;
            case 'secure_auth':
                $cookie_name = SECURE_AUTH_COOKIE;
                break;
            case "logged_in":
                $cookie_name = LOGGED_IN_COOKIE;
                break;
            default:
                    if ( is_ssl() ) {
                        $cookie_name = SECURE_AUTH_COOKIE;
                        $scheme = 'secure_auth';
                    } else {
                        $cookie_name = AUTH_COOKIE;
                        $scheme = 'auth';
                    }
        }
        if ( empty($_COOKIE[$cookie_name]) ){
            exit('wp_parse_auth_cookie error 1');
            return false;
        }
        $cookie = $_COOKIE[$cookie_name];
    }
    $cookie_elements = explode('|', $cookie);
    if ( count( $cookie_elements ) !== 4 ) {
        exit('wp_parse_auth_cookie error 2');
        return false;
    }
    list( $username, $expiration, $token, $hmac ) = $cookie_elements;
    return compact( 'username', 'expiration', 'token', 'hmac', 'scheme' );
}

function wp_validate_auth_cookie($cookie = '', $scheme = '') {
    if ( ! $cookie_elements = wp_parse_auth_cookie($cookie, $scheme) ) {
            exit('auth_cookie_malformed');
            return false;
    }
    $scheme = $cookie_elements['scheme'];
    $username = $cookie_elements['username'];
    $hmac = $cookie_elements['hmac'];
    $token = $cookie_elements['token'];
    $expired = $expiration = $cookie_elements['expiration'];
    // Allow a grace period for POST and Ajax requests
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX || 'POST' == $_SERVER['REQUEST_METHOD'] ) {
            $expired += HOUR_IN_SECONDS;
    }
    // Quick check to see if an honest cookie has expired
    if ( $expired < time() ) {
            exit('auth_cookie_expired');
            return false;
    }
    $userID = lookup_user_id($username);
    $user = get_userdata($userID);
    if ( ! $user ) {
        exit('auth_cookie_bad_username');
        return false;
    }
    $pass_frag = substr($user->user_pass, 8, 4);
    $key = wp_hash( $username . '|' . $pass_frag . '|' . $expiration . '|' . $token, $scheme );
    // If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
    $algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
    $hash = hash_hmac( $algo, $username . '|' . $expiration . '|' . $token, $key );
    if ( ! hash_equals( $hash, $hmac ) ) {
            exit('auth_cookie_bad_hash');
            return false;
    }
    require ABSPATH."wp-includes/class-wp-session-tokens.php";
    require ABSPATH."wp-includes/class-wp-user-meta-session-tokens.php";
    $manager = WP_Session_Tokens::get_instance( $user->ID );
    if ( ! $manager->verify( $token ) ) {
            exit('auth_cookie_bad_session_token');
            return false;
    }
    // Ajax/POST grace period set above
    if ( $expiration < time() ) {
            $GLOBALS['login_grace_period'] = 1;
    }
    //auth_cookie_valid
    return $user->ID;
}


wp_constants();
$cookie_name = USE_SSL ? SECURE_AUTH_COOKIE : AUTH_COOKIE;
$scheme = USE_SSL ? 'secure_auth' : 'auth';
$res = isset($_COOKIE[$cookie_name]) && wp_validate_auth_cookie( $_COOKIE[$cookie_name], $scheme );
echo $res !== false ? 'true' : 'false';
