<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
  Increment these variables when changes happen to the associated stage of the setup process
  To trigger the setup process to be rerun as part of the next plugin update
*/
const OW_SETUP_VERSIONING = array(
	'HTACCESS' => 2,
	'MAINTENANCE_DROPIN' => 3,
	'BRANDING_INFO' => 2,
    'LOGO' => 1,
	'MU_PLUGIN' => 2,
	'ADD_CAPABILITIES' => 1,

  /* Increment this one whenever a new stage is added to this array to alert us to the need to rerun the setup process.
    The run_setup_if_needed file will also need to be updated to detect changes to the new stage of the setup process */
	'NEW_SETUP_STAGES' => 2,
);
