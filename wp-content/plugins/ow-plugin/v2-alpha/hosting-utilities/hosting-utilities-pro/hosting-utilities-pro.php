<?php
/*
Plugin Name: Hosting Utilities Pro
Description: Includes all of the glue necessary to get more advanced functionality to work
Version: 1
*/
defined( 'ABSPATH' ) || exit();

/*
    define constants
*/
const HU_PRO_VERSION = '1';
define( 'HU_PRO_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
if (!defined('HU_PRO_PLUGIN_URL')) /* may have already been defined by our dev loader plugin */
    define( 'HU_PRO_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

/*
    load hosting utilities common library if it hasn't been loaded already
*/
if (! defined('HU_COMMON_FUNCTIONS_LOADED'))
    require 'common/init.php';

function hup_api_key_options($hu_admin_settings){
    ?>
    <tr valign="top">
        <th scope="row">WP-Overwatch API Key/Token</th>
        <td>
            <input type="text" name="hu_admin_settings[api_key]" value='<?php echo $hu_admin_settings['api_key'] ?? '' ?>' />
            <a style="margin-left: 6px;" href="https://missioncontrol.wp-overwatch.com/my-org" target="_blank">Create Token</a>
            <p class=description>The API key needs to be added before for the other plugin pages to work properly.</p>
        </td>
    </tr>
    <?php
}
add_action( 'hosting_utilities_secret_options', 'hup_api_key_options', 10, 1 );

/* TODO move this to its own page and/or expand out the functionality into individual buttons */
function hup_setup_plugin_options(){
    ?>
    <br />
    <hr />
    <p style='text-align: center;'>Setup</p>
    <hr />
    <br />
    <p>Adds rewrite rules to the .htaccess file, installs the maintenance dropin, and stores branding/whitelabeling info.</p>
    <p>If one of the below setup stages becomes outdated, the setup process is automatically rerun. </p>
    <p>If there is an error, the setup process has to be manually rerun.</p>

    <?php
    $setup = get_option('hu_setup_versions');

    if ( isset($setup['success']) ){
        echo '<ul>';
        foreach($setup as $stage => $version){
            echo "<li><b>$stage:</b> " . ($version ? "version $version" : '<font color=red>installation failed</font>') . '</li>';
        }
        echo '</ul>';
    }

    if (isset($setup['success']) && ! $setup['success']){
        echo '<p>✘ There was an error with the initial setup. Click the button below to try to rerun the setup process.</p>';
        require_once HU_PRO_PLUGIN_PATH.'inc/setup/setup_notice.php';
        add_action( 'admin_notices', 'hu_setup_error_notice' );
    } else if (isset($setup['success']) && $setup['success'] == true){
        echo '<p>✓ The initial setup has been performed. Click the button below if you would like to rerun the setup process.</p>';
    } else{
        echo '<p>✘ The setup process has not yet been run.</p>';
    }
    ?>
    <input type=submit class='button ow-btn' value="Run Setup" id='setup-btn' />

    <script type="text/javascript" >
    <?php $ajax_nonce = wp_create_nonce( 'ow-setup' ); ?>
    jQuery('#setup-btn').click(function() {
        $btn = jQuery(this)

        event.preventDefault();
        var data = {
            'security': '<?php echo $ajax_nonce ?>',
            'action': 'hu_setup',
        };

        // ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.post(ajaxurl, data, function(response) {
            $btn.val('Setup Complete')
            $btn.attr('disabled', '')
        }).fail(function(xhr, status, err){console.log(status + ' ' + err);console.log(xhr);alert('There was an error. The server response is on the dev tools console'); })
        //.success(function(){location.reload()});
    });
    </script>
    <?php
}
add_action('additional_option_forms', 'hup_setup_plugin_options');

function hup_setup_ajax_callback() {
    if ( check_ajax_referer( 'ow-setup', 'security', false) ) {

        try{
            $res = require 'inc/setup/run_setup.php';
        } catch (Exception $e) {
            $setup = get_option('ow_setup_versions', array());
            $setup['success'] = -1;
            update_option('ow_setup_versions', $setup, 'no');
            http_response_code(500);
            exit('failed');
        }

        exit(json_encode($res));

        if (isset($res['stages']['success']) && $res['stages']['success'] === true){
            exit(json_encode($res));
        } else{
            http_response_code(500);
            exit(json_encode($res));
        }

    } else {
        http_response_code(500);
        exit('Message could not be sent: security check failed');
    }
}
add_action( 'wp_ajax_hu_setup', 'hup_setup_ajax_callback' );

/* Update/Activation stuff */
register_activation_hook( __FILE__, function(){
    if (! defined('HU_COMMON_FUNCTIONS_LOADED'))
        include_once 'common/init.php';
    include_once 'inc/setup/run_setup_if_needed.php';
});
add_action( 'upgrader_post_install', function(){ /* upgrader_post_install is specific to the RKV Updater library we are using */
    if (! defined('HU_COMMON_FUNCTIONS_LOADED'))
        include_once 'common/init.php';
    include_once 'inc/setup/run_setup_if_needed.php';
});
