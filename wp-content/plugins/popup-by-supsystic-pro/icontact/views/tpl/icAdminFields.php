<?php if(!$this->isSupported) { ?>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_icontact">
	<th scope="row">
		<?php _e('Not supported by Server', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(__('Not supported on your server', PPS_LANG_CODE))?>"></i>
	</th>
	<td>
		<?php _e('This module require to have cUrl library for PHP on server installed. Please contact your hosting provider and ask them to enable cUrl for you, this is Free library.', PPS_LANG_CODE)?>
	</td>
</tr>	
<?php } else {?>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_icontact">
	<th scope="row">
		<?php _e('Application ID', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(sprintf(__('You can create it <a href="%s" target="_blank">here</a>. More info can be found <a href="%s" target="_blank">here</a>', PPS_LANG_CODE), 'https://app.icontact.com/icp/core/registerapp', 'http://help.limelightcrm.com/entries/315841-Configuring-iContact-API-2-0'))?>"></i>
	</th>
	<td>
		<?php echo htmlPps::text('params[tpl][sub_ic_app_id]', array(
			'value' => (isset($this->popup['params']['tpl']['sub_ic_app_id']) ? $this->popup['params']['tpl']['sub_ic_app_id'] : ''),
		))?>
	</td>
</tr>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_icontact">
	<th scope="row">
		<?php _e('API Username', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(sprintf(__('You can create it <a href="%s" target="_blank">here</a>. More info can be found <a href="%s" target="_blank">here</a>', PPS_LANG_CODE), 'https://app.icontact.com/icp/core/registerapp', 'http://help.limelightcrm.com/entries/315841-Configuring-iContact-API-2-0'))?>"></i>
	</th>
	<td>
		<?php echo htmlPps::text('params[tpl][sub_ic_app_user]', array(
			'value' => (isset($this->popup['params']['tpl']['sub_ic_app_user']) ? $this->popup['params']['tpl']['sub_ic_app_user'] : ''),
		))?>
	</td>
</tr>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_icontact">
	<th scope="row">
		<?php _e('API Password', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(sprintf(__('You can create it <a href="%s" target="_blank">here</a>. More info can be found <a href="%s" target="_blank">here</a>', PPS_LANG_CODE), 'https://app.icontact.com/icp/core/registerapp', 'http://help.limelightcrm.com/entries/315841-Configuring-iContact-API-2-0'))?>"></i>
	</th>
	<td>
		<?php echo htmlPps::text('params[tpl][sub_ic_app_pass]', array(
			'value' => (isset($this->popup['params']['tpl']['sub_ic_app_pass']) ? $this->popup['params']['tpl']['sub_ic_app_pass'] : ''),
		))?>
	</td>
</tr>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_icontact">
	<th scope="row">
		<?php _e('Lists for subscribe', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Select Lists for subscribe. They are taken from your iContact account.', PPS_LANG_CODE)?>"></i>
	</th>
	<td>
		<div id="ppsSubIcListsShell" style="display: none;">
			<?php echo htmlPps::selectlist('params[tpl][sub_ic_lists]', array(
				'value' => (isset($this->popup['params']['tpl']['sub_ic_lists']) ? $this->popup['params']['tpl']['sub_ic_lists'] : ''),
				'attrs' => 'id="ppsSubIcLists" class="chosen" data-placeholder="'. __('Choose Lists', PPS_LANG_CODE). '"',
			))?>
		</div>
		<span id="ppsSubIcMsg"></span>
	</td>
</tr>
<?php }?>

