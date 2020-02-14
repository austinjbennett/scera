<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* Force our menus to be the first ones in the sidebar */
function ow_wp_menu_order($menu_order){

	$prefix = ow_branding('url friendly');

	unset($menu_order[ array_search($prefix.'-tickets', $menu_order, true) ]);
	unset($menu_order[ array_search($prefix, $menu_order, true) ]);

	array_unshift($menu_order, $prefix.'-tickets', $prefix);

	return $menu_order;
}
add_filter( 'menu_order', 'ow_wp_menu_order', 999 );
add_filter( 'custom_menu_order' , '__return_true');

/* Change menu item `Posts` to `Blog posts` */
/* Additional customization options can be found at https://revelationconcept.com/wordpress-rename-default-posts-news-something-else/ */
function ow_change_post_label() {
    global $menu;
    $menu[5][0] = 'Blog Posts';
}
add_action( 'admin_menu', 'ow_change_post_label' );

/*
  Create the WP Overwatch menu
  And register callbacks for each page

	IMPORTANT: New sub_pages need to be added to:
	- is_ow_page function located in helper_functions.php
	- all-pages mixin located in scss/base_for_all_themes/other/mixins.scss
*/
function add_wp_overwatch_admin_menu() {

	$prefix = ow_branding('url friendly');
	$pages_path = OW_PLUGIN_PATH . 'pages/';

	if (ow_are_we_whitelabeled()){
		$img = 'none';
		add_action('admin_head', function(){
			?>
			<style>
				.menu-top.toplevel_page_<?php echo ow_branding('url friendly'); ?>-tickets .wp-menu-image,
				.menu-top.toplevel_page_<?php echo ow_branding('url friendly'); ?> .wp-menu-image{
					background-position: center;
					background-size: 92%;
					background-repeat: no-repeat;
					background-image: url("<?php echo ow_branding('menu icon'); ?>");
					filter: grayscale(1);
				}
				.menu-top.toplevel_page_<?php echo ow_branding('url friendly'); ?>-tickets:hover .wp-menu-image,
				.menu-top.toplevel_page_<?php echo ow_branding('url friendly'); ?>-tickets .wp-menu-open .wp-menu-image,
				.menu-top.toplevel_page_<?php echo ow_branding('url friendly'); ?>:hover .wp-menu-image,
				.menu-top.toplevel_page_<?php echo ow_branding('url friendly'); ?> .wp-menu-open .wp-menu-image{
					filter: none;
				}
				.menu-top.toplevel_page_<?php echo ow_branding('url friendly'); ?>-tickets{
					border-top: 1px solid #555;
    				background: #252a4e;
				}
				.menu-top.toplevel_page_<?php echo ow_branding('url friendly'); ?>-tickets:hover{
					background: #1e293e;
				}
			</style>
			<?php
		});
	} else {
		$img = 'data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1ODYgNTg2Ij48ZGVmcz48c3R5bGU+LmNscy0xe2ZpbGw6ICM4Mjg3OGM7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5Mb2dvPC90aXRsZT48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik00MywuMjhWMjExLjM2aDkzLjY0VjEyNS45NHE3LjcsMS4wOSwxNS41NiwxLjY3dDE1Ljc5LjU4YTIxNy4xMiwyMTcuMTIsMCwwLDAsMzMuNjctMi42MnE4LjI4LTEuMywxNi4zOS0zLjIyQTIxOS4yNSwyMTkuMjUsMCwwLDAsMjkzLDg4Ljc1YTIxOS4yNCwyMTkuMjQsMCwwLDAsNzQuOTUsMzMuNTlxOC4xLDEuOTIsMTYuMzksMy4yMkEyMTcuMTIsMjE3LjEyLDAsMCwwLDQxOCwxMjguMTloMGEyMjMuNCwyMjMuNCwwLDAsMCwzMS4zNi0yLjI1VjMzNy4yMWExNTYuNDQsMTU2LjQ0LDAsMCwxLTEyOS4yMywxNTR2OTQuNTNBMjUwLDI1MCwwLDAsMCw1NDMsMzM3LjIxVi4yOFoiLz48L3N2Zz4=';
	}
	add_menu_page(
		'Submit Ticket', /* page title */
		'Submit Ticket', /* menu title */
		'hu_submit_tickets', /* required capability */
		$prefix.'-tickets', /* slug */
		function() use ($pages_path){ require $pages_path.'tickets/submit.php'; }, /* callback */
		$img,
		-9999999 /* menu position */
	);
	add_menu_page(
		ow_branding().' Options', /* page title */
		ow_branding(), /* menu title */
		'manage_options', /* required capability */
		$prefix, /* slug */
		function() use ($pages_path){ require $pages_path.'wpoverwatch_top_page/top_page.php'; }, /* callback */
		$img,
		0 /* menu position */
	);

	add_submenu_page(
	  $prefix, /* parent slug */
	  'Site Health Dashboard', /* page title */
	  'Site Health', /* menu title */
	  'manage_options', /* capability */
	  "$prefix-site-health-dashboard", /* slug */
	  function() use ($pages_path){ include $pages_path.'site_health/site_health_dashboard.php'; } /* callback */
	);

	add_submenu_page(
	  $prefix, /* parent slug */
	  'Tickets', /* page title */
	  'Submit Ticket', /* menu title */
	  'hu_submit_tickets', /* capability */
	  $prefix.'-tickets', /* slug */
  	  function() use ($pages_path){ /*require $pages_path.'tickets/submit.php';*/ } /* callback */
	);

	add_submenu_page(
	  $prefix, /* parent slug */
	  'My Tickets', /* page title */
	  'My Tickets', /* menu title */
	  'hu_submit_tickets', /* capability */
	  "$prefix-my-tickets", /* slug */
	  function() use ($pages_path){ include $pages_path.'tickets/my_tickets.php'; } /* callback */
	);

	add_submenu_page(
		'', /* parent slug */ /* Making this an empty string prevents the page from showing up on the admin menu */
	  'Ticket', /* page title */
	  'Ticket', /* menu title */
	  'hu_submit_tickets', /* capability */
	  "$prefix-ticket", /* slug */
	  function() use ($pages_path){ include $pages_path.'tickets/ticket.php'; } /* callback */
	);

	add_submenu_page(
	  $prefix, /* parent slug */
	  'My Site Care Plan', /* page title */
	  'My Plan', /* menu title */
	  'manage_options', /* capability */
	  "$prefix-plan", /* slug */
	  function() use ($pages_path){ require $pages_path.'plan/my_plan.php'; } /* callback */
	);

	$urlparts = parse_url(home_url());
	$domain = $urlparts['host'];
	if ($domain == 'northamericanarms.com'){ #TODO detect if their plan has the ecommerce package, but first need to store plan info in DB, but first need hosting-utilities DB table
		add_submenu_page(
		  $prefix, /* parent slug */
		  'Woo Discounts', /* page title */
		  'Woo Discounts', /* menu title */
		  'manage_options', /* capability */
		  'woo_discount_rules', /* slug */
		  function() use ($pages_path){ } /* callback */
		);
	}

	add_submenu_page(
      $prefix, /* parent slug */
      'Separator', /* page title */
      "<span id='ow-more-inner'>More <span class='dashicons dashicons-arrow-down-alt2'></span></span>", /* menu title */
      'manage_options', /* capability */
      "$prefix-separator", /* slug */
      function() use ($pages_path){ } /* callback */
    );


	add_submenu_page(
      $prefix, /* parent slug */
      'Separator', /* page title */
      '<hr />', /* menu title */
      'manage_options', /* capability */
      "$prefix-separator", /* slug */
      function() use ($pages_path){ } /* callback */
  );

	add_submenu_page(
	  $prefix, /* parent slug */
	  'Roadmap', /* page title */
	  'Roadmap / Feedback', /* menu title */
	  'manage_options', /* capability */
	  "$prefix-roadmap", /* slug */
	  function() use ($pages_path){ include $pages_path.'roadmap/roadmap.php'; } /* callback */
	);

	add_submenu_page(
      $prefix, /* parent slug */
      'Options', /* page title */
      'Options', /* menu title */
      'manage_options', /* capability */
      "$prefix-options", /* slug */
      function() use ($pages_path){ require $pages_path.'options/index.php'; } /* callback */
    );

  add_submenu_page(
    $prefix, /* parent slug */
    'Separator', /* page title */
    '<hr />', /* menu title */
    'manage_options', /* capability */
    "$prefix-separator", /* slug */
    function() use ($pages_path){ } /* callback */
  );

	global $ow_removed_plugins;
	$active_plugins = array_merge( get_option('active_plugins'), $ow_removed_plugins??[] );

	if (in_array('backupbuddy/backupbuddy.php', $active_plugins)){
	  add_submenu_page(
		$prefix, /* parent slug */
		'Backups', /* page title */
		'Backups', /* menu title */
		'manage_options', /* capability */
		'pb_backupbuddy_backup', /* slug */
		function() use ($pages_path){ } /* callback */
	  );
	}

	if (in_array('really-simple-ssl/rlrsssl-really-simple-ssl.php', $active_plugins)){
	  add_submenu_page(
		$prefix, /* parent slug */
		'SSL/TLS', /* page title */
		'SSL/TLS', /* menu title */
		'manage_options', /* capability */
		'rlrsssl_really_simple_ssl', /* slug */
		function() use ($pages_path){ } /* callback */
	  );
	}

	if (in_array('ithemes-security-pro/ithemes-security-pro.php', $active_plugins)){
	  add_submenu_page(
		$prefix, /* parent slug */
		'Security Settings', /* page title */
		'Security Settings', /* menu title */
		'manage_options', /* capability */
		'itsec', /* slug */
		function() use ($pages_path){ } /* callback */
	  );
	  add_submenu_page(
		$prefix, /* parent slug */
		'Activity Log', /* page title */
		'Activity Log', /* menu title */
		'manage_options', /* capability */
		'itsec-logs&filters=type%7Cnotice', /* slug */
		function() use ($pages_path){ } /* callback */
	  );
	}

	if (in_array('rvg-optimize-database/rvg-optimize-database.php', $active_plugins)){
	  add_submenu_page(
		$prefix, /* parent slug */
		'Database Optimizer', /* page title */
		'Database Optimizer', /* menu title */
		'manage_options', /* capability */
		'odb_settings_page', /* slug */
		function() use ($pages_path){ } /* callback */
	  );
	}

	if (in_array('ewww-image-optimizer/ewww-image-optimizer.php', $active_plugins)){
	  add_submenu_page(
		$prefix, /* parent slug */
		'Image Optimizer', /* page title */
		'Image Optimizer', /* menu title */
		'manage_options', /* capability */
		'ewww-image-optimizer%2Fewww-image-optimizer.php', /* slug */
		function() use ($pages_path){ } /* callback */
	  );
	}

	if (in_array('breeze/breeze.php', $active_plugins)){
	  add_submenu_page(
		$prefix, /* parent slug */
		'Cache Settings', /* page title */
		'Cache Settings', /* menu title */
		'manage_options', /* capability */
		'breeze', /* slug */
		function() use ($pages_path){ } /* callback */
	  );
	}

	if (in_array('wordfence/wordfence.php', $active_plugins)){
	  add_submenu_page(
		$prefix, /* parent slug */
		'WordPress Application Firewall (WAF)', /* page title */
		'Firewall', /* menu title */
		'manage_options', /* capability */
		'Wordfence', /* slug */
		function() use ($pages_path){ } /* callback */
	  );
	}

	if (in_array('wp-mail-smtp/wp_mail_smtp.php', $active_plugins)){
	  add_submenu_page(
		$prefix, /* parent slug */
		'WP Mail SMTP', /* page title */
		'SMTP email', /* menu title */
		'manage_options', /* capability */
		'Wordfence', /* slug */
		function() use ($pages_path){ } /* callback */
	  );
	}

}
add_action( 'admin_menu', 'add_wp_overwatch_admin_menu' );
