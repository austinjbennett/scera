<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="wrap">
<div id="wrap-inner">

<section class='roadmap'>
<h1>Roadmap</h1>
<br/>
	<div class='roadmap-inner'>
		<h2>In the works</h2>
		<p>We are currently working on changes to the ticketing system.</p>
		<br/>

		<h2>Planned</h2>
		<p>We plan on adding the following:</p>
		<ul>
			<li>• The ability to change your current plan.</li>
			<li>• Your payment history, and a way to update payment details</li>
			<li>• Live chat</li>
			<li>• Monitor logs for errors</li>
			<li>• Add more insights to the site health dashboard</li>
		</ul>
		<br/>

		<h2>Completed</h2>
		<p>The following features have already been added:</p>
		<ul>
			<li>✓ A ticketing system</li>
			<li>✓ The site health dashboard</li>
			<li>✓ Info about the plan you're signed up on</li>
			<li>✓ Custom login page that displays announcements</li>
		</ul>
		<br/>
	</div>
</section>
<br/><br/>

  <h1>Feedback / Suggest New Feature </h1>
  <form id="feedback-form" class='email-form' data-msg="Thank you for your feedback :)">
    <textarea name='body' rows=8 cols=80 required></textarea>
    <br />
    <input type=submit class='ow-btn'>
  </form>

	<br />
	<br />

	<h1>Leave a Testimonial </h1>
	<p>
		If you think we're doing a good job, we would appreciate it if you left a testimonial.
		<?php if ( ! hu_are_we_whitelabeled() ): ?>
			<br/>
			If your testimonial is used on our website, we will include a link back to your site.
		<?php endif; ?>
	</p>
  <form id="feedback-form" class='email-form' data-msg="Thanks. We appreciate you taking time to leave a testimonial.">
    <textarea name='body' rows=8 cols=80 required></textarea>
    <br />
    <input type=submit class='ow-btn'>
  </form>

  <?php $ajax_nonce = wp_create_nonce( 'ow-roadmap-feedback' ); ?>

  <script type="text/javascript" >
  jQuery('.email-form').submit(function() {
    event.preventDefault();
    var $form = jQuery(this)
    var data = {
      'security': '<?php echo $ajax_nonce ?>',
      'action': 'hu_roadmap_feedback',
      'message': $form.find('textarea').val(),
      'useremail': '<?php echo wp_get_current_user()->user_email ?>'
    };

    // ajaxurl is always defined in the admin header and points to admin-ajax.php
    jQuery.post(ajaxurl, data, function(response) {
      $form.html('<p>'+$form.data('msg')+'</p>')
    }).fail(function(xhr, status, err){console.log(status + ' ' + err);console.log(xhr);alert('There was an error. Please open a ticket, so we can get this fixed. 😥'); });
  });
  </script>

</div>
</div>

<?php if (! hu_are_we_whitelabeled()): ?>
	<br/>
	<br/>
	<img src="<?php echo hu_branding('logo'); ?>" alt="logo" class='ow-logo ow-logo-side' width="500px" style='margin:auto;display:block'>
<?php endif; ?>
