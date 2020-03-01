<?php
// REMOVE VISUAL EDITOR.
// add_filter('user_can_richedit','__return_false', 50);

// SHORTCODE.
// function shortcodeTest(){
// 	return 'Hello there';
// }
// add_shortcode('shTest','shortcodeTest');

// MYCALENDAR PLUGIN.
// include(plugin_dir_url(__FILE__).'')

// REMOVE EDITOR IN POSTS.
add_action('init', function () {
	remove_post_type_support('movie', 'editor');
	remove_post_type_support('event', 'editor');
	remove_post_type_support('sponsor', 'editor');
	remove_post_type_support('cast', 'editor');
	remove_post_type_support('instructor', 'editor');
}, 99);

// ACF STUFF.
if (function_exists('acf_add_options_page')) {
	acf_add_options_page(
		array(
			'page_title' => 'Options',
			'menu_title' => 'Options',
			'menu_slug'  => 'options',
			'capability' => 'edit_posts',
			'redirect'   => false,
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Homepage',
			'menu_title'  => 'Homepage',
			'parent_slug' => 'options',
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Highlighted',
			'menu_title'  => 'Highlighted',
			'parent_slug' => 'options',
		)
	);
}

// STOP WP FROM ADDING P TAGS.
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

// CREATE MENU.
register_nav_menus(
	array(
		'primary'   => __( 'Primary Menu', 'THEMENAME' ),
		'user_menu' => 'User Menu',
		'footerNav' => 'Footer Nav',
	)
);

// READ MORE BUTTON.
function wpdocs_excerpt_more($more)
{
	return '<a class="readMore" href="' . get_the_permalink() . '" rel="nofollow">Read More...</a>';
}

add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );

// Register Custom Navigation Walker.
//require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';
//
//// Bootstrap navigation.
//function bootstrap_nav() {
//	wp_nav_menu(
//		array(
//			'theme_location'  => 'primary',
//			'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
//			'container'       => 'div',
//			'container_class' => 'collapse navbar-collapse',
//			'container_id'    => 'myTopNav',
//			'menu_class'      => 'navbar-nav mr-auto',
//			'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
//			'walker'          => new WP_Bootstrap_Navwalker(),
//		)
//	);
//}

/**
 * DEVELOPMENT MODE ONLY
 *
 * Browser-sync script loader
 * to enable script/style injection
 *
 */
add_action( 'wp_head', function () { ?>
	<script type="text/javascript" id="__bs_script__">//<![CDATA[
        document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.js'><\/script>".replace("HOST", location.hostname));
        //]]></script>
<?php }, 999);

?>