<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div id="wrap">
<div id="wrap-inner">

  <iframe src='<?php echo site_url( 'dashboard/'); ?>'></iframe>

<script>
  (function(){
    var iframe = document.querySelector('#wrap iframe');
    var appContainer = (iframe.contentDocument) ? iframe.contentDocument.getElementsByClassName('app-container')[0] : iframe.contentWindow.document.getElementsByClassName('app-container')[0];
    console.log(appContainer)
  })();

// background-color: rgba(0, 80, 0, 0.216);
</script>

</div>
</div>
