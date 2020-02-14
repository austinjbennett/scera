<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

 if( isset($_POST['updated']) && $_POST['updated'] === 'true' ){

   /* verify nonce */
   if( ! isset( $_POST['hu_admin_options_nonce'] ) || ! wp_verify_nonce( $_POST['hu_admin_options_nonce'], 'hu_admin_options_nonce_action' ) ){
      ?>
      <div class="error">
          <p>	⤜(ⱺ ʖ̯ⱺ)⤏ We try to keep things secure around here, and this form failed to save securely.</p>
          <p>Please try to save again, and if it still does not work,
            <a href='<?php home_url( 'tickets', 'https' ); ?>'>submit a ticket to us</a>, so we can fix it.
          </p>
      </div>
      <?php
      exit;
    }

    	echo '<div class="notice notice-success ow-fade"><p><strong>Settings saved</strong></p></div>';

		$previous = get_option( 'hu_admin_settings', array() );
    	update_option( 'hu_admin_settings', $_POST['hu_admin_settings'], false );

		$changed = array();
		foreach ($_POST['hu_admin_settings'] as $k => $v){
			$prev_v = $previous[$k] ?? '';
			if ($prev_v != $v)
				$changed[$k] = $v;
		}
		foreach ($previous as $k => $v){
			$current_v = $_POST['hu_admin_settings'][$k] ?? '';
			if ($current_v != $v)
				$changed[$k] = $current_v;
		}

		/*
		  hook into the 'hu_settings_updated' action if you need to respond to changes made to an option.
		*/
		do_action( 'hu_settings_updated', $changed, $_POST['hu_admin_settings'] );
}

?>

<div id="wrap">
<div id="wrap-inner">

	<h1>Options</h1>

	<?php $hu_admin_settings = get_option('hu_admin_settings', array()); ?>
	<?php require dirname(__FILE__) . '/options.php'; ?>

	<?php do_action( 'hosting_utilities_general_options', $hu_admin_settings ); ?>

	<hr />
	<p style='text-align: center;'>Advanced Options</p>
	<hr />
	  <br />

	<form method="post">
		<!--<img src="https://wordpressoverwatch.com/logo.svg" alt="logo" class='ow-logo ow-logo-side'>-->
		<input type="hidden" name="updated" value="true" />
		<?php wp_nonce_field( 'hu_admin_options_nonce_action', 'hu_admin_options_nonce' ); ?>

		<table class="form-table ow-options-form">

			<?php if ( is_hosting_utilities_user() ) { ?>
				<?php do_action( 'hosting_utilities_secret_options', $hu_admin_settings ); ?>
			<?php } ?>

		</table>

		<?php submit_button('save', 'ow-btn'); ?>
	</form>

	<?php do_action('additional_option_forms'); ?>

</div>
</div>
