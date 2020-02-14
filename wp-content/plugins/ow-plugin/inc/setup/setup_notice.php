<?php

function ow_setup_error_notice() {
  if( is_wpoverwatch_user()) {
    ?>
    <div class="notice notice-error">
        <p><b>WP Overwatch Setup Error:</b> There was an error with the setup. <br/>
          You will have to manually fix the error, and rerun the setup process. <br/>
          There is an option to rerun the setup process in the options menu. <br/>
        </p>
    </div>
    <?php
  }
}
