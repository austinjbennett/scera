<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
  Create the Hosting Utilities menu
  And register callbacks for each page

	IMPORTANT: New sub_pages need to be added to:
	- is_hu_page function located in helper_functions.php
	- all-pages mixin located in scss/base_for_all_themes/other/mixins.scss
*/
function add_wp_overwatch_admin_menu() {

	$prefix = hu_branding('url friendly');
	$pages_path = HU_OPTIONS_PLUGIN_PATH . 'pages/';

	if (hu_are_we_whitelabeled()){
		$img = 'none';
		add_action('admin_head', function(){
			?>
			<style>
				.menu-top.toplevel_page_<?php echo hu_branding('url friendly'); ?>-tickets .wp-menu-image,
				.menu-top.toplevel_page_<?php echo hu_branding('url friendly'); ?> .wp-menu-image{
					background-position: center;
					background-size: 92%;
					background-repeat: no-repeat;
					background-image: url("<?php echo hu_branding('menu icon'); ?>");
					filter: grayscale(1);
				}
				.menu-top.toplevel_page_<?php echo hu_branding('url friendly'); ?>-tickets:hover .wp-menu-image,
				.menu-top.toplevel_page_<?php echo hu_branding('url friendly'); ?>-tickets .wp-menu-open .wp-menu-image,
				.menu-top.toplevel_page_<?php echo hu_branding('url friendly'); ?>:hover .wp-menu-image,
				.menu-top.toplevel_page_<?php echo hu_branding('url friendly'); ?> .wp-menu-open .wp-menu-image{
					filter: none;
				}
				.menu-top.toplevel_page_<?php echo hu_branding('url friendly'); ?>-tickets{
					border-top: 1px solid #555;
    				background: #252a4e;
				}
				.menu-top.toplevel_page_<?php echo hu_branding('url friendly'); ?>-tickets:hover{
					background: #1e293e;
				}
			</style>
			<?php
		});
	} else {
		$img = 'data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA1ODYgNTg2Ij48ZGVmcz48c3R5bGU+LmNscy0xe2ZpbGw6ICM4Mjg3OGM7fTwvc3R5bGU+PC9kZWZzPjx0aXRsZT5Mb2dvPC90aXRsZT48cGF0aCBjbGFzcz0iY2xzLTEiIGQ9Ik00MywuMjhWMjExLjM2aDkzLjY0VjEyNS45NHE3LjcsMS4wOSwxNS41NiwxLjY3dDE1Ljc5LjU4YTIxNy4xMiwyMTcuMTIsMCwwLDAsMzMuNjctMi42MnE4LjI4LTEuMywxNi4zOS0zLjIyQTIxOS4yNSwyMTkuMjUsMCwwLDAsMjkzLDg4Ljc1YTIxOS4yNCwyMTkuMjQsMCwwLDAsNzQuOTUsMzMuNTlxOC4xLDEuOTIsMTYuMzksMy4yMkEyMTcuMTIsMjE3LjEyLDAsMCwwLDQxOCwxMjguMTloMGEyMjMuNCwyMjMuNCwwLDAsMCwzMS4zNi0yLjI1VjMzNy4yMWExNTYuNDQsMTU2LjQ0LDAsMCwxLTEyOS4yMywxNTR2OTQuNTNBMjUwLDI1MCwwLDAsMCw1NDMsMzM3LjIxVi4yOFoiLz48L3N2Zz4=';
	}

	do_action('hu_add_top_level_menu', $prefix, $img);

	add_menu_page(
		hu_branding().' Options', /* page title */
		hu_branding(), /* menu title */
		'manage_options', /* required capability */
		$prefix, /* slug */
		function() use ($pages_path){ require $pages_path.'wpoverwatch_top_page/top_page.php'; }, /* callback */
		$img,
		0 /* menu position */
	);

	do_action('hu_add_submenu_before_more_link', $prefix);

}
add_action( 'admin_menu', 'add_wp_overwatch_admin_menu' );
