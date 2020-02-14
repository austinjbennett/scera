<?php
/*
Plugin Name: Hosting Utilities Site Health
Description: Adds a site health dashboard.
Version: 1
*/

const HOSTING_UTILITIES_SITE_HEALTH_VERSION = '1';

define( 'HU_SITEHEALTH_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
if (!defined('HU_SITEHEALTH_PLUGIN_URL')) /* may have already been defined by our dev loader plugin */
    define( 'HU_SITEHEALTH_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

/*
    load hosting utilities common library if it hasn't been loaded already
*/
if (! defined('HU_COMMON_FUNCTIONS_LOADED'))
    require 'common/init.php';

include_once 'pages/options/update_site_health_dashboard.php';

function hush_site_health_options(){
    ?>
    <tr valign="top" class='sub-section'>
        <th scope="row">
            <br />
            <hr />
          <p>Site Health Dashboard</p>
          <hr />
            <br />
        </th>
    </tr>

    <tr valign="top">
        <th scope="row">Show Sales</th>
        <td>
            <?php $checked = isset($hu_admin_settings['site_health_show_sales']) && $hu_admin_settings['site_health_show_sales'] == true; ?>
            <input type="checkbox" name="hu_admin_settings[site_health_show_sales]" value=true <?php echo $checked ? 'checked' : ''; ?> />
            <p class=description>Check to show WooCommerce sales in the site health dashboard.</p>
        </td>
    </tr>

    <tr valign="top">
        <th scope="row">Activity Feed</th>
        <td>
            <?php $checked = isset($hu_admin_settings['activity_feed']) && $hu_admin_settings['activity_feed'] == true; ?>
            <input type="checkbox" name="hu_admin_settings[activity_feed]" value=true <?php echo $checked ? 'checked' : ''; ?> />
            <p class=description>Check to show the activity feed in the site health dashboard.</p>
        </td>
    </tr>
    <?php
}
add_action( 'hosting_utilities_secret_options', 'hush_site_health_options' );
