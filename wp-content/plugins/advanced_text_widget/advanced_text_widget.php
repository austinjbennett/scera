<?php
/**
 * Plugin Name: Advanced Text Widgets
 * Description: Text Widgets for Dummies
 * Version: 1.1.0
 * Author: Evan Bradham
 */


add_action( 'widgets_init', 'custom_text_widgets' );

include( plugin_dir_path( __FILE__ ) . '/options.php');

function custom_text_widgets() {
	register_widget( 'Custom_Text_Widget' );
}

class Custom_Text_Widget extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'custom-text-widget', 'description' => __('Add a custom text widget', 'custom-text-widget') );
		
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'custom-text-widget' );
		
		parent::__construct( 'custom-text-widget', __('Custom Text Widget', 'custom-text-widget'), $widget_ops, $control_ops );
	}
	
	function widget( $args, $instance ) {
		extract( $args );

		//Our variables from the widget settings.
		$title = apply_filters('widget_title', $instance['title'] );
		$lower_title = $instance['lower_title'];
		$class = $instance['class'];
		$body = $instance['body'];
		$link = $instance['link'];
		$link_text = $instance['link_text'];

		echo $before_widget;
		if( $class && get_option('atw_class_option') )
			echo '<div class="'. $class .'"><div class="'. $class .'-inner">';
		else
			echo '<div class="default-ctw"><div class="default-ctw-inner">';
		// Display the widget title 
		if ( $title && get_option('atw_title_option') )
			echo $before_title . $title . $after_title;

		if ( $lower_title && get_option('atw_second_title_option') )
			echo '<h4 class="lower-title">' . $lower_title . '</h4>';

		if ( $body && get_option('atw_body_option') )
			echo '<p>' . do_shortcode($body) . '</p>';
			
		if ( $link_text && get_option('atw_link_option') )
			echo '<a href="'.$link.'">' . $link_text . '</a>';

		
		if( $class && get_option('atw_link_option') )
			echo '</div></div>';
		
		echo $after_widget;
	}

	//Update the widget 
	 
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		//Strip tags from title and name to remove HTML 
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['lower_title'] = strip_tags( $new_instance['lower_title'] );
		$instance['class'] = strip_tags( $new_instance['class'] );
		$instance['body'] = $new_instance['body'];
		$instance['link'] = strip_tags( $new_instance['link'] );
		$instance['link_text'] = strip_tags( $new_instance['link_text'] );

		return $instance;
	}

	function form( $instance ) {

		//Set up some default widget settings.
		$defaults = array();
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<?php //Title
		if( get_option('atw_title_option') ){ ?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'custom-text-widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" /></p>
		<?php } ?>
		
		<?php //Lower Title
		if( get_option('atw_second_title_option') ){ ?>
			<p><label for="<?php echo $this->get_field_id( 'lower_title' ); ?>"><?php _e('Lower Title:', 'custom-text-widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'lower_title' ); ?>" name="<?php echo $this->get_field_name( 'lower_title' ); ?>" value="<?php echo $instance['lower_title']; ?>" style="width:100%;" /></p>
		<?php } ?>
		
		<?php //Class
		if( get_option('atw_class_option') ){ ?>
			<p><label for="<?php echo $this->get_field_id( 'class' ); ?>"><?php _e('Class:', 'custom-text-widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'class' ); ?>" name="<?php echo $this->get_field_name( 'class' ); ?>" value="<?php echo $instance['class']; ?>" style="width:100%;" /></p>
		<?php } ?>
		
		<?php //Body
		if( get_option('atw_body_option') ){ ?>
			<p><label for="<?php echo $this->get_field_id( 'body' ); ?>"><?php _e('Body:', 'custom-text-widget'); ?></label>
			<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id('body'); ?>" name="<?php echo $this->get_field_name('body'); ?>"><?php echo $instance['body']; ?></textarea></p>
		<?php } ?>
		
		<?php //Link
		if( get_option('atw_link_option') ){ ?>
			<p><label for="<?php echo $this->get_field_id( 'link_text' ); ?>"><?php _e('Link Text:', 'custom-text-widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link_text' ); ?>" name="<?php echo $this->get_field_name( 'link_text' ); ?>" value="<?php echo $instance['link_text']; ?>" style="width:100%;" /></p>
		
			<p><label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e('Paste Link URL:', 'custom-text-widget'); ?></label>
			<input id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" value="<?php echo $instance['link']; ?>" style="width:100%;" /></p>
		<?php } ?>
	<?php
	}
}
?>
