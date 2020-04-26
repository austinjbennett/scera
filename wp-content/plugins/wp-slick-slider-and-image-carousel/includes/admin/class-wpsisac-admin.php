<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package WP Slick Slider and Image Carousel
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpsisac_Admin {

	function __construct() {		

		// Action to add admin menu
		add_action( 'admin_menu', array($this, 'wpsisac_register_menu'), 12 );

		// Admin init process
		add_action( 'admin_init', array($this, 'wpsisac_admin_init_process') );

		// Action to add metabox
		add_action( 'add_meta_boxes', array($this, 'wpsisac_post_sett_metabox') );

		// Action to save metabox
		add_action( 'save_post_'.WPSISAC_POST_TYPE, array($this, 'wpsisac_save_metabox_value') );

		// Action to add custom column to slide listing
		add_filter("manage_edit-wpsisac_slider-category_columns", array( $this, 'wpsisac_slider_category_manage_columns') );

		// Action to add custom column data to slide listing
		add_filter("manage_wpsisac_slider-category_custom_column", array( $this, 'wpsisac_slider_category_columns' ), 10, 3);

	}

	/**
	 * Function to add menu
	 * 
	 * @package WP Slick Slider and Image Carousel
	 * @since 1.0.0
	 */
	function wpsisac_register_menu() {

		// Register plugin premium page
		add_submenu_page( 'edit.php?post_type='.WPSISAC_POST_TYPE, __('Upgrade to PRO - WP Slick Slider and Image Carousel', 'wp-slick-slider-and-image-carousel'), '<span style="color:#2ECC71">'.__('Upgrade to PRO', 'wp-slick-slider-and-image-carousel').'</span>', 'manage_options', 'wpsisac-premium', array($this, 'wpsisac_premium_page') );

		// Register plugin hire us page
		add_submenu_page( 'edit.php?post_type='.WPSISAC_POST_TYPE, __('Hire Us', 'wp-slick-slider-and-image-carousel'), '<span style="color:#2ECC71">'.__('Hire Us', 'wp-slick-slider-and-image-carousel').'</span>', 'manage_options', 'wpnw-hireus', array($this, 'wpsisac_hireus_page') );
	}

	/**
	 * Getting Started Page Html
	 * 
	 * @package WP Slick Slider and Image Carousel
	 * @since 1.0.0
	 */
	function wpsisac_premium_page() {
		include_once( WPSISAC_DIR . '/includes/admin/settings/premium.php' );
	}

	/**
	 * Hire Us Page Html
	 * 
	 * @package WP Slick Slider and Image Carousel
	 * @since 1.0.0
	 */
	function wpsisac_hireus_page() {		
		include_once( WPSISAC_DIR . '/includes/admin/settings/hire-us.php' );
	}

	/**
	 * Function to notification transient
	 * 
	 * @package WP Slick Slider and Image Carousel
	 * @since 1.5
	 */
	function wpsisac_admin_init_process() {
		// If plugin notice is dismissed
	    if( isset($_GET['message']) && $_GET['message'] == 'wpsisac-plugin-notice' ) {
	    	set_transient( 'wpsisac_install_notice', true, 604800 );
	    }
	}

	/**
	 * Post Settings Metabox
	 * 
	 * @package WP Slick Slider and Image Carousel
	 * @since 1.0.0
	 */
	function wpsisac_post_sett_metabox() {
		add_meta_box( 'custom-metabox', __( 'WP Slick Slider and Image Carousel - Settings', 'wp-slick-slider-and-image-carousel' ), array($this, 'wpsisac_post_sett_mb_content'), WPSISAC_POST_TYPE, 'normal', 'high' );
	}

	/**
	 * Post Settings Metabox HTML
	 * 
	 * @package WP Slick Slider and Image Carousel
	 * @since 1.2.5
	 */
	function wpsisac_post_sett_mb_content( $post ) {
		include_once( WPSISAC_DIR .'/includes/admin/metabox/wpsisac-post-metabox.php');
	}

	/**
	 * Function to save metabox values
	 * 
	 * @package WP Slick Slider and Image Carousel
	 * @since 1.2.5
	 */
	function wpsisac_save_metabox_value( $post_id ) {

		global $post_type;

		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )					// Check Autosave
		|| ( ! isset( $_POST['post_ID'] ) || $post_id != $_POST['post_ID'] )	// Check Revision
		|| ( $post_type !=  WPSISAC_POST_TYPE ) )							// Check if current post type is supported.
		{
			return $post_id;
		}

		// Taking variables
		$read_more_link = isset($_POST['wpsisac_slide_link']) ? wpsisac_get_clean_url( $_POST['wpsisac_slide_link'] )  : '';

		update_post_meta($post_id, 'wpsisac_slide_link', $read_more_link);
	}

	/**
	 * Add custom column to slide listing page
	 * 
	 * @package WP Slick Slider and Image Carousel
	 * @since 1.0
	 */
	function wpsisac_slider_category_manage_columns($theme_columns) {
		$new_columns = array(
			'cb' => '<input type="checkbox" />',
			'name' => __('Name'),
			'slider_shortcode' => __( 'Slider Category Shortcode', 'wp-slick-slider-and-image-carousel' ),
			'slug' => __('Slug'),
			'posts' => __('Posts')
		);

		return $new_columns;
	}

	/**
	 * Add custom column data to slide listing page
	 * 
	 * @package WP Slick Slider and Image Carousel
	 * @since 1.0
	 */
	function wpsisac_slider_category_columns($out, $column_name, $theme_id) {
		$theme = get_term($theme_id, 'wpsisac_slider-category');
		switch ($column_name) {
			case 'title':
				echo get_the_title();
			break;
			case 'slider_shortcode':
				echo '[slick-slider category="' . $theme_id. '"]<br />';
				echo '[slick-carousel-slider category="' . $theme_id. '"]';
			break;
			default:
			break;
		}
		return $out;
	}
}

$wpsisac_admin = new Wpsisac_Admin();