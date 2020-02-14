<?php

if(__FILE__ == $_SERVER['SCRIPT_FILENAME']) {
  exit("Cannot access this file directly");
}

/* Stops extraneous parts of WordPress from being loaded when WordPress is loaded */
if (! defined('WP_USE_THEMES') )
  define( 'WP_USE_THEMES', false );
if (! defined('COOKIE_DOMAIN') )
  define( 'COOKIE_DOMAIN', false );
if (! defined('DISABLE_WP_CRON') )
  define( 'DISABLE_WP_CRON', true );

const VALID_HU_DASHBOARD_CAPABILITIES = array('edit_dashboard', 'overwatch_dashboard');

/*
   Returns the token in the token file only if the user
   has the "edit_dashboard" capability (default role for all WordPress admins) or the capability "overwatch_dashboard"
   and if $website is set it must match the URL of the WordPress website
   Also accepts the argument $wpDir which is to be used if WordPress is installed in a sub-directory
*/
function get_hu_api_key($website=null, $wpDir="../wp-load.php"){
  $user = null;

  //$_SERVER['HTTP_HOST'] = $website; // For multi-site ONLY. Provide the
                                     // URL/blog you want to auth to.

  //load WordPress core if it's not already loaded
  if ( ! defined( 'ABSPATH' ) )
    require($wpDir);

  if ($website && ! (strpos(get_home_url(), $website)) ){
    exit("Website mismatch. You may only view the dashboard for ". $website);
  }

  /*Grab the current user or show a login form if they are not logged in */
  if ( is_user_logged_in() ) {
    $user = wp_get_current_user();
  } else{
    try{ hu_signin_form(); }
    finally{ exit(); }
  }

  /* Make sure the user has the correct permissions */
  if (! (hu_check_capabilities($user, VALID_HU_DASHBOARD_CAPABILITIES)) ){
    exit("Insufficent privelages");
  }

  return get_option('hu_admin_settings', array())['api_key'];
  // $api_key_path = __DIR__."/token";
  // if ( file_exists($api_key_path) ){
  //     $token = file_get_contents($api_key_path);
  //     return $token;
  //   } else {
  //       return get_option('hu_admin_settings', array())['api_key'];
  //   }
}

/* deprecated. Alias of get_hu_api_key */
function get_token() {
  return call_user_func_array("get_hu_api_key", func_get_args());
}

/* Returns true if the user has a capability in $caps, otherwise, returns false */
function hu_check_capabilities($user, $caps){
  foreach ($caps as $cap){
    if (user_can($user, $cap)){
      return true;
    }
  }
  return false;
}

/*
 Show a sign in form, and return true if the user successfully signs in, otherwise echo the error message
*/
function hu_signin_form(){
  ?>
  <style>
    body{
      font-family: "Roboto", "Open Sans", "sans-serif";
    }
    h1{
      padding: 1em;
      margin-left: .5em;
      margin-right: .5em;
      border-bottom: 1px solid black;

      font-family: "Open Sans", sans-serif;
      font-size: 1.8em;
      letter-spacing: -.01rem;
    }
    h2{
      text-transform: uppercase;
    }
    #form-container{
      max-width: 400px;
      margin: auto;
    }
    img{
      margin-top: 1em auto;
      display: block;
    }
    input{
      float: right;
    }
    input[type=submit]{
      background: #eee;
      border: none;
      padding: .5em .8em;
      background-image: linear-gradient(
        to right top,
        #eee 33%,
        #ddd 33%,
        #ddd 66%,
        #eee 66%
      );
      background-size: 3px 3px;
    }
    input[type=submit]:hover{
      cursor: pointer;
      background-image: linear-gradient(
        to right top,
        #eee 33%,
        #ccc 33%,
        #ccc 66%,
        #eee 66%
      );
    }

    input[type="checkbox"] {
     -webkit-appearance:none;/* Hides the default checkbox style */
     height: 20px;
     width: 20px;
     cursor:pointer;
     position:relative;
     -webkit-transition: .15s;
     background-color:#900;
    }

    input[type="checkbox"]:checked {
     background-color:green;
    }

    input[type="checkbox"]:not(:checked){
      width: 20px;
      height: 20px;
      background: #eee;
      border: 2px solid #ccc;
    }

    input[type="checkbox"]:before, input[type="checkbox"]:checked:before {
     position:absolute;
     top:0;
     left:0;
     width:100%;
     height:100%;
     line-height: 21px;
     text-align:center;
     color:#fff;
     /*content: '✘';*/
    }

    input[type="checkbox"]:checked:before {
     content: '✔';
    }

    input[type="checkbox"]:hover:before {
     background: rgba(255,255,255,0.3);
    }

    input[type="text"], input[type="password"]{
      border: 2px solid #ccc;
      padding-left: 3px;
    }

  </style>
  <h1>WP-Overwatch Dashboard</h1>
    <div id="form-container">
    <h2>Log In</h2>
    <?php wp_login_form(); ?>
    <img src='http://wordpressoverwatch.com/logo-small.png' />
  </div>
  <?php
}
