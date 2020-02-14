<?php

/* Logs a user into WordPress if they have valid Thou Shalt Not Pass credentials.
A user will be created in WordPress DB for the Thou Shalt Not Pass user if the user does not already exist in the DB

parameters
@username: thou shalt not pass username
@api_key: thou shalt not pass password
*/

if(strtolower($_SERVER['REQUEST_METHOD']) == "options") {
    return;
}
if(strtolower($_SERVER['REQUEST_METHOD']) !== "post") {
    throw new Exception('Invalid request method');
}

const USE_SSL = true; // !!! Should only be False on dev sites !!!

const DAY_IN_SECONDS = 86400;
const HOUR_IN_SECONDS = 3600;

/* define ABSPATH */
$url_path = parse_url($_SERVER['REQUEST_URI'])['path']; /* remove the query string */
$abspath_depth = substr_count($url_path, '/');
if (empty( $abspath_depth) )
    throw Exception('Unable to discover where ABSPATH should be');
define( 'ABSPATH', dirname(__FILE__, $abspath_depth) . '/' );

set_include_path(get_include_path() . PATH_SEPARATOR . ABSPATH);

require 'functions.php';
require 'login_functions.php';

// normal auth flow can be analyzed by looking at the code for wp_signon();
// https://developer.wordpress.org/reference/functions/wp_signon/
wp_constants();

function thou_shalt_not_pass_authentication(){

    #TODO update this when service-registry shows update domain
    #$services = json_decode( file_get_contents('https://service-registry.wordpressoverwatch.com/v1/services.json') ); // TODO cache this in the database
    #$notpass_api = $services->{'Thou Shalt Not Pass'} . '/v3';

    $notpass_api = 'http://thou-shalt-not-pass.hostingutilities.net/v3';

    $nonce = $notpass_api . '/mission_control_nonce.php'; // TODO I left off here

    if (!isset($_POST['username']) || !isset($_POST['api_key'])){
        throw new AuthenticationError('Missing POST Parameter(s)');
    }


    $auth_url = $notpass_api . '/verify';
    $res = httpPost($auth_url, array('username'=>$_POST['username'], 'api_key'=>$_POST['api_key'] ));
    $json = json_decode($res, true);

    if ( isset($json['error']) ){
        throw new AuthenticationError($json['message'] ?? 'Error authenticating');
    }

    switch ($json['type']){

        case 'master':
            return true;
            break;

        case 'reseller':
        case 'customer':
            foreach ($json['domains'] as $domain){
                if ($domain && parse_url($domain)['host'] == CURRENT_DOMAIN){
                    return true;
                }
            }
            break;

        case 'organization':
            throw new AuthenticationError('authenticating against organization keys is not supported. How did you get those credentials in the first place?');
            break;

        default:
            throw new AuthenticationError('Unknown type of API key');
    }
}

if (thou_shalt_not_pass_authentication()){

    $user_id = lookup_user_id();

    // Create the user in the WordPress DB if it does not exist
    // This is needed for authentication to work
    if ($user_id === false){
        if ( isset($POST['create_user_if_does_not_exist'] ) && ! $POST['create_user_if_does_not_exist'] ){
            exit('username does not exist');
        }
        $username = get_username();
        $email = get_email() ?: filter_var(str_replace('@', '', $_POST['username']), FILTER_SANITIZE_EMAIL).'@fake-emails.wp-overwatch.com';
        // We're never going to know the password stored in the WordPress DB, but that is alright
        // because we will only authenticate this user against thou shalt not pass and not the WordPress DB
        $password = wp_generate_password(30, true, true);
        $user_id = wp_insert_user( array('user_login'=>$username, 'user_pass'=>$password, 'user_email'=>$email, 'display_name'=>$_POST['username']) );
    }

    wp_set_auth_cookie($user_id, false);
    if (isset($_POST['redirect'])){
        header('Location: '.$_POST['redirect']);
    }
}  else{
    http_response_code(403);
    exit('Authentication Failed');
}
