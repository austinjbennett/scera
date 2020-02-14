<?php

require_once 'modify_this_when_changes_are_made.php';

$setup = get_option('ow_setup_versions');

if (isset($setup['success']) && $setup['success'] === -1){
  include_once 'setup_notice.php';
  add_action( 'admin_notices', 'ow_setup_error_notice' );
}

if ( setup_needed($setup) ) {

  function ow_run_setup(){
    $res = include_once 'run_setup.php'; #TODO make it so we are able to only run the stages of the setup process that need to be rerun

    if ($res['results']['success']){
      remove_action( 'admin_notices', 'ow_setup_error_notice' );
    }
  }
  add_action('init', 'ow_run_setup');

  /* If we already ran the init hook, run the setup immediately */
  if (did_action('init')){
      ow_run_setup();
  }
}

/* Returns if there is a change requiring the setup process to be run */
function setup_needed($versions){
  return (
     // If we've never attempted to run the setup, then no questions, let's just run it.
     $versions === false
     || (
       // If we ran the setup successfully, but there have been changes that would require us to rerun it, then rerun the setup process
          ( ! isset($versions['htaccess'])           || $versions['htaccess'] < OW_SETUP_VERSIONING['HTACCESS'] )
       || ( ! isset($versions['maintenance_dropin']) || $versions['maintenance_dropin'] < OW_SETUP_VERSIONING['MAINTENANCE_DROPIN'] )
       || ( ! isset($versions['branding_info'])      || $versions['branding_info'] < OW_SETUP_VERSIONING['BRANDING_INFO'] )
       || ( ! isset($versions['logo'])               || $versions['logo'] < OW_SETUP_VERSIONING['LOGO'] )
       || ( ! isset($versions['mu_plugin'])          || $versions['mu_plugin'] < OW_SETUP_VERSIONING['MU_PLUGIN'] )
       || ( ! isset($versions['add_caps'])           || $versions['add_caps'] < OW_SETUP_VERSIONING['ADD_CAPABILITIES'] )
       || ( ! isset($versions['new_stages'])         || $versions['new_stages'] < OW_SETUP_VERSIONING['NEW_SETUP_STAGES'] )
     )
   );
}
