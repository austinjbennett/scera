<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* unneccessary check, in the future change this to check the username, manage_options is already checked for at this point */
if ( !current_user_can( 'manage_options' ) )  {
  wp_die( __( 'The WP Overwatch plugin says hi. Unfortunately, it also says that you do not have sufficient permissions to access this page.' ) );
}

?>

<div id="wrap">

	<?php $page_prefix = ow_branding('url friendly'); ?>

  <a href='<?php echo admin_url("admin.php?page=$page_prefix-tickets"); ?>' data-affect='tickets'><span class=bckgrd></span><span class='inner'>Submit Ticket</a>
	<a href='<?php echo admin_url("admin.php?page=$page_prefix-my-tickets"); ?>' data-affect='my-tickets'><span class=bckgrd></span><span class='inner'>My Tickets</a>
  <a href='<?php echo admin_url("admin.php?page=$page_prefix-site-health-dashboard");?>' data-affect='dashboard'><span class=bckgrd></span><span class='inner'>Site Health Dashboard</span></a>
  <a href='<?php echo admin_url("admin.php?page=$page_prefix-plan"); ?>' data-affect='plan'><span class=bckgrd></span><span class='inner'>My Plan</span></a>
  <!--<a href='<?php echo admin_url("admin.php?page=$page_prefix-my-tickets"); ?>' data-affect='roadmap'><span class=bckgrd></span><span class='inner'>Feedback</span></a>-->

</div>

<script>
	jQuery(document).ready(function($){
		var $wpcontent = $('#wpcontent')
		$('#wrap a').hover(function(){
			$wpcontent.removeClass();
			$wpcontent.addClass( 'affect-' + $(this).data('affect') )
		})
	})
</script>
