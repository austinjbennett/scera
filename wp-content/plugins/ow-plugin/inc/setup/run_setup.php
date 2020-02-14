<?php
/*
	Import this module to run the setup process
	If you only want to run specific sections of the setup process, then instead import setup.php
	and call the appropriate function
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'modify_this_when_changes_are_made.php';
require_once 'setup.php';

function ow_setup_run(){
	$errors = array();

	try {
		ow_htaccess_rewrites();
	} catch (Exception $e) {
		$errors[] = $e;
	}

	try {
		ow_store_branding_info();
	} catch (Exception $e) {
		$errors[] = $e;
	}

	try {
		ow_maintenance_drop_in();
	} catch (Exception $e) {
		$errors[] = $e;
	}

	try {
		ow_store_logo();
	} catch (Exception $e) {
		$errors[] = $e;
	}

	try {
		ow_mu_plugin();
	} catch (Exception $e) {
		$errors[] = $e;
	}

	try {
		ow_add_capabilities();
	} catch (Exception $e) {
		$errors[] = $e;
	}

	$stages = ow_mark_setup_stage_as_successful();
	$stages['success'] = ( !in_array(-1, $stages) && !in_array(false, $stages) && empty($errors) );

	update_option( 'ow_setup_versions', $stages, 'no' );

	//we just return the error so that AJAX calls can display the errors
	return array('results'=>$stages, 'errors'=>$errors);
}

return ow_setup_run();
