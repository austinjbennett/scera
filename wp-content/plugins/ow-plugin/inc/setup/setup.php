<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once 'modify_this_when_changes_are_made.php';

/*
    Used to mark a stage in the setup process as complete
	Pass in null(the default) to get an array of each stage and the version they where updated to
*/
function ow_mark_setup_stage_as_successful($stage=null){

	static $stages = array(
		'new_stages' => OW_SETUP_VERSIONING['NEW_SETUP_STAGES'],
		'htaccess' => false,
		'maintenance_dropin' => false,
		'branding_info' => false,
		'logo' => false,
		'add_caps' => false,
		'mu_plugin' => false
	);
	static $versions = array(
		'new_stages' => OW_SETUP_VERSIONING['NEW_SETUP_STAGES'],
		'htaccess' => OW_SETUP_VERSIONING['HTACCESS'],
		'maintenance_dropin' => OW_SETUP_VERSIONING['MAINTENANCE_DROPIN'],
		'branding_info' => OW_SETUP_VERSIONING['BRANDING_INFO'],
		'logo' => 1,
		'add_caps' => OW_SETUP_VERSIONING['ADD_CAPABILITIES'],
		'mu_plugin' => OW_SETUP_VERSIONING['MU_PLUGIN']
	);

	if ($stage != null)
		$stages[$stage] = $versions[$stage];

	return $stages;
}

/*
  Based on the insert_with_markers file in wp-admin/includes/misc.php
  Used to add data to htaccess
*/
function insert_with_markers_top_of_file( $filename, $marker, $insertion ) {
    if ( ! file_exists( $filename ) ) {
        if ( ! is_writable( dirname( $filename ) ) ) {
            return false;
        }
        if ( ! touch( $filename ) ) {
            return false;
        }
    } elseif ( ! is_writeable( $filename ) ) {
        return false;
    }

    if ( ! is_array( $insertion ) ) {
        $insertion = explode( "\n", $insertion );
    }

    $start_marker = "# BEGIN {$marker}";
    $end_marker   = "# END {$marker}";

    $fp = fopen( $filename, 'r+' );
    if ( ! $fp ) {
        return false;
    }

    // Attempt to get a lock. If the filesystem supports locking, this will block until the lock is acquired.
    flock( $fp, LOCK_EX );

    $lines = array();
    while ( ! feof( $fp ) ) {
        $lines[] = rtrim( fgets( $fp ), "\r\n" );
    }

    // Split out the existing file into the preceding lines, and those that appear after the marker
    $pre_lines = $post_lines = $existing_lines = array();
    $found_marker = $found_end_marker = false;
    foreach ( $lines as $line ) {
        if ( ! $found_marker && false !== strpos( $line, $start_marker ) ) {
            $found_marker = true;
            continue;
        } elseif ( ! $found_end_marker && false !== strpos( $line, $end_marker ) ) {
            $found_end_marker = true;
            continue;
        }
        if ( ! $found_marker ) {
            $pre_lines[] = $line;
        } elseif ( $found_marker && $found_end_marker ) {
            $post_lines[] = $line;
        } else {
            $existing_lines[] = $line;
        }
    }

    // Check to see if there was a change
    if ( $existing_lines === $insertion ) {
        flock( $fp, LOCK_UN );
        fclose( $fp );

				ow_mark_setup_stage_as_successful('htaccess');
        return true;
    }

		/* hacking: add a newline after our insertion */
		if (isset($pre_lines[0]) && ! (substr($pre_lines[0], 0, strlen("\n")) == "\n") ) {
			array_unshift($pre_lines, "");
		}

    // Generate the new file data
    /* hacking: changing the order around, so that our rules are always at the top of the file */
    $new_file_data = implode( "\n", array_merge(
        array( $start_marker ),
        $insertion,
        array( $end_marker ),
        $pre_lines,
        $post_lines
    ) );

    // Write to the start of the file, and truncate it to that length
    fseek( $fp, 0 );
    $bytes = fwrite( $fp, $new_file_data );
    if ( $bytes ) {
        ftruncate( $fp, ftell( $fp ) );
    }
    fflush( $fp );
    flock( $fp, LOCK_UN );
    fclose( $fp );

		if ($bytes)
			ow_mark_setup_stage_as_successful('htaccess');
    return (bool) $bytes;
}


/*
 * Store whitelabeled info from info.wordpressoverwatch.com
*/
function ow_store_branding_info(){
	global $ow_wpoverwatch_branding_defaults;

	require_once OW_PLUGIN_PATH.'inc/wp_authenticator/auth.php';
	$api_key = get_token(null, $wpDir="../../../../wp-load.php");

	$resp = wp_remote_get("https://info.wordpressoverwatch.com/wp-json/acf/v3/websites/?token=$api_key");

	if ( ! $resp || is_wp_error($resp) )
	  return false;

	$json = $resp['body'];
    $data = json_decode($json, true);

	if ( !$data || isset($data['data']['status']) && $data['data']['status'] >= 400 ) {
	   	return false;
    }

	$wl_info = $data[0]['acf']['whitelabel'] ?: $ow_wpoverwatch_branding_defaults;

	update_option('ow_whitelabel_info', $wl_info);
	ow_mark_setup_stage_as_successful('branding_info');
}

