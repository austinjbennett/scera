<?php
/*
	Plugin Name: Autonav Sidebar Widget
	Description: This plugin creates a widget that uses Wordpress Menus to show a sidebar navigation.
	Version: 1.5
	Author: Evan Bradham
	Copyright 2012 evanbradham.org
	New to 1.4, No title duplication on first item.  No longer shows siblings on top level.  Added some WordPress widget classes and html adjustments.  Add custom class support from the menu
	New to 1.5, Now works specifically with Woocommerce products CPT.
*/

class AutoNavSidebarWidget extends WP_Widget
{
  function __construct()
  {
    $widget_ops = array('classname' => 'AutoNavSidebarWidget', 'description' => 'Displays a sidebar Navigation based on your Sidebar Menu in Appearance->Menus->Theme Locations->Autonav Sidebar Menu' );
    parent::__construct('AutoNavSidebarWidget', 'Autonav Sidebar', $widget_ops);

	register_nav_menu( 'autonav-sidebar', __( 'Autonav Sidebar Menu', 'themebo' ) );
	//wp_register_style( 'autonav_style', plugins_url('autonav.css', __FILE__) );
    //wp_enqueue_style( 'autonav_style' );
  }

  function form($instance) {
  	$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
  	$title = $instance['title'];
  	?>
  	<p><label for="<?php echo $this->get_field_id('title'); ?>">Default Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
  	<?php
  }

  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }

 //start widget
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);

    echo $before_widget;
	//start menu list
	$menu_name = 'autonav-sidebar';

    if ( ( $locations = get_nav_menu_locations() ) &&  $locations[ $menu_name ] ) {
		$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
		$menu_items = wp_get_nav_menu_items($menu->term_id);
		$page_id = get_the_ID();
		$page_title = get_the_title();
		$current = 0;
		$cat = get_the_category();
		//Checker for woocommerece products post type in the nav
		$post_check = get_post_type( $page_id );
		if( $post_check ==  'product'){
			$post_check .= 's/';
			$page = get_page_by_path($post_check);
			$page_id = $page->ID;
			$page_title = $page->poast_title;
		}

		$found = false;

		foreach( (array) $cat as $key => $kitty ){
			$this_cat = $kitty->name;
		}
		//sets up the current page
		foreach ( (array) $menu_items as $key => $menu_item ) {
			if( ($menu_item->object_id) == $page_id && $found != true){
				$main_parent_id = $menu_item ->menu_item_parent;
				$main_page_id =  $menu_item ->db_id;
				$menu_item->show = true;
				$found = true;
				if($main_parent_id == 0){
					$menu_title = $main_page_id;
				}else{
					$menu_title = $main_parent_id;
				}
			}elseif($menu_item ->title == $this_cat ){
				$main_parent_id = $menu_item ->menu_item_parent;
				$main_page_id =  $menu_item ->db_id;
				$menu_item->show = true;
				if($main_parent_id == 0){

					$menu_title = $main_page_id;
				}else{
					$menu_title = $main_parent_id;
				}
			}
		}
		//decides which pages to show
		foreach ( (array) $menu_items as $key => $menu_item ) {
			$current++;
			$sp = '';
			//checks for siblings
			if($menu_item ->menu_item_parent == $main_parent_id && $main_parent_id != 0 ){
				$menu_item->show = true;
			}

			//check if parent is self
			if($menu_item ->db_id == $main_parent_id){
				$menu_item->selfparent = true;
				$sp = $menu_item ->object_id;
			}
			if($menu_item ->object_id == $sp && $menu_item->selfparent != true){
				$menu_item->sub1 = true;
			}
			//checks for children
			if($menu_item ->menu_item_parent == $main_page_id || $menu_item ->menu_item_parent == $menu_item ->db_id){
				$menu_item->sub1 = true;
				$menu_item->show = true;
				$menu_item->child = $current-1;
			}

		}
		//create an array of active links
		foreach ( (array) $menu_items as $key => $menu_item ) {
			$id = $menu_item->db_id;
			if($menu_item->show){
				if($menu_item->sub1){
					$item_list['sub1'][$id] = $menu_item;
				}else{
					$item_list['main'][$id] = $menu_item;
				}
			}
		}
		//Begin creating the html for the menu
		$menu_list = '<div class="menu-sidebar-navigation-container">';
		//title
		$menu_list .= '<h3 class="widget-title" >';
		foreach( (array) $menu_items as $key => $menu_item ){
			if($menu_title == $menu_item ->db_id){
				$menu_list .=  $menu_item->title;
				$checker = $menu_item->title;
			}
		}
		$menu_list .=  '</h3>';

		$menu_list .= '<ul id="menu-' . $menu_name . '">';
		//go through the active link array and place them accordingly
		if($item_list['main']){

			foreach($item_list['main'] as $key => $menu_item){
				$classes = reset($menu_item->classes);
				$title = $menu_item->title;
				$url = $menu_item->url;
				$location = $menu_item->menu_order;
				if( $main_parent_id != 0){//on top level pages don't show the h3 with the title and a link with the title
					if($menu_item->title == $checker || $this_cat == $title){
						$menu_list .= '<li class="current ' . $classes . '"><a href="' . $url . '">' . $title . '</a>';
					}else{
						$menu_list .= '<li class="' . $classes . '"><a href="' . $url . '">' . $title . '</a>';
					}
				}
				if($item_list['sub1']){
					if($checker != $checker2 && $main_parent_id != 0){
						$menu_list .= '<ul class="sub-menu">';
					}
					$here='no';
					foreach($item_list['sub1'] as $key => $menu_item){

						if( reset($menu_item->classes) ){
							$classes = reset($menu_item->classes);
						}
						if($location == $menu_item->child || $here=='yes'){
							$title = $menu_item->title;
							$url = $menu_item->url;
							$here='yes';//force reiteration through all sub1 siblings
							if($menu_item->db_id == $main_page_id || $this_cat == $title){
								$menu_list .= '<li class="current ' . $classes . '"><a href="' . $url . '">' . $title . '</a>';
							}else{
								$menu_list .= '<li class="' . $classes . '"><a href="' . $url . '">' . $title . '</a>';
							}
						}//end foreach sub1
					}
					if($checker != $checker2  && $main_parent_id != 0){
						$menu_list .= '</ul>';
					}
				}
				if( $main_parent_id != 0){//on top level pages don't show the h3 with the title and a link with the title
					$menu_list .= '</li>';
				}
			}//end foreach main
			$menu_list .= '</ul></div>';
			echo $menu_list;
		}else{
			//Begin creating the html for the menu
			$menu_list = '<div class="menu-sidebar-navigation-container">';
			//title

			$menu_list .= '<h3 class="widget-title">';

			$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

		    if (!empty($title))
		      $menu_list .= $title;

			$menu_list .='</h3>';

			echo $menu_list;
			wp_nav_menu(array('theme_location'=> 'primary', 'depth' => 1,'menu_id' => 'menu-autonav-sidebar'));
			echo '</div>';
		}
		//end menu HTML
    } else {//if you forget to set the Theme Location
		$menu_list = '<p>You forget to set the Autonav Sidebar in Theme Locations on the Appearance->Menus page of the wordpress admin.</p>';
		echo $menu_list;
    }
	//end menu_list
    echo $after_widget;

  }//end widget
}
add_action( 'widgets_init', create_function('', 'return register_widget("AutoNavSidebarWidget");') );

?>
