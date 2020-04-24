<?php 
/**
 * 'slick-slider' Shortcode
 * 
 * @package WP Slick Slider and Image Carousel

 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wpsisac_get_slick_slider( $atts, $content = null ){

	// Shortcode Parameter
	extract(shortcode_atts(array(
	    'limit'    			=> '-1',
		'category' 			=> '',
		'design' 			=> 'design-1',
		'image_size' 		=> 'full',
		'show_content' 		=> 'true',
		'dots'     			=> 'true',
		'arrows'     		=> 'true',
		'autoplay'     		=> 'true',
		'loop'              => 'true',
		'hover_pause'       => 'true',
		'autoplay_interval' => 3000,
		'speed'             => 300,
		'fade'		        => 'false',
		'sliderheight'     	=> '',
		'image_fit' 		=> 'true',
		'lazyload'          => '',
		'rtl'               => '',
		'extra_class'		=> '',
		'className'			=> '',
		'align'				=> '',
	), $atts, 'slick-slider'));

    $shortcode_designs 	= wpsisac_slider_designs();
	$limit 				= ! empty($limit) 					? $limit 								: '-1';
	$cat 				= ( ! empty($category)) 			? explode(',', $category) 				: '';
	$design 			= ( $design && (array_key_exists(trim($design), $shortcode_designs)) ) 		? trim($design) : 'design-1';
	$show_content 		= ( $show_content == 'true' ) 		? true 									: false;
	$dots 				= ( $dots == 'false' ) 				? 'false' 								: 'true';
	$arrows 			= ( $arrows == 'false' ) 			? 'false' 								: 'true';
	$autoplay 			= ( $autoplay == 'false' ) 			? 'false' 								: 'true';
	$loop 				= ( $loop == 'false' ) 				? 'false' 								: 'true';
	$hover_pause 		= ( $hover_pause == 'false' ) 		? 'false' 								: 'true';
	$autoplay_interval 	= ( ! empty($autoplay_interval) ) 	? $autoplay_interval 					: 3000;
	$speed 				= ( ! empty($speed) ) 				? $speed 								: 300;
	$fade 				= ( $fade == 'true' ) 				? 'true' 								: 'false';
	$lazyload 			= ( $lazyload == 'ondemand' || $lazyload == 'progressive' ) ? $lazyload 	: ''; // ondemand or progressive
	$image_fit			= ( $image_fit == 'false' )			? 0										: 1;
	$sliderheight 		= ( ! empty($sliderheight)) 		? $sliderheight 						: '';
	$slider_height_css 	= ( ! empty($sliderheight))			? "style='height:{$sliderheight}px;'"	: '';
	$sliderimage_size 	= ! empty($image_size) 				? $image_size 							: 'full';
	$align				= ( ! empty( $align ) )				? 'align'.$align						: '';
	$extra_class		= $align .' '. wpsisac_get_sanitize_html_classes($className);

	// For RTL
	if( empty($rtl) && is_rtl() ) {
		$rtl = 'true';
	} elseif ( $rtl == 'true' ) {
		$rtl = 'true';
	} else {
		$rtl = 'false';
	}

	// Shortcode file
	$design_file_path 	= WPSISAC_DIR . '/templates/' . $design . '.php';
	$design_file 		= (file_exists($design_file_path)) ? $design_file_path : '';

	// Enqueus required script
	wp_enqueue_script( 'wpos-slick-jquery' );
	wp_enqueue_script( 'wpsisac-public-script' );

	// Taking some variables
	$image_fit_class = ( $image_fit ) ? 'wpsisac-image-fit'	: '';

	// Slider configuration
	$slider_conf = compact('dots', 'arrows', 'autoplay', 'autoplay_interval', 'fade', 'lazyload', 'speed', 'rtl', 'loop', 'hover_pause');

	ob_start();	

	// Taking some global
	global $post;

	// Taking some variables
	$unique 	= wpsisac_get_unique();
	$post_type 	= WPSISAC_POST_TYPE;
	$orderby 	= 'post_date';
	$order 		= 'DESC';

	// WP Query Parameters
    $args = array ( 
        'post_type'      => $post_type,
        'orderby'        => $orderby,
        'order'          => $order,
        'posts_per_page' => $limit,
    );

    // Category Parameter
	if($cat != ""){
    	$args['tax_query'] = array( 
    						array( 	'taxonomy' 	=> 'wpsisac_slider-category',
    								'field' 	=> 'id',
    								'terms' 	=> $cat
    					) );
    }

    // WP Query Parameters
    $query = new WP_Query($args);
    $post_count = $query->post_count;

    // If post is there
    if ( $query->have_posts() ) : ?>
		<div class="wpsisac-slick-slider-wrp wpsisac-clearfix <?php echo $extra_class; ?>" data-conf="<?php echo htmlspecialchars(json_encode($slider_conf)); ?>">
			<div id="wpsisac-slick-slider-<?php echo $unique; ?>" class="wpsisac-slick-slider <?php echo $design; ?> <?php echo $image_fit_class; ?>">
				<?php while ( $query->have_posts() ) : $query->the_post();
					$slider_img = wpsisac_get_post_featured_image( $post->ID, $sliderimage_size, true );
						// Include shortcode html file
						if( $design_file ) {
							include( $design_file );
						}
				endwhile; ?>
			</div>
		</div>
	<?php
    endif; 
    wp_reset_postdata(); // Reset WP Query
	return ob_get_clean();
}
// 'slick-slider' shortcode
add_shortcode('slick-slider','wpsisac_get_slick_slider');