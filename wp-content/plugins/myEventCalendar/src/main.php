<?php
	defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

	// CSS
	add_action('wp_enqueue_scripts','addMyCss');
	function addMyCss(){
		wp_register_style('mainCal',plugins_url('css/mainCal.css',__FILE__));
		// wp_register_style('mobileCal',plugins_url('css/mobileCal.css',__FILE__));		
		// wp_register_style('mobileCal',plugins_url('css/mobileCalSCSS.scss',__FILE__));
		wp_register_style('mobileCal',plugins_url('css/mobileCalSCSS.css',__FILE__));		
		// wp_enqueue_style('mainCal');
	}

	// JS
	add_action('wp_enqueue_scripts','addMyJs');
	function addMyJs(){
		wp_register_script('mainCalJs',plugins_url('js/mainCal.js',__FILE__));
		wp_register_script('swipeHandler',plugins_url('js/swipeHandler.js',__FILE__));		
		// wp_enqueue_script('mainCalJs');
		// wp_localize_script('mainCalJs', 'myScript', array(
	 //    	'pluginsUrl' => plugins_url().'/myEventCalendar/src/',
		// ));
	}

	// INIT
	add_action('init','addShortCodes');
	function addShortCodes(){
		// add_shortcode('myEventCalendar','getMyCalendar');
		add_shortcode('myEventCalendar','loadCalendar');

	}

	function loadCalendar(){
		wp_enqueue_style('mainCal');
		wp_enqueue_style('mobileCal');

		wp_enqueue_script('swipeHandler');

		wp_enqueue_script('mainCalJs');
		wp_localize_script('mainCalJs', 'myScript', array(
	    	'pluginsUrl' => plugins_url().'/myEventCalendar/src/',
		));
		// wp_localize_script('swipeHandler', 'myScript', array(
	 //    	'pluginsUrl' => plugins_url().'/myEventCalendar/src/',
		// ));
		
		getMyCalendar();
	}

?>