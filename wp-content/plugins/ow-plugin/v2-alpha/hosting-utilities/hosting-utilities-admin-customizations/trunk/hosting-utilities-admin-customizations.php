<?php
/*
Plugin Name: Hosting Utilities Admin Theme
Description: This plugin modifies the admin area to make it more user friendly
Version: 1
*/

const HOSTING_UTILITIES_ADMIN_THEME_VERSION = '1';

define( 'HU_ADMIN_THEME_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
if ( ! defined('HU_ADMIN_THEME_PLUGIN_URL')) /* may have already been defined by our dev loader plugin */
    define( 'HU_ADMIN_THEME_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

if (! is_admin()){
    return;
}


require 'common/init.php';

/* load styles, replacing .toplevel_page_wpoverwatch and #toplevel_page_wpoverwatch with the branding being used */
add_filter('hu_admin_pages_stylesheet_files', function($stylesheets){
    $stylesheets[] = HU_ADMIN_THEME_PLUGIN_PATH."menu-styles.css";
    return $stylesheets;
}, 50);

/* Change menu item `Posts` to `Blog posts` */
/* Additional customization options can be found at https://revelationconcept.com/wordpress-rename-default-posts-news-something-else/ */
function hua_change_post_label() {
    global $menu;
    $menu[5][0] = 'Blog Posts';
}
add_action( 'admin_menu', 'hua_change_post_label' );


/* Force our menus to be the first ones in the sidebar */
function hua_wp_menu_order($menu_order){

	$prefix = hu_branding('url friendly');

	unset($menu_order[ array_search($prefix.'-tickets', $menu_order, true) ]);
	unset($menu_order[ array_search($prefix, $menu_order, true) ]);

	array_unshift($menu_order, $prefix.'-tickets', $prefix);

	return $menu_order;
}
add_filter( 'menu_order', 'hua_wp_menu_order', 999 );
add_filter( 'custom_menu_order' , '__return_true');

function hua_add_to_admin_menu(){

    $prefix = hu_branding('url friendly');
    $urlparts = parse_url(home_url());
    $domain = $urlparts['host'];
    if ($domain == 'northamericanarms.com'){ #TODO detect if their plan has the ecommerce package, but first need to store plan info in DB, but first need hosting-utilities DB table
        add_submenu_page(
          $prefix, /* parent slug */
          'Woo Discounts', /* page title */
          'Woo Discounts', /* menu title */
          'manage_options', /* capability */
          'woo_discount_rules', /* slug */
          function(){ } /* callback */
        );
    }

    add_submenu_page(
      $prefix, /* parent slug */
      'Separator', /* page title */
      '<hr />', /* menu title */
      'manage_options', /* capability */
      "$prefix-separator", /* slug */
      function(){ } /* callback */
    );

    global $hu_removed_plugins;
    $active_plugins = array_merge( get_option('active_plugins'), $hu_removed_plugins??[] );

    if (in_array('backupbuddy/backupbuddy.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'Backups', /* page title */
    	'Backups', /* menu title */
    	'manage_options', /* capability */
    	'pb_backupbuddy_backup', /* slug */
    	function(){ } /* callback */
      );
    }

    if (in_array('wp-time-capsule/wp-time-capsule.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'WP Time Capusule', /* page title */
    	'Continuous Backups', /* menu title */
    	'manage_options', /* capability */
    	'wp-time-capsule-monitor', /* slug */
    	function(){ } /* callback */
      );
    }

    if (in_array('really-simple-ssl/rlrsssl-really-simple-ssl.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'SSL/TLS', /* page title */
    	'SSL/TLS', /* menu title */
    	'manage_options', /* capability */
    	'rlrsssl_really_simple_ssl', /* slug */
    	function(){ } /* callback */
      );
    }

    if (in_array('ithemes-security-pro/ithemes-security-pro.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'Security Settings', /* page title */
    	'Security Settings', /* menu title */
    	'manage_options', /* capability */
    	'itsec', /* slug */
    	function(){ } /* callback */
      );
      add_submenu_page(
    	$prefix, /* parent slug */
    	'Activity Log', /* page title */
    	'Activity Log', /* menu title */
    	'manage_options', /* capability */
    	'itsec-logs&filters=type%7Cnotice', /* slug */
    	function(){ } /* callback */
      );
    }

    if (in_array('rvg-optimize-database/rvg-optimize-database.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'Database Optimizer', /* page title */
    	'Database Optimizer', /* menu title */
    	'manage_options', /* capability */
    	'odb_settings_page', /* slug */
    	function(){ } /* callback */
      );
    }

    if (in_array('ewww-image-optimizer/ewww-image-optimizer.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'Image Optimizer', /* page title */
    	'Image Optimizer', /* menu title */
    	'manage_options', /* capability */
    	'ewww-image-optimizer%2Fewww-image-optimizer.php', /* slug */
    	function(){ } /* callback */
      );
  } else if (in_array('wp-smush-pro/wp-smush.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'Image Optimizer', /* page title */
    	'Image Optimizer', /* menu title */
    	'manage_options', /* capability */
    	'smush', /* slug */
    	function(){ } /* callback */
      );
    } else if (in_array('webp-express/webp-express.php', $active_plugins)){
        add_submenu_page(
      	$prefix, /* parent slug */
      	'Image Optimizer', /* page title */
      	'Image Optimizer', /* menu title */
      	'manage_options', /* capability */
      	'webp_express_settings_page', /* slug */
      	function(){ } /* callback */
        );
      }

    if (in_array('breeze/breeze.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'Cache Settings', /* page title */
    	'Cache Settings', /* menu title */
    	'manage_options', /* capability */
    	'breeze', /* slug */
    	function(){ } /* callback */
      );
  } else if (in_array('swift-performance/performance.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'Cache Settings', /* page title */
    	'Cache Settings', /* menu title */
    	'manage_options', /* capability */
    	'swift-performance', /* slug */
    	function(){ } /* callback */
      );
    }

    if (in_array('redis-cache/redis-cache.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'Redis', /* page title */
    	'Object Cache', /* menu title */
    	'manage_options', /* capability */
    	'redis-cache', /* slug */
    	function(){ } /* callback */
      );
    }

    if (in_array('wordfence/wordfence.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'WordPress Application Firewall (WAF)', /* page title */
    	'Firewall', /* menu title */
    	'manage_options', /* capability */
    	'Wordfence', /* slug */
    	function(){ } /* callback */
      );
    }

    if (in_array('wp-mail-smtp/wp_mail_smtp.php', $active_plugins)){
      add_submenu_page(
    	$prefix, /* parent slug */
    	'WP Mail SMTP', /* page title */
    	'SMTP email', /* menu title */
    	'manage_options', /* capability */
    	'wp-mail-smtp', /* slug */
    	function(){ } /* callback */
      );
    }

}
add_action( 'admin_menu', 'hua_add_to_admin_menu' );

add_action( 'hu_add_submenu_before_more_link', function($prefix){
    add_submenu_page(
      $prefix, /* parent slug */
      'Separator', /* page title */
      "<span id='ow-more-inner'>More <span class='dashicons dashicons-arrow-down-alt2'></span></span>", /* menu title */
      'manage_options', /* capability */
      "$prefix-separator", /* slug */
      function(){ } /* callback */
    );
}, 99999); /* Using a high priority to make sure the more link happens after all of the menus that where suppose to go before the more dropdown have been added */

function hua_show_hidden_plugins_option(){
    ?>
    <div id='hiddenPlugins'>
      <p><b>Show Hidden Plugins:</b></p>

      <?php
        global $ow_removed_plugins;
        $ow_removed_plugins = $ow_removed_plugins ?? [];

        if (isset($_COOKIE['show_hidden_plugins'])){
          $time_remaining = abs( ( time() - $_COOKIE['show_hidden_plugins'] ) / 3600 );
          if ($time_remaining > 1){
            $time_remaining =  round( $time_remaining, $precision=0 );
          } else{
            $time_remaining =  round( $time_remaining, $precision=2 );
          }

        ?>
        <p>To keep the WordPress backend less cluttered and loading faster, we normally hide the settings pages of the following plugins.</p>
        <?php echo "<ul style='margin-left: 2em;'><li>" . implode( "</li><li>", array_keys($ow_removed_plugins) ) . "</li></ul>"; ?>
        <p>For the next <?php echo $time_remaining ?> hours these plugins are being shown.</p>

      <?php } else { ?>

        <p>To keep the WordPress backend less cluttered and loading faster, we have hidden the settings pages of the following plugins.</p>
        <?php echo "<ul style='margin-left: 2em;'><li>" . implode( "</li><li>", $ow_removed_plugins ) . "</li></ul>"; ?>
        <p>If you would like to see these pages, check the box below. </p>

        <label><input type="checkbox" id="showHiddenPlugins" name="show_hidden_plugins" value="true">Show hidden plugins for the next 12 hours</label>
      <?php } ?>

    </div>
    <br />
    <br />
    <script>
      document.getElementById('hiddenPlugins').addEventListener('change', function(e){
        fetch( '<?php echo admin_url( 'admin-ajax.php' ) ?>?action=ow_show_hidden_plugins&nonce=<?php echo wp_create_nonce('ow-show-hidden-plugins'); ?>', {
          method: 'POST',
          headers : new Headers({'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'}),
          credentials: 'same-origin', /* Must be present to enable cookies with the request */
        } )
          .then(
            function(response) {

              if (response.status !== 200) {
                console.log('There was a problem. Status Code: ' + response.status);
              }

              // Examine the text in the response
              response.text().then(function(data) {
                if (data === 'success'){
                  location.reload()
                } else{
                  alert('Ouch. There was a failure enabling hidden plugins.')
                }
              });
            }
          )
          .catch(function(err) {
            console.log('Fetch Error :', err);
            alert('Ouch! There was a failure enabling hidden plugins.')
          });

      })
    </script>
    <?php
}
add_action('hosting_utilities_general_options', 'hua_show_hidden_plugins_option');

function hua_hide_update_notice_option(){
    ?>
    <tr valign="top">
        <th scope="row">Hide Update Notices</th>
        <td>
            <?php $checked = isset($hu_admin_settings['hide_update_notices']) && $hu_admin_settings['hide_update_notices'] == true; ?>
            <input type="checkbox" name="hu_admin_settings[hide_update_notices]" value=true <?php echo $checked ? 'checked' : ''; ?> />
            <p class=description>Hide notices that contain words such as "update", "upgrade", etc.</p>
            <p class=description>We can already see what plugins need to be updated from the plugins page. The notices are just unneccessary advertisements.</p>
        </td>
    </tr>
    <?php
}
add_action('hosting_utilities_secret_options', 'hua_hide_update_notice_option');

include_once __dir__.'/inc/admin_customizations/admin_customizations.php';

function hua_show_hidden_plugins_ajax_callback() {
 if ( check_ajax_referer( 'ow-show-hidden-plugins', 'nonce', false) ) {

	 $urlparts = parse_url(home_url());
	 $the_domain = $urlparts['host'];

	 $expires = time()+(3600*12); /* will expire in 12 hour */

	 /* Also setting expiration date as cookie value so we can report to the user when the cookie expires */
	 setcookie('show_hidden_plugins', $expires, $expires, '/wp-admin', $domain=$the_domain, $secure=is_ssl());
	 exit('success');

 } else {
	 http_response_code(500);
     exit('Message could not be sent: security check failed');
 }
}
add_action( 'wp_ajax_ow_show_hidden_plugins', 'hua_show_hidden_plugins_ajax_callback' );
