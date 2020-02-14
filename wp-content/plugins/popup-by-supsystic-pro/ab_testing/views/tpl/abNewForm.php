<!--Add one more test-->
<div id="ppsAbAddNewWnd" title="<?php _e('Add New Test', PPS_LANG_CODE)?>" style="display: none;">
	<form id="ppsAbAddNewForm">
		<label>
			<h3 style="float: left; margin: 10px;"><?php _e('Test PopUp Name', PPS_LANG_CODE)?>:</h3>
			<?php echo htmlPps::text('label', array('attrs' => 'style="float: left; width: 50%;"'))?>
		</label>
		<?php echo htmlPps::hidden('base_id', array('value' => $this->popup['id']))?>
		<?php echo htmlPps::hidden('mod', array('value' => 'ab_testing'))?>
		<?php echo htmlPps::hidden('action', array('value' => 'create'))?>
	</form>
	<div style="clear: both;"></div>
	<div id="ppsAbAddNewMsg"></div>
</div>
<!---->