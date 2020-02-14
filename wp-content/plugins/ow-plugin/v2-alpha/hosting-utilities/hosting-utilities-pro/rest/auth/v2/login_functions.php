<?php

class AuthenticationError extends Exception{

    // public function __toString() {
    //
    //     // Prevent detailed authentication messages from being seen if
    //     if (isset('WP_DEBUG') || WP_DEBUG)
    //         return 'Authentication failed';
    //
    //     return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    // }
}

$wp_config = file_get_contents( ABSPATH.'wp-config.php' );

// eval() requires to include the beginning php tag
// we need to consider that the php can now be <? instead of <?php
$start = strpos($wp_config, '<?') + strlen('<?');
if ( strpos($wp_config, 'php', $start) === $start ){
  $start += strlen('php');
}

// we want to remove everything after the "That's all, stop editing" comment
$needle = '/* That\'s all, stop editing! Happy blogging. */';
$cutoff = strpos($wp_config, $needle);
if ($cutoff){
    $length = $cutoff - $start;
    $wp_config = substr( $wp_config, $start, $length );
} else {
    // at the very least make sure we don't include wp-settings.php which loads all of WordPress
    $wp_config = str_replace('require_once', '', $wp_config);
}

eval($wp_config);

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

function apply_filters($tag, $val){ return $val; }

function get_option( $option, $default=false ) {
    global $mysqli, $table_prefix;

    $options_table = $table_prefix.'options';

    $stmt = $mysqli->prepare("SELECT option_value FROM $options_table WHERE option_name = ? LIMIT 1");
    $stmt->bind_param( 's', $option );
    $stmt->execute();
    $option_value = $stmt->get_result()->fetch_row()[0];
    $stmt->close();

    if (empty($option_value) && func_num_args() > 1)
        $option_value = $default;

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
        define( 'CURRENT_DOMAIN', parse_url($siteurl)['host'] ); /* saving the siteurl in a constant to avoid calls to the DB */

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

function endsWith($haystack,$needle){ // case insensitive version
    $expectedPosition = strlen($haystack) - strlen($needle);
    return strripos($haystack, $needle, 0) === $expectedPosition;
}

function get_username(){
    #TODO can we get the company name from info.wordpressoverwatch.com (wpoverwatch or whitelabeled name of reseller/agency name instead of "-externally-authenticated")?
    $suffix = '-externally-authenticated';
    if (isset($_POST['email'])){
        if (endsWith($_POST['email'], '@sitesmash.com'))
            $suffix = '-sitesmash';
        if (endsWith($_POST['email'], '@sebodev.com'))
            $suffix = '-sebodev';
        if (endsWith($_POST['email'], '@wordpressoverwatch.com') || endsWith($_POST['email'], '@wp-overwatch.com'))
            $suffix = '-wpoverwatch';
    }
    $username = $_POST['username'] . $suffix;
    return $username;
}

function get_email(){
    return $_POST['email'] ?? null;
}

/* if the email was given, lookup the user ID by email, otherwise use the username */
/* Returns true/false */
function lookup_user_id(){
    global $mysqli, $table_prefix;

    $users_table = $table_prefix.'users';

    $email = get_email();

    if (! empty($email)){

        $stmt = $mysqli->prepare("SELECT ID FROM $users_table WHERE user_email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_row()[0];
        $stmt->close();

    } else{

        $username = get_username();

        $stmt = $mysqli->prepare("SELECT ID FROM $users_table WHERE user_login = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_row()[0];
        $stmt->close();

    }

    if ($result === null)
        return false;

    return $result;
}
function get_userdata($user_id){
    global $mysqli, $table_prefix;

    if ( ! is_numeric($user_id)){
        throw new AuthenticationError('Failed due to an invalid user ID');
    }

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

}

function httpPost($url, $data){
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}
