<?php

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


<div id='hiddenPlugins'>
  <p><b>Show Hidden Plugins:</b></p>

  <?php
    global $ow_removed_plugins;
    $ow_removed_plugins = $ow_removed_plugins ?? [];

    if (isset($_COOKIE['show_hidden_plugins'])){
      $time_remaining = abs( ( time() - $_COOKIE['show_hidden_plugins'] ) / 3600 );
      if ($time_remaining > 1){
        $time_remaining =  round( $time_remaining, $precision=0 );
      } else{
        $time_remaining =  round( $time_remaining, $precision=2 );
      }

    ?>
    <p>To keep the WordPress backend less cluttered and loading faster, we normally hide the settings pages of the following plugins.</p>
    <?php echo "<ul style='margin-left: 2em;'><li>" . implode( "</li><li>", array_keys($ow_removed_plugins) ) . "</li></ul>"; ?>
    <p>For the next <?php echo $time_remaining ?> hours these plugins are being shown.</p>

  <?php } else { ?>

    <p>To keep the WordPress backend less cluttered and loading faster, we have hidden the settings pages of the following plugins.</p>
    <?php echo "<ul style='margin-left: 2em;'><li>" . implode( "</li><li>", $ow_removed_plugins ) . "</li></ul>"; ?>
    <p>If you would like to see these pages, check the box below. </p>

    <label><input type="checkbox" id="showHiddenPlugins" name="show_hidden_plugins" value="true">Show hidden plugins for the next 12 hours</label>
  <?php } ?>

</div>
<br />
<br />
<script>
  document.getElementById('hiddenPlugins').addEventListener('change', function(e){
    fetch( '<?php echo admin_url( 'admin-ajax.php' ) ?>?action=ow_show_hidden_plugins&nonce=<?php echo wp_create_nonce('ow-show-hidden-plugins'); ?>', {
      method: 'POST',
      headers : new Headers({'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8'}),
      credentials: 'same-origin', /* Must be present to enable cookies with the request */
    } )
      .then(
        function(response) {

          if (response.status !== 200) {
            console.log('There was a problem. Status Code: ' + response.status);
          }

          // Examine the text in the response
          response.text().then(function(data) {
            if (data === 'success'){
              location.reload()
            } else{
              alert('Ouch. There was a failure enabling hidden plugins.')
            }
          });
        }
      )
      .catch(function(err) {
        console.log('Fetch Error :', err);
        alert('Ouch! There was a failure enabling hidden plugins.')
      });

  })
</script>
