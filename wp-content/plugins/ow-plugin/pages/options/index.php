<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once OW_PLUGIN_PATH.'inc/setup/modify_this_when_changes_are_made.php'; /* setup versioning info */

 if( isset($_POST['updated']) && $_POST['updated'] === 'true' ){

   /* verify nonce */
   if( ! isset( $_POST['ow_admin_options_nonce'] ) || ! wp_verify_nonce( $_POST['ow_admin_options_nonce'], 'ow_admin_options_nonce_action' ) ){
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

		$previous = get_option( 'ow_admin_settings', array() );
    	update_option( 'ow_admin_settings', $_POST['ow_admin_settings'], false );

		$changed = array();
		foreach ($_POST['ow_admin_settings'] as $k => $v){
			$prev_v = $previous[$k] ?? '';
			if ($prev_v != $v)
				$changed[$k] = $v;
		}
		foreach ($previous as $k => $v){
			$current_v = $_POST['ow_admin_settings'][$k] ?? '';
			if ($current_v != $v)
				$changed[$k] = $current_v;
		}

		/*
		  hook into the 'ow_settings_updated' action if you need to respond to changes made to an option.
		*/
		do_action( 'ow_settings_updated', $changed, $_POST['ow_admin_settings'] );
}

?>

<div id="wrap">
<div id="wrap-inner">

  <h1>Options</h1>

  <?php require 'public_options.php'; ?>

  <?php $ow_admin_settings = get_option('ow_admin_settings', array()); ?>

  <?php if ( is_wpoverwatch_user() ) { ?>

  <hr />
  <p style='text-align: center;'>Advanced Options</p>
  <hr />
	<br />

  <form method="post">
    <!--<img src="https://wordpressoverwatch.com/logo.svg" alt="logo" class='ow-logo ow-logo-side'>-->
    <input type="hidden" name="updated" value="true" />
    <?php wp_nonce_field( 'ow_admin_options_nonce_action', 'ow_admin_options_nonce' ); ?>

  <table class="form-table ow-options-form">

    <tr valign="top">
      <th scope="row">WP Overwatch User</th>

      <td>
        <?php
          wp_dropdown_users(array(
            'role' => 'administrator',
            'show_option_none' => 'Not Set',
            'name' => 'ow_admin_settings[wpoverwatch_userid]',
            'selected' => $ow_admin_settings['wpoverwatch_userid'] ?? -1,
            'show' => 'display_name_with_login'
          ));
        ?>
        <p class="description">This user will be able to manage additional options.</p>
        <p class="description"><b>Warning:</b> If you choose someone else,<br/> you won't be able to see this option anymore.</p>
      </td>
    </tr>

		<tr valign="top">
      <th scope="row">WP-Overwatch API Key/Token</th>
      <td>
        <input type="text" name="ow_admin_settings[api_key]" value='<?php echo $ow_admin_settings['api_key'] ?? '' ?>' />
				<a style="margin-left: 6px;" href="https://missioncontrol.wp-overwatch.com/my-org" target="_blank">Create Token</a>
        <p class=description>The API key needs to be added before for the other plugin pages to work properly.</p>
      </td>
    </tr>

		<tr valign="top" class='sub-section'>
			<th scope="row">
				<br />
				<hr />
			  <p>Lockouts</p>
			  <hr />
				<br />
			</th>
		</tr>

    <tr valign="top">
      <th scope="row">Migration Mode</th>
      <td>
        <?php $migration_mode_enabled = isset($ow_admin_settings['migration_mode']) && $ow_admin_settings['migration_mode'] == true; ?>
        <input type="checkbox" name="ow_admin_settings[migration_mode]" value=true <?php echo $migration_mode_enabled ? 'checked' : ''; ?> />
        <p class=description>Display a maintenance page about updating the website to anyone else who tries to access the backend.</p>
      </td>
    </tr>

    <tr valign="top" class='maintenance-mode'>
      <th scope="row">Maintenance Mode</th>
      <td>
        <?php $maintenance_mode_enabled = isset($ow_admin_settings['maintenance_mode']) && $ow_admin_settings['maintenance_mode'] == true; ?>
        <input type="checkbox" name="ow_admin_settings[maintenance_mode]" value=true <?php echo $maintenance_mode_enabled ? 'checked' : ''; ?> />
        <p class=description>Display a maintenance page to everybody else on the frontend.</p>
      </td>
    </tr>

	<tr valign="top" class='maintenance-mode-msg hidden'>
      <th scope="row">Maintenance Mode Message</th>
      <td>
        <input type=text name="ow_admin_settings[maintenance_mode_message]" value="<?php echo $ow_admin_settings['maintenance_mode_message']; ?>" style='width: 100%;' />
        <p class=description></p>
      </td>
    </tr>
	<script>
		jQuery(document).ready(function($){
			if ( $('.maintenance-mode input:checked').length ){
				$('.maintenance-mode-msg').removeClass('hidden');
			}
			$('.maintenance-mode').click(function(){
				$('.maintenance-mode-msg').toggleClass('hidden');
			})
		})
	</script>

		<tr valign="top" class='sub-section'>
			<th scope="row">
				<br />
				<hr />
			  <p>Site Health Dashboard</p>
			  <hr />
				<br />
			</th>
		</tr>

		<tr valign="top">
			<th scope="row">Show Sales</th>
			<td>
				<?php $checked = isset($ow_admin_settings['site_health_show_sales']) && $ow_admin_settings['site_health_show_sales'] == true; ?>
				<input type="checkbox" name="ow_admin_settings[site_health_show_sales]" value=true <?php echo $checked ? 'checked' : ''; ?> />
				<p class=description>Check to show WooCommerce sales in the site health dashboard.</p>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row">Activity Feed</th>
			<td>
				<?php $checked = isset($ow_admin_settings['activity_feed']) && $ow_admin_settings['activity_feed'] == true; ?>
				<input type="checkbox" name="ow_admin_settings[activity_feed]" value=true <?php echo $checked ? 'checked' : ''; ?> />
				<p class=description>Check to show the activity feed in the site health dashboard.</p>
			</td>
		</tr>

		<!-- Auto updates are not working properly. Using iThemes Security for this instead.
		<tr valign="top" class='sub-section'>
			<th scope="row">
				<br />
				<hr />
			  <p>Auto Updates</p>
			  <hr />
				<br />
			</th>
		</tr>

		<tr valign="top">
			<th scope="row">Auto Update Plugins</th>
			<td>
				<?php $checked = isset($ow_admin_settings['auto_update_plugins']) && $ow_admin_settings['auto_update_plugins'] == true; ?>
				<input type="checkbox" name="ow_admin_settings[auto_update_plugins]" value=true <?php echo $checked ? 'checked' : ''; ?> />
				<p class=description>Check to enable automatic updates.</p>
			</td>
		</tr>

		<tr valign="top">
      <th scope="row">Exclude Plugin</th>
      <td>
        <input type="text" name="ow_admin_settings[exclude_plugin_updates]" value='<?php echo $ow_admin_settings['exclude_plugin_updates'] ?? '' ?>' />
        <p class=description>Enter a comman separated list of plugin slugs to exclude from the automatic updates.</p>
      </td>
    </tr>

		<tr valign="top">
			<th scope="row">Auto Update Themes</th>
			<td>
				<?php $checked = isset($ow_admin_settings['auto_update_themes']) && $ow_admin_settings['auto_update_themes'] == true; ?>
				<input type="checkbox" name="ow_admin_settings[auto_update_themes]" value=true <?php echo $checked ? 'checked' : ''; ?> />
				<p class=description>Check to enable automatic updates.</p>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row">Auto Update WordPress Core</th>
			<td>
				<?php $checked = isset($ow_admin_settings['auto_update_core']) && $ow_admin_settings['auto_update_core'] == true; ?>
				<input type="checkbox" name="ow_admin_settings[auto_update_core]" value=true <?php echo $checked ? 'checked' : ''; ?> />
				<p class=description>Check to enable automatic updates.</p>
			</td>
		</tr>
		-->

		<tr valign="top" class='sub-section'>
			<th scope="row">
				<br />
				<hr />
			  <p>Other</p>
			  <hr />
				<br />
			</th>
		</tr>

		<tr valign="top">
			<th scope="row">Hide Update Notices</th>
			<td>
				<?php $checked = isset($ow_admin_settings['hide_update_notices']) && $ow_admin_settings['hide_update_notices'] == true; ?>
				<input type="checkbox" name="ow_admin_settings[hide_update_notices]" value=true <?php echo $checked ? 'checked' : ''; ?> />
				<p class=description>Hide notices that contain words such as "update", "upgrade", etc.</p>
				<p class=description>We can already see what plugins need to be updated from the plugins page. The notices are just unneccessary advertisements.</p>
			</td>
		</tr>

		<tr valign="top">
			<th scope="row">Default Login Page</th>
			<td>
				<?php $checked = isset($ow_admin_settings['default_login_page']) && $ow_admin_settings['default_login_page'] == true; ?>
				<input type="checkbox" name="ow_admin_settings[default_login_page]" value=true <?php echo $checked ? 'checked' : ''; ?> />
				<p class=description>Revert back to using the default WordPress login page</p>
			</td>
		</tr>

  </table>

  <?php submit_button('save', 'ow-btn'); ?>

  </form>

	<br />
	<hr />
  <p style='text-align: center;'>Setup</p>
  <hr />
	<br />
	<p>Adds rewrite rules to the .htaccess file, installs the maintenance dropin, and stores branding/whitelabeling info.</p>
	<p>If one of the below setup stages becomes outdated, the setup process is automatically rerun. </p>
	<p>If there is an error, the setup process has to be manually rerun.</p>

	<?php
		$setup = get_option('ow_setup_versions');

		if ( isset($setup['success']) ){
			echo '<ul>';
			foreach($setup as $stage => $version){
				echo "<li><b>$stage:</b> " . ($version ? "version $version" : '<font color=red>installation failed</font>') . '</li>';
			}
			echo '</ul>';
		}

		if (isset($setup['success']) && ! $setup['success']){
			echo '<p>✘ There was an error with the initial setup. Click the button below to try to rerun the setup process.</p>';
      require_once OW_PLUGIN_PATH.'inc/setup/setup_notice.php';
      add_action( 'admin_notices', 'ow_setup_error_notice' );
		} else if (isset($setup['success']) && $setup['success'] == true){
			echo '<p>✓ The initial setup has been performed. Click the button below if you would like to rerun the setup process.</p>';
		} else{
			echo '<p>✘ The setup process has not yet been run.</p>';
		}
	?>
	<input type=submit class='button ow-btn' value="Run Setup" id='setup-btn' />

	<script type="text/javascript" >
		<?php $ajax_nonce = wp_create_nonce( 'ow-setup' ); ?>
		jQuery('#setup-btn').click(function() {
			$btn = jQuery(this)

			event.preventDefault();
			var data = {
				'security': '<?php echo $ajax_nonce ?>',
				'action': 'ow_setup',
			};

			// ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(ajaxurl, data, function(response) {
				$btn.val('Setup Complete')
				$btn.attr('disabled', '')
			}).fail(function(xhr, status, err){console.log(status + ' ' + err);console.log(xhr);alert('There was an error. The server response is on the dev tools console'); })
			.success(function(){location.reload()});
		});
	</script>

	<?php } ?>

</div>
</div>
