<?php

/*
    Load admin CSS, and correct the branding
    Use the hu_pages_stylesheet_files filter to add CSS files to hu pages
    And the hu_admin_pages_stylesheet_files filter to add CSS to add pages
 */
add_action( 'admin_enqueue_scripts', function() {

  /* just registering & enqueuing this for use later on with the wp_add_inline_style function */
  wp_enqueue_style('hu-admin-styles', 'placeholder');

  /* get stylesheets to be loaded on all admin pages */
  $stylesheets = array(HU_OPTIONS_PLUGIN_PATH."plugin_themes/css/all-admin-pages.css");
  $stylesheets = apply_filters('hu_admin_pages_stylesheet_files', $stylesheets);

  /* get stylesheets to be loaded on hu pages */
  if (is_hu_page()){
    $css_theme = get_user_meta(get_current_user_id(), 'ow_css_theme', true);
    $css_theme = empty($css_theme) ? DEFAULT_CSS_THEME : $css_theme;
    $stylesheets = apply_filters('hu_pages_stylesheet_files', $stylesheets, $css_theme);
  }

  /* get the css, so we can apply a transformation on the branding used */
  $css = '';
  foreach($stylesheets as $stylesheet){
      $css .= file_get_contents($stylesheet);
  }

  /* Depending on the whitelabeling/brading used, our pages will have different css classes, and we need to correct for that */
  $branding = hu_branding('url friendly');
  $css = str_replace('toplevel_page_wpoverwatch', 'toplevel_page_'.$branding, $css);

  /* output the CSS */
  wp_add_inline_style('hu-admin-styles', $css);

} );

/*
    Stop the hu-admin-styles placeholder from being outputted
    We do things this way instead of dequeueing it, because this allows us to continue to load
    the styles that where added with wp_add_inline_style()
*/
add_filter('style_loader_tag', function($tag, $handler){
    if ($handler === 'hu-admin-styles'){
        return '';
    }
    return $tag;
}, 10, 2);

/*
    Add styling based on which CSS theme our plugin is using.
    There is a setting in our plugin options that controls the theme being used
*/
add_filter('hu_pages_stylesheet_files', function($stylesheets, $css_theme){
    $stylesheets[] = HU_OPTIONS_PLUGIN_PATH."plugin_themes/css/admin-theme-$css_theme.css";
    return $stylesheets;
}, 10, 2);
