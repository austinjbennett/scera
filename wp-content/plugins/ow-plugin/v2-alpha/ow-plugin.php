<?php

require 'hu-dev-loader/hu-dev-loader.php';

if (is_admin()){

    require 'plugin-update-checker/plugin-update-checker.php';

    function reset_cloudways_permissions(){
        $args = [ 'http' => [
                'method'  => 'POST',
                'content' => http_build_query([
                    'api_key' => 'xxx',
                    'domain' => 'silverhorsecare.com'
                ])
        ] ];
        file_get_contents('https://cloudways-permissions.hostingutilities.net/v1/reset.php', false, stream_context_create($args));
    }
    // add_filter('upgrader_pre_install', 'reset_cloudways_permissions', 0, 5);
    // add_filter('upgrader_package_options', 'reset_cloudways_permissions', 0, 5);

    global $myUpdateChecker;
    $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
    	'https://bitbucket.org/wordpress_overwatch/ow-plugin',
    	HU_PLUGIN_FILE, //Full path to the main plugin file or functions.php.
    	'hosting-utilities'
    );

    //Optional: If you're using a private repository, create an OAuth consumer
    //and set the authentication credentials like this:
    //Note: For now you need to check "This is a private consumer" when
    //creating the consumer to work around #134:
    // https://github.com/YahnisElsts/plugin-update-checker/issues/134
    $myUpdateChecker->setAuthentication(array(
    	'consumer_key' => 'YbhA3SxKwSF297M3pG',
    	'consumer_secret' => 'GLSY2p4ATQL3eJJXXJygScVLJfh3RWGW',
    ));

    $myUpdateChecker->scheduler->checkPeriod = 2; // check every 2 hours for updates

    //Optional: Set the branch that contains the stable release.
    //$myUpdateChecker->setBranch('master');

    # TODO create a way of turning this feature off (in case a website isn't compatable with an update)
    // Auto update this plugin (other update methods don't pick up this plugin because PUC doesn't directly change the `update_plugins` DB option)
    add_filter( 'auto_update_plugin', function($update, $item) {
        if ( function_exists('hu_branding') && $item->slug == hu_branding('url_friendly') ) {
            return true; // Always update current plugin
        } else {
            return $update;
        }
    }, 10, 2 );

    add_action( 'pre_auto_update', function($type, $item, $context){

        file_put_contents(ABSPATH . 'updates.log', 'pre_auto_update: '.$item->slug.PHP_EOL , FILE_APPEND | LOCK_EX);

     }, 10, 3);



    function ow_update_self_transient_fix($transient){
        global $myUpdateChecker;

        // $transient->test = 'testing';
        //
        // if (isset($_GET['dev'])){
        //     exit(var_dump($transient));
        // }

        // if ( ! class_exists('WP_Upgrader') || ! WP_Upgrader::create_lock( 'auto_updater' )){
        //     return $transient;
        // }

        static $bitbucket_response = false;
        if ($bitbucket_response === false){
            $bitbucket_response = $myUpdateChecker->getUpdate(); // returns null if no updates are available
        }


        // Checks if an update is needed, and if the transient has a checked property, and it's active
        $basename = plugin_basename(HU_PLUGIN_FILE);
        if( $bitbucket_response && property_exists( $transient, 'checked') && $transient->checked && is_plugin_active($basename) ) {
            $plugin = array( // setup our plugin info
              'url' => 'https://wp-overwatch.com',
              'slug' => current( explode('/', $basename ) ),
              'package' => $bitbucket_response->download_url,
              'new_version' => $bitbucket_response->version,
              'autoupdate' => true,
              'plugin' => 'ow-plugin/ow-plugin.php',
              'tested' => '5.4'
            );
            $transient->response[ $basename ] = (object) $plugin;
        }

        return $transient;

    }
    //add_filter( 'pre_set_site_transient_update_plugins', 'ow_update_self_transient_fix', 10, 1 );
    add_filter( 'pre_add_option__site_transient_update_plugins', 'ow_update_self_transient_fix', 10, 1 );
    add_filter( 'pre_update_option__site_transient_update_plugins', 'ow_update_self_transient_fix', 10, 1 );

    function always_auto_update_ow_plugin ( $update, $item ) {

        if ( $item->slug == 'ow-plugin' )
            return true;

        return $update;
    }
    add_filter( 'auto_update_plugin', 'always_auto_update_ow_plugin', 30, 2 ); // iThemes uses priority 30

    // if (isset( $_GET['update-test'] )){
    //
    //     add_action('admin_init', function(){
    //
    //             //wp_maybe_auto_update();
    //             include_once ABSPATH . 'wp-admin/includes/class-wp-automatic-updater.php';
    //             include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    //             $updater = new WP_Automatic_Updater();
    //
    //
    //             //if ( ! WP_Upgrader::create_lock( 'auto_updater' ) ) {
    //
    //             if ( $updater->is_disabled() ) {
    //                 return;
    //             }
    //
    //             if ( ! is_main_network() || ! is_main_site() ) {
    //                 return;
    //             }
    //
    //             // if ( ! WP_Upgrader::create_lock( 'auto_updater' ) ) {
    //             //     return;
    //             // }
    //
    //             // Don't automatically run these thins, as we'll handle it ourselves
    //             remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );
    //             remove_action( 'upgrader_process_complete', 'wp_version_check' );
    //             remove_action( 'upgrader_process_complete', 'wp_update_plugins' );
    //             remove_action( 'upgrader_process_complete', 'wp_update_themes' );
    //
    //             // Next, Plugins
    //             wp_update_plugins(); // Check for Plugin updates
    //             $plugin_updates = get_site_transient( 'update_plugins' );
    //             if ( $plugin_updates && ! empty( $plugin_updates->response ) ) {
    //                 foreach ( $plugin_updates->response as $plugin ) {
    //                     var_dump($plugin->slug);
    //                     $updater->update( 'plugin', $plugin );
    //                 }
    //                 // Force refresh of plugin update information
    //                 wp_clean_plugins_cache();
    //             }
    //
    //             WP_Upgrader::release_lock( 'auto_updater' );
    //
    //             // $res = $updater->run();
    //             // var_dump($res);
    //             exit('done updating');
    //             //$plugin_updates = get_site_transient( 'update_plugins' );
    //             //var_dump($plugin_updates->response['ow-plugin/ow-plugin.php']);
    //             //}
    //
    //         });
    // }

}
