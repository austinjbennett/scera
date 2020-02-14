<?php
/*
  Prevents some plugins for loading for non-wpoverwatch users.
  There is, however, a setting only visible to them that allows them to see these plugins.
*/

function endsWith($haystack, $needle){ // case insensitive version
  $expectedPosition = strlen($haystack) - strlen($needle);
  return strripos($haystack, $needle, 0) === $expectedPosition;
}

if( is_admin() ){

    function hu_hidden_plugins_notice() {
        global $pagenow;

        if ( $pagenow !== 'plugins.php' || isset($_COOKIE['show_hidden_plugins']) ){
            return;
        }

        $hosting_company = get_option('hu_whitelabel_info', false)['default'] ?? 'WP Overwatch';

        ?>
        <div id='message' class="notice notice-warning">
            <div style='padding:5px'>
                <details style="cursor:pointer"><summary>Settings for some active plugins are being hidden.</summary>
                    <p>Some of the things we take care of here at <?php echo $hosting_company ?> such as backups, caching, database optimizing, etc. (depends on the plan you are on) use plugins that don't need to load on the backend. To speed things up a little, we disable these plugins when you are viewing the backend. If you would like to see them, you may do so by going to <i><?php echo $hosting_company ?> > Options</i> and checking the option "Show hidden plugins for the next 12 hours"</p>
                </details>
            </div>
        </div>
        <?php
    }
    add_action( 'admin_notices', 'hu_hidden_plugins_notice' );

  function hu_unload_plugins( $plugins ){
    global $hu_removed_plugins, $pagenow;

    // Not really required, but it seems like this could help avoid some unforeseen problem down the road
    // WordPress uses the query parameter action for a lot of actions that can be taken within the admin area
    if ( isset( $_GET['action'] ) ){
        return $plugins;
    }

    /* On the plugins page, we only want the active plugins to show in the list, but we still want to prevent them from loading */
    if ( $pagenow === 'plugins.php' ){

        // TODO this breaks the ability to deactivate the unnecessary_plugins

        $dbt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5);
        $caller = isset($dbt[4]['function']) ? $dbt[4]['function'] : null;
        if ( $caller === 'is_plugin_active' ){

            return $plugins; /* return all of the plugins including the plugins that we prevented from loading */
        }

        // $dbt=debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 4);
        // $calling_file = isset($dbt[3]['file']) ? $dbt[3]['file'] : null;
        // if ( endsWith($calling_file, 'wp-admin'.DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'plugin.php') ){
        //     return $plugins; /* return all of the plugins including the plugins that we prevented from loading */
        // }
    }

    // DO NOT TRY to disable these plugins, these plugins add functionality to the backend that is desirable for all admin users
    // Disabling security is obviosuly a bad idea
    // 'ithemes-security-pro/ithemes-security-pro.php',
    // 'wordfence/wordfence.php',
    // I don't even want to test this out, I'd get in so much trouble if emails stopped working
    // 'wp-mail-smtp/wp_mail_smtp.php',
    // These plugins are needed for the cache to purge itself on website changes
    // 'Pagespeed Optimization' => 'above-the-fold-optimization/abovethefold.php',
    // 'Breeze' => 'breeze/breeze.php',
    // 'WP Rocket' => 'wp-rocket/wp-rocket.php',
    // Not sure if disabling these will break image resizing when an image is uploaded to an admin page. I need to test this, but I'm thinking probably so
    // 'Ewww Image Optimizer' => 'ewww-image-optimizer/ewww-image-optimizer.php',
    // 'WP Smush Pro' => 'wp-smush-pro/wp-smush.php',
    $unnecessary_plugins = array(
      'BackupBuddy' => 'backupbuddy/backupbuddy.php',
      'Really Simple SSL' => 'really-simple-ssl/rlrsssl-really-simple-ssl.php',
      'RVG Database Optimizer' => 'rvg-optimize-database/rvg-optimize-database.php',
      'WP Rollback' => 'wp-rollback/wp-rollback.php',
    );

    $hidden_plugin_pages = array(
        'rlrsssl_really_simple_ssl', 'rlrsssl_options', // Really Simple SSL
        'odb_settings_page', 'rvg-optimize-database', // Optimize Database after Deleting Revisions
        'pb_backupbuddy_backup', 'pb_backupbuddy_migrate_restore', 'pb_backupbuddy_destinations', 'pb_backupbuddy_server_tools', 'pb_backupbuddy_malware_scan', 'pb_backupbuddy_scheduling', 'pb_backupbuddy_settings' // BackupBuddy
    );

    $hu_removed_plugins = array();

    // Don't unload any plugins if we are viewing the settings page of one of the hidden plugins
    if ( (isset($_GET['page']) && in_array($_GET['page'], $hidden_plugin_pages) )
    || isset($_POST['option_page']) // for really simple ssl
    ){
        return $plugins;
    }

    // unload $unnecessary_plugins
    if ( ! isset($_COOKIE['show_hidden_plugins']) ){
        foreach ( $unnecessary_plugins as $plugin_name => $plugin_path ) {
            $k = array_search( $plugin_path, $plugins );
            if( $k !== false ){
                $hu_removed_plugins[$plugin_name] = $plugin_path;
                unset( $plugins[$k] );
            }
        }
    }

    return $plugins;
  }
  add_filter( 'option_active_plugins', 'hu_unload_plugins' );

  function mu_is_wpoverwatch_user(){
    $hu_admin_settings = get_option('hu_admin_settings', array());
    return (
              ! isset($hu_admin_settings['wpoverwatch_userid']) /* If a designated wpoverwatch user has not been set */
              || get_current_user_id() == $hu_admin_settings['wpoverwatch_userid'] /* or if we are that user */
              || endsWith( wp_get_current_user()->user_login, '-wpoverwatch' ) /* or if we are a wp-overwatch user */
              || isset($_GET['wpoverwatch']) /* alternatively the wpoverwatch paramater can be added to gain wpoverwatch privelages */
           );
  }
}
