<?php

/*
 // Send a post request here to authenticate the current user and retrieve the required token for communicating with the reporting server
 // @args
 //  website = (optional) if passed in, we will validate the wordpress installation we are communicating with is for this website
 //  wordpress-dir = (optional) the directory to the wp-load.php file if different from the default "../wp-load.php". This can also be a query string paramater.
 //  format = (optional) Default is plain-text. Can also be set to json.
 // @returns
 //  If format is: 'plain-text'
 //    returns either the api-key or the string "Error: " followed by the error message
 //  If format is: 'json'
 //    returns {
 //      "apiUsername": The username for the api-key
 //      "apiKey": The Thou-Shalt-Not-Pass api-key
 //      "wpEmail": The email of the WordPress user who's logged in
 //      "wpUserId": The User ID of the WordPress user who's logged in
 //      "wpFirstName": The first name of the WordPress user who's logged in
 //      "wpLastName": The last name of the WordPress user who's logged in
 //    }
 //    or an error in the format {"error": "<ERROR MESSAGE>"}
*/

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: x-requested-with');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

$website = isset($_POST["website"]) ? $_POST["website"] : null;
if ($website == ""){
  $website = null;
}
$wpDir = isset($_POST["wordpress-dir"]) ? $_POST["wordpress-dir"] : (isset($_GET["wordpress-dir"]) ? $_GET["wordpress-dir"] : "../../../../");
if ($wpDir == ""){
  $wpDir = "../";
}
$wpDir = __dir__ . '/' . $wpDir;

/* Stops extraneous parts of WordPress from being loaded when WordPress is loaded */
define( 'WP_USE_THEMES', false );
define( 'COOKIE_DOMAIN', false );
define( 'DISABLE_WP_CRON', true );

const VALID_HU_DASHBOARD_CAPABILITIES = array('edit_dashboard', 'overwatch_dashboard');
$jsonOutput = isset($_POST['format']) && $_POST['format']==='json';

function show_error($message) {
  global $jsonOutput;
  if ($jsonOutput)
    echo json_encode(array('error'=>$message));
  else
    echo 'Error: '.$message;
}

/*
   Returns the token in the token file only if the user
   has the "edit_dashboard" capability (default role for all WordPress admins) or the capability "overwatch_dashboard"
   and if $website is set it must match the URL of the WordPress website
   Also accepts the argument $wpDir which is to be used if WordPress is installed in a sub-directory
*/
function echo_token($website, $wpDir) {
  global $jsonOutput;
  $user = null;

  //$_SERVER['HTTP_HOST'] = $website; // For multi-site ONLY. Provide the
                                     // URL/blog you want to auth to.

  //load WordPress core
  require($wpDir."wp-load.php");

  if ($website && ! (strpos(get_home_url(), $website)) ){
    http_response_code(403);
    show_error("Website mismatch. You may only view the dashboard for ". $website);
    return;
  }


  /*Grab the current user or show a login form if they are not logged in */
  if ( is_user_logged_in() ) {
    $user = wp_get_current_user();
  } else{
    http_response_code(401);
    show_error("User is not logged in");
    return;
  }

  if (! (check_capabilities($user, VALID_HU_DASHBOARD_CAPABILITIES)) ){
    http_response_code(403);
    show_error("Insufficent privelages");
    return;
  }

  $apiKey = get_option('hu_admin_settings', array())['api_key'];
  if (!$jsonOutput) {
    echo $apiKey;
  } else {
    echo json_encode(array(
      "apiUsername" => get_home_url(null, '', 'https'),
      "apiKey" => $apiKey,
      "wpEmail" => $user->user_email,
      "wpUserId" => $user->ID,
      "wpFirstName" => $user->user_firstname,
      "wpLastName" => $user->user_lastname,
    ));
  }
}

/* Returns true if the user has a capability in $caps, otherwise, returns false */
function check_capabilities($user, $caps){
  foreach ($caps as $cap){
    if (user_can($user, $cap)){
      return true;
    }
  }
  return false;
}

echo_token($website, $wpDir);
