<?php
// create custom plugin settings menu
add_action('admin_menu', 'atw_create_menu');

function atw_create_menu() {

	//create new top-level menu
	add_options_page('Advanced Text Widget Settings', 'Advanced Text Widget', 'manage_options', __FILE__, 'atw_settings_page', 'atw_settings_page');

	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
}


function register_mysettings() {
	//register our settings
	register_setting( 'atw-settings-group', 'atw_title_option' );
	register_setting( 'atw-settings-group', 'atw_second_title_option' );
	register_setting( 'atw-settings-group', 'atw_class_option' );
	register_setting( 'atw-settings-group', 'atw_body_option' );
	register_setting( 'atw-settings-group', 'atw_link_option' );
}

function atw_settings_page() {
?>
<div class="wrap">
<h2>Advanced Text Widget Settings</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'atw-settings-group' ); ?>
    <?php do_settings_sections( 'atw-settings-group' ); ?>
	<h4>Customize which fields appear for the widget.</h4>
		
		<p><input id='atw_title_option' type="checkbox" name="atw_title_option" <?php if(get_option('atw_title_option')){echo 'checked';} ?> /> <label for='atw_title_option'>Title</label></p>
		
		<p><input id='atw_second_title_option' type="checkbox" name="atw_second_title_option" <?php if(get_option('atw_second_title_option')){echo 'checked';} ?> /> <label for='atw_second_title_option'>Second Title</label></p>

		<p><input id='atw_class_option' type="checkbox" name="atw_class_option" <?php if(get_option('atw_class_option')){echo 'checked';} ?> /> <label for='atw_class_option'>Class</label></p>
		
		<p><input id='atw_body_option' type="checkbox" name="atw_body_option" <?php if(get_option('atw_body_option')){echo 'checked';} ?> /> <label for='atw_body_option'>Body</label></p>
		
		<p><input id='atw_link_option' type="checkbox" name="atw_link_option" <?php if(get_option('atw_link_option')){echo 'checked';} ?> /> <label for='atw_link_option'>Link</label></p>
		
		
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>
