<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* callback for adding branding to announcements shown on the login page  */
function ow_announcement_branding_ajax_callback() {
  //For some unkown reason, the $_POST was not automatically getting decoded in the way I expected,
  //So I'm manually decoding the $_POST variables
  $_POST_fixed = array();
  foreach( explode( '&', file_get_contents('php://input') ) as $kv){
    $kv = explode( '=', $kv);
    $_POST_fixed[$kv[0]] = urldecode( $kv[1] );
  }

  check_ajax_referer( 'ow-announcement-branding', 'security', false);

  $default_branding_info = array(
   	'url_friendly' => 'wpoverwatch',
   	'url' => 'https://wp-overwatch.com/',
   	'url_pricing' => 'https://wp-overwatch.com/#PanelPricing',
   	'logo' => 'https://wordpressoverwatch.com/logo.svg',
   	'menu-icon' => 'https://wordpressoverwatch.com/logo.svg',
   	'possessive' => "WP Overwatch's",
   	'official' => 'WP Overwatch LLC',
   	'default' => 'WP Overwatch',
  );

  $content = $_POST_fixed['content'];
  $branding = get_option('ow_whitelabel_info', $default_branding_info);
  $branding['name'] = $branding['default'];

  if (preg_match_all("/{{(.*?)}}/", $content, $m)) {
   foreach ($m[1] as $i => $varname) {
     if ( isset($branding[$varname]) )
       $content = str_replace($m[0][$i], $branding[$varname], $content);
   }
  }

  echo $content;
  exit;

}
add_action( 'wp_ajax_nopriv_ow_announcement_branding', 'ow_announcement_branding_ajax_callback' );
