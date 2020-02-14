<?php

/*
 // Send a post request here to update the token used to communicate with the reporting server
 // @args
 //  api_token = the password for using this API
 //  token_to_save = The new token the dashboard will need to use for communicating with the reporting server
 // @returns
 //  returns either the string "success" or a failure message
*/

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Headers: x-requested-with');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');

//The token that must be included with each request to use this API
define( 'REQUIRED_API_TOKEN', 'wp_authenticator_bD5zSzC4j4uzw' );

//Immediately stop if someone, possibly malicously, formed the post data wrong,
//or is not accessing the file with a post request (ie accessing the file directly or with a get request)
if (! (isset($_POST["api_token"]) && isset($_POST["token_to_save"]))){
  http_response_code(500);
  exit("Did not recieve the required post args.");
}

$api_token = $_POST["api_token"];
$token_to_save = $_POST["token_to_save"];

if ($api_token != REQUIRED_API_TOKEN){
  http_response_code(500);
  exit("Invalid token");
}

if ( file_put_contents ( "./token" , $token_to_save ) ){
  echo "success";
}
