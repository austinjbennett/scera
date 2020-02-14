<?php

function hu_update_site_health_dashboard_config($changes, $settings){

  if (! (
    array_key_exists('activity_feed', $changes)
    || array_key_exists('site_health_show_sales', $changes)
  )) {
    return;
  }

  $show_activity_feed = $settings['activity_feed'] ?? 'false';
  $show_sales = $settings['site_health_show_sales'] ?? 'false';

  $config_file_dir = wp_upload_dir()['basedir'] . '/site_health_dashboard/';
  $config_file_loc = $config_file_dir . 'config.js';
  $service_registry = 'service-registry.wordpressoverwatch.com';
  $force_ssl = 'true'; // only disable when developing locally
  $current_domain = parse_url( home_url() )['host'];
  $wp_authenticator_url = HU_PRO_PLUGIN_URL.'wp_authenticator/';
  $wpDir = str_repeat('../', substr_count(HU_PRO_PLUGIN_URL, '/')-2); // wp-dir from wp-authenticator's perspective

  $config = <<<EOD
window.config = {
  "service_registry": "https://$service_registry",
  "base_url": "/dashboard/",
  "target_domain": "$current_domain",
  "auth_url": "$wp_authenticator_url",
  "wp_dir": $wpDir,
  "force_ssl": $force_ssl,

  "doesSales": $show_sales,
  "activityFeed": $show_activity_feed,
}
EOD;

  wp_mkdir_p($config_file_dir);
  $fp = fopen($config_file_loc, "wb");
  fwrite($fp, $config);
  fclose($fp);

}
add_action('hu_settings_updated', 'hu_update_site_health_dashboard_config', 10, 2);


function hu_update_token($changes, $settings){

  if (! array_key_exists('api_key', $changes)) {
    return;
  }

  $api_key = $settings['api_key'] ?? '';

  if (!empty($api_key)){

    $token_file_loc = HU_PRO_PLUGIN_PATH . 'wp_authenticator/token';

    $fp = fopen($token_file_loc, "wb");
    fwrite($fp, $api_key);
    fclose($fp);
  }

}
add_action('hu_settings_updated', 'hu_update_token', 10, 2);
