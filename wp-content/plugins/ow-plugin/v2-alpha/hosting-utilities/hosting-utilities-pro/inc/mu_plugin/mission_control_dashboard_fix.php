<?php
// allows the mission control center to load the backend in an iframe

remove_action( 'login_init', 'send_frame_options_header', 10, 0 );
remove_action( 'admin_init', 'send_frame_options_header', 10, 0 );

// // Allow-from is not supported in chrome :(
// // Ticket #13839871 addressess a better alternative
//
// add_action( 'login_init', 'new_send_frame_options_header', 10, 0 );
// add_action( 'admin_init', 'new_send_frame_options_header', 10, 0 );
//
// function new_send_frame_options_header(){
//     header( 'X-Frame-Options: allow-from https://missioncontrol.wp-overwatch.com' );
// }