/*
	Store's the logo in the `ow_site_logo` variable
*/
function ow_store_logo(){
	require_once OW_PLUGIN_PATH.'inc/wp_authenticator/auth.php';

	$api_key = get_token(null, $wpDir="../../../../wp-load.php");

	$resp = wp_remote_get("https://info.wordpressoverwatch.com/wp-json/acf/v3/websites/?token=$api_key");
	if ( ! $resp || is_wp_error($resp) )
	  return false;

	$json = $resp['body'];
  $data = json_decode($json, true);

	if ( !$data || isset($data['data']['status']) && $data['data']['status'] >= 400 ) {
   	return false;
  }

	$logo_url = $data[0]['acf']['logo'];
	if ($logo_url) {
		$logo = "<a href='". home_url() ."' class=custom-logo-link rel=home itemprop=url><img src=$logo_url class=custom-logo alt=logo itemprop=logo></a>";
	} else {
		/* custom_logo is only available for some themes.
		If supported by the theme, there will be an option to set a logo under customizations > site identity */
		if (has_custom_logo()){
			$logo = get_custom_logo();
		} else{
			return false;
		}
	}

	update_option( 'ow_site_logo', $logo, false );
	ow_mark_setup_stage_as_successful( 'logo' );
}

/*
 * Add a maintenance drop-in file if it doesn't exist
 * The drop-in will cause override WP behaviour to use locakout/upgrading_page.php as a maintenance page
*/
function ow_maintenance_drop_in(){
	$content = '<?php $maintenance_page = "' . OW_PLUGIN_PATH . 'inc/lockout_notices/updating_page.php";';
	$content .= <<<'CODE'

if (file_exists($maintenance_page)){
  include $maintenance_page;
} else{
    ?><h2>Maintenance Mode</h2><p>The website will be back up in a few moments</p><?php
}
CODE;

	$maintenance_dropin_location = WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'maintenance.php';
	$fp = fopen($maintenance_dropin_location, "wb");
	fwrite($fp, $content);
	fclose($fp);
	ow_mark_setup_stage_as_successful('maintenance_dropin');
}

/*
 * Add our rewrite rules to the htaccess file
*/
function ow_htaccess_rewrites(){
	$home_path = function_exists('get_home_path') ? get_home_path() : ABSPATH;
	$htaccess_file = $home_path . '.htaccess';
	$htaccess_rules = array(
		'RewriteEngine On',
	    'RewriteRule ^dashboard/(.*) wp-content/plugins/ow-plugin/pages/site_health/dashboard/$1',
		'RewriteRule ^tickets/(.*) wp-content/plugins/ow-plugin/pages/tickets/$1',
		'RewriteRule ^myplan/(.*) wp-content/plugins/ow-plugin/pages/plan/$1',
		'RewriteRule ^wp-authenticator/(.*) wp-content/plugins/ow-plugin/inc/wp_authenticator/$1',
	);
	insert_with_markers_top_of_file( $htaccess_file, 'WPOverwatch', $htaccess_rules );
	ow_mark_setup_stage_as_successful('htaccess');
}

function ow_mu_plugin(){

	$content = '<?php $mu_page = "' . OW_PLUGIN_PATH . 'inc/mu_plugin/mu_plugin.php";';
	$content .= <<<'CODE'
/**
 * Plugin Name: WordPress Customizations
 * Description: Performs modifications that have to be made before the hosting utilities plugin runs. This plugin will short circuit itself and not do anything, if the hosting utilities plugin is not active.
 */

if (file_exists($mu_page)){
	include $mu_page;
}
CODE;

	$mu_plugin_directory = ABSPATH . 'wp-content'. DIRECTORY_SEPARATOR .'mu-plugins';

	if ( ! file_exists($mu_plugin_directory) ) {
	    mkdir($mu_plugin_directory, 0775);
	}

	/* remove old mu plugin if it exists */
	$old_mu_plugin_location = $mu_plugin_directory . DIRECTORY_SEPARATOR . 'admin_area_modifications.php';
	if ( file_exists($old_mu_plugin_location) ) {
		unlink($old_mu_plugin_location);
	}

	$mu_plugin_location = $mu_plugin_directory . DIRECTORY_SEPARATOR . 'hosting_utility_modifications.php';

	$fp = fopen($mu_plugin_location, "wb");
	fwrite($fp, $content);
	fclose($fp);

	ow_mark_setup_stage_as_successful('mu_plugin');
}

/*
  adds the hu_submit_ticket capability to all of the editors, (hu stands for hosting utilities)
  so they can submit tickets too
*/
function ow_add_capabilities(){
	$editor_role = get_role( 'editor' );
	if ($editor_role)
		$editor_role->add_cap( 'hu_submit_tickets' );
	$admin_role = get_role( 'administrator' );
	$admin_role->add_cap( 'hu_submit_tickets' );
	ow_mark_setup_stage_as_successful( 'add_caps' );
}
