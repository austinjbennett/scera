<?php

//flush_rewrite_rules();

// REMOVE VISUAL EDITOR.
//add_filter('user_can_richedit','__return_false', 50);

// SHORTCODE.
//function shortcodeTest(){
//    return 'Hello there';
//}
//add_shortcode('shTest','shortcodeTest');

// MYCALENDAR PLUGIN.
//include(plugin_dir_url(__FILE__).'')

// REMOVE EDITOR IN POSTS.
//add_action('init', function () {
//    remove_post_type_support('movie', 'editor');
//    remove_post_type_support('event', 'editor');
//    remove_post_type_support('sponsor', 'editor');
//    remove_post_type_support('cast', 'editor');
//    remove_post_type_support('instructor', 'editor');
//}, 99);
add_action('init', 'create_post_type');

function create_post_type()
{
    // Events
    register_post_type(
        'events',
        array(
            'labels' => array(
                'name' => __('Misc. Events'),
                'singular_name' => __('Event'),
                'menu_name' => __('Misc. Events')
            ),
            'public' => true,
            'has_archive' => true,
        )
    );
//    register_taxonomy(
//        'event-category',
//        'events',
//        array(
//            'hierarchical' => true,
//            'label' => __('Category'),
//            'show_admin_column' => true,
//            'query_var' => true,
//            'rewrite' => array( 'slug' => 'event-category' )
//        )
//    );

    // Stage
    register_post_type(
        'stage',
        array(
            'labels' => array(
                'name' => __('Stage Event'),
                'singular_name' => __('Stage Event'),
                'menu_name' => __('Stage')
            ),
            'public' => true,
            'has_archive' => true,
        )
    );

    // Concerts
    register_post_type(
        'concerts',
        array(
            'labels' => array(
                'name' => __('Concerts'),
                'singular_name' => __('Concert'),
                'menu_name' => __('Concerts')
            ),
            'public' => true,
            'has_archive' => true,
        )
    );

    // Education
    register_post_type(
        'education',
        array(
            'labels' => array(
                'name' => __('Education'),
                'singular_name' => __('Education'),
                'menu_name' => __('Education')
            ),
            'public' => true,
            'has_archive' => true,
        )
    );

    // Movies
    register_post_type(
        'movies',
        array(
            'labels' => array(
                'name' => __('Movies'),
                'singular_name' => __('Movie')
            ),
            'public' => true,
            'has_archive' => true,
        )
    );

    // Sponsors
    register_post_type(
        'sponsors',
        array(
            'labels' => array(
                'name' => __('Sponsors'),
                'singular_name' => __('Sponsor')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array( 'title', 'editor', 'thumbnail' )
        )
    );

    // Cast
    register_post_type(
        'cast',
        array(
            'labels' => array(
                'name' => __('Cast'),
                'singular_name' => __('Cast')
            ),
            'public' => true,
            'has_archive' => true,
        )
    );

    // Instructors
    register_post_type(
        'instructors',
        array(
            'labels' => array(
                'name' => __('Instructors'),
                'singular_name' => __('Instructor')
            ),
            'public' => true,
            'has_archive' => true,
        )
    );

    // Hero Slides
    register_post_type(
        'hero-slide',
        array(
            'labels' => array(
                'name' => __('Hero Slides'),
                'singular_name' => __('Hero Slide')
            ),
            'public' => true,
            'has_archive' => true,
            'exclude_from_search' => true,
        )
    );

}

// ACF STUFF.
//if (function_exists('acf_add_options_page')) {
//    acf_add_options_page(
//        array(
//            'page_title' => 'Options',
//            'menu_title' => 'Options',
//            'menu_slug'  => 'options',
//            'capability' => 'edit_posts',
//            'redirect'   => false,
//        )
//    );
//
//    acf_add_options_sub_page(
//        array(
//            'page_title'  => 'Homepage',
//            'menu_title'  => 'Homepage',
//            'parent_slug' => 'options',
//        )
//    );
//
//    acf_add_options_sub_page(
//        array(
//            'page_title'  => 'Highlighted',
//            'menu_title'  => 'Highlighted',
//            'parent_slug' => 'options',
//        )
//    );
//}

// STOP WP FROM ADDING P TAGS.
remove_filter( 'the_content', 'wpautop' );
remove_filter( 'the_excerpt', 'wpautop' );

// CREATE MENU.
register_nav_menus(
    array(
        'headerNav' => __('Header Navigation', 'THEMENAME'),
        'user_menu' => 'User Menu',
        'footerNav' => 'Footer Nav',
    )
);

// READ MORE BUTTON.
function wpdocs_excerpt_more($more)
{
    return '<a class="readMore" href="' . get_the_permalink() . '" rel="nofollow">Read More...</a>';
}

add_filter('excerpt_more', 'wpdocs_excerpt_more');

// Register Custom Navigation Walker.
//require_once get_template_directory() . '/class-wp-bootstrap-navwalker.php';

// Bootstrap navigation.
//function bootstrap_nav() {
//    wp_nav_menu(
//        array(
//            'theme_location'  => 'primary',
//            'depth'           => 2, // 1 = no dropdowns, 2 = with dropdowns.
//            'container'       => 'div',
//            'container_class' => 'collapse navbar-collapse',
//            'container_id'    => 'myTopNav',
//            'menu_class'      => 'navbar-nav mr-auto',
//            'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
//            'walker'          => new WP_Bootstrap_Navwalker(),
//        )
//    );
//}
function load_fonts() {
    wp_enqueue_style( 'oxygenFont', 'https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Oxygen:wght@300;400;700&display=swap', false);
	wp_enqueue_style( 'fontAwesome', 'https://use.fontawesome.com/releases/v5.7.1/css/all.css', false);
}
function load_swiper_files() {
	if( is_front_page() ) {
        wp_enqueue_style( 'swiperCSS', get_template_directory_uri() . '/vendor/swiperjs/swiper.min.css');
        wp_enqueue_script( 'swiperJS', get_template_directory_uri() . '/vendor/swiperjs/swiper.min.js');
        wp_enqueue_script( 'frontJS', get_template_directory_uri() . '/js/front-page.js');
    }
}
function load_main_script() {
    wp_enqueue_script('main', get_template_directory_uri() . '/js/main.js');
}
//add_action('wp-enqueue_scripts', 'load_fonts');
add_action('wp_enqueue_scripts', 'load_swiper_files');
add_action('wp_enqueue_scripts', 'load_main_script');

/**
 * DEVELOPMENT MODE ONLY
 *
 * Browser-sync script loader
 * to enable script/style injection
 *
 */
add_action('wp_head', function () { ?>
    <script type="text/javascript" id="__bs_script__">//<![CDATA[
        document.write("<script async src='http://HOST:3000/browser-sync/browser-sync-client.js'><\/script>".replace("HOST", location.hostname));
        //]]></script>
<?php }, 999);

?>
