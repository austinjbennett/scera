<?php
/*
	Plugin Name: Accorss - Minimal Accordion Slider
	Description: Programmed to be easy and unintrusive, this plugin allows the user to add a horizontally styled accordion slider to their WordPress website.  Shortcode is [accorss].  Currently it only allows one, but support for multiple sliders can be added later.
	Version: 1.0
	Author: Evan Bradham
	Copyright 2013 evanbradham.org
*/
	
	// Admin page stuff
	include( plugin_dir_path( __FILE__ ) . '/options.php');


	// Accorss Shortcode
	// [accorss]
	class accorss_shortcode 
	{
		static $add_script;
		
		static function init() 
		{
			add_shortcode('accorss', array(__CLASS__, 'handle_shortcode'));

			add_action('init', array(__CLASS__, 'register_script'));
			add_action('wp_footer', array(__CLASS__, 'print_script'));
		}

		static function handle_shortcode( $content = null ) 
		{
			self::$add_script = true;
			
			$options = get_option('accorss_options');
			if( !$options )
			{
				return 'You need to set your slide settings in the admin.';
			}
			
			$return = '<div id="accorss">';
			for( $i=1; $i<=4; $i++ )
			{
				$active = '';
				switch ($i) {
					case 1:
						$active = ' active';
						$prefix = 'fs_';
						break;
					case 2:
						$prefix = 'ss_';
						break;
					case 3:
						$prefix = 'ts_';
						break;
					case 4;
						$prefix = 'rs_';
						break;
				}
			
				$return .= 
				'<div id="accorss-slide-'.$i.'" class="slide'.$active.'" style="background-image:url('.$options[$prefix.'background'].')">
					<img class="accorss-banner" src="'.$options[$prefix.'banner'].'">
					<div class="slide-content'.$active.'">
						<div class="slide-text">
							'.$options[$prefix.'primary'].'<span>'.$options[$prefix.'secondary'].'</span>
						</div>';
					if( isset($options[$prefix.'button_one']['main']) && $options[$prefix.'button_one']['main'] )
					{
						$return .=
						'<div class="button1">
							'. do_shortcode('[button color="'.$options[$prefix.'button_one']['color'].'" text_color="'.$options[$prefix.'button_one']['text_color'].'" text="'.$options[$prefix.'button_one']['text'].'" link="'.$options[$prefix.'button_one']['link'].'" class="small"]').'
						</div>';
					}
					if( $options[$prefix.'button_two']['main'] )
					{
						$return .=
						'<div class="button2">
							'. do_shortcode('[button color="'.$options[$prefix.'button_two']['color'].'" text_color="'.$options[$prefix.'button_two']['text_color'].'" text="'.$options[$prefix.'button_two']['text'].'" link="'.$options[$prefix.'button_two']['link'].'" class="small"]').'
						</div>';
					}
						$return .= 
					'</div>
					<div class="slide-cover"><div class="slide-cover-inner"></div></div>
				</div>';
			}
			$return .= '</div>';
			return $return;
		}

		static function register_script() 
		{
			wp_register_script('accorss_script', plugins_url('accorss.js', __FILE__), array('jquery'), '1.0', true);
			wp_register_style( 'accorss_style', plugins_url('accorss.css', __FILE__) );
		}

		static function print_script() 
		{
			if ( ! self::$add_script )
				return;

			wp_print_scripts('accorss_script');
			wp_enqueue_style('accorss_style');
		}
	}

	accorss_shortcode::init();
	
?>
