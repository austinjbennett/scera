<?php

function hu_theme_option($ow_admin_settings){
    /* These options are made available to all admin users */

    $css_theme = get_user_meta(get_current_user_id(), 'ow_css_theme', true);
    $css_theme = empty($css_theme) ? DEFAULT_CSS_THEME : $css_theme;
    ?>
    <br />
    <div id='basicSettings'>
      <p><b>Theme:</b></p>
      <label><input type="radio" id="defaultCssTheme" name="css_theme" value="geometric" <?php if ($css_theme==='geometric'){ echo 'checked'; } ?>>Geometric</label> &nbsp;
      <label><input type="radio" id="blackCssTheme" name="css_theme" value="dark"<?php if ($css_theme==='dark'){ echo 'checked'; } ?>>Dark</label> &nbsp;
      <label><input type="radio" id="simpleCssTheme" name="css_theme" value="vibrant"<?php if ($css_theme==='vibrant'){ echo 'checked'; } ?>>Vibrant</label> &nbsp;
      <label><input type="radio" id="wordpressCssTheme" name="css_theme" value="wordpress"<?php if ($css_theme==='wordpress'){ echo 'checked'; } ?>>WordPress</label> &nbsp;
    </div>
    <script>
      document.getElementById('basicSettings').addEventListener('change', function(e){
        fetch( '<?php echo admin_url( 'admin-ajax.php' ) ?>?action=ow_basic_options&css_theme='+e.srcElement.value+'&nonce=<?php echo wp_create_nonce('basic-settings'); ?>', {
          method: 'POST',
          headers : new Headers({'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'}),
          credentials: 'same-origin', /* Must be present to enable cookies with the request */
        } )
          .then(
            function(response) {
              if (response.status !== 200) {
                console.log('Looks like there was a problem. Status Code: ' +
                  response.status);
                return;
              }

              // Examine the text in the response
              response.text().then(function(data) {
                var json = JSON.parse(data)
                if ( json.changed.includes('css_theme') ){
                  location.reload()
                }
              });
            }
          )
          .catch(function(err) {
            console.log('Fetch Error :-S', err);
          });

      })
    </script>
    <br />
    <?php
}
add_action( 'hosting_utilities_general_options', 'hu_theme_option', 50, 1 );


function change_hu_user($hu_admin_settings){
    ?>
    <tr valign="top">
        <th scope="row">Limit Advanced Options to</th>

        <td>
          <?php
            wp_dropdown_users(array(
              'role' => 'administrator',
              'show_option_none' => 'Not Set',
              'name' => 'hu_admin_settings[wpoverwatch_userid]',
              'selected' => $hu_admin_settings['wpoverwatch_userid'] ?? -1,
              'show' => 'display_name_with_login'
            ));
          ?>
          <p class="description">Set this user to yourself to make it so that only you are able to see the advanced options.
              <br/>Change it to 'Not Set' to allow anyone to modify the options on this page.
          </p>
          <p class="description"><b>Warning:</b> If you choose someone else, you won't be able to see this option anymore.
          </p>
        </td>
    </tr>

    <?php
}
add_action( 'hosting_utilities_secret_options', 'change_hu_user', 50, 1 );


function hu_options($hu_admin_settings){
  ?>

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
      <?php $migration_mode_enabled = isset($hu_admin_settings['migration_mode']) && $hu_admin_settings['migration_mode'] == true; ?>
      <input type="checkbox" name="hu_admin_settings[migration_mode]" value=true <?php echo $migration_mode_enabled ? 'checked' : ''; ?> />
      <p class=description>Display a maintenance page about updating the website to anyone else who tries to access the backend.</p>
    </td>
  </tr>

  <tr valign="top" class='maintenance-mode'>
    <th scope="row">Maintenance Mode</th>
    <td>
      <?php $maintenance_mode_enabled = isset($hu_admin_settings['maintenance_mode']) && $hu_admin_settings['maintenance_mode'] == true; ?>
      <input type="checkbox" name="hu_admin_settings[maintenance_mode]" value=true <?php echo $maintenance_mode_enabled ? 'checked' : ''; ?> />
      <p class=description>Display a maintenance page to everybody else on the frontend.</p>
    </td>
  </tr>

  <tr valign="top" class='maintenance-mode-msg hidden'>
    <th scope="row">Maintenance Mode Message</th>
    <td>
      <input type=text name="hu_admin_settings[maintenance_mode_message]" value="<?php echo $hu_admin_settings['maintenance_mode_message'] ?? ''; ?>" style='width: 100%;' />
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
            <p>Other</p>
            <hr />
              <br />
          </th>
      </tr>

      <tr valign="top">
          <th scope="row">Default Login Page</th>
          <td>
              <?php $checked = isset($hu_admin_settings['default_login_page']) && $hu_admin_settings['default_login_page'] == true; ?>
              <input type="checkbox" name="hu_admin_settings[default_login_page]" value=true <?php echo $checked ? 'checked' : ''; ?> />
              <p class=description>Revert back to using the default WordPress login page</p>
          </td>
      </tr>


  <?php
}
add_action( 'hosting_utilities_secret_options', 'hu_options', 100, 1 );
