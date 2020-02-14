<?php if(!$this->isSupported) { ?>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_salesforce">
	<th scope="row">
		<?php _e('Not supported by Server', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(__('Not supported on your server', PPS_LANG_CODE))?>"></i>
	</th>
	<td>
		<?php _e('This module require to have cUrl library for PHP on server installed. Please contact your hosting provider and ask them to enable cUrl for you, this is Free library.', PPS_LANG_CODE)?>
	</td>
</tr>	
<?php } else {?>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_salesforce">
	<th scope="row">
		<?php _e('Form ID', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(sprintf(__('Generate your lead form in your Salesforce account (more about generating form you can read <a target="_blank" href="%s">here</a>), then copy "oid" value from it (<a target="_blank" href="%s">like this</a>) and insert it into this paramter', PPS_LANG_CODE), 'https://help.salesforce.com/apex/HTViewHelpDoc?id=setting_up_web-to-lead.htm&language=en', $this->helpImgUrl))?>"></i>
	</th>
	<td>
		<?php echo htmlPps::text('params[tpl][sub_sf_app_id]', array(
			'value' => (isset($this->popup['params']['tpl']['sub_sf_app_id']) ? $this->popup['params']['tpl']['sub_sf_app_id'] : ''),
		))?>
	</td>
</tr>
<?php }?>

