<?php if(!$this->isSupported) { ?>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_infusionsoft">
	<th scope="row">
		<?php _e('Not supported by Server', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(__('Not supported on your server', PPS_LANG_CODE))?>"></i>
	</th>
	<td>
		<?php _e('This module require to have cUrl library for PHP on server installed and PHP version to be at least 5.4. Please contact your hosting provider and ask them to enable cUrl for you, this is Free library, and check your PHP version.', PPS_LANG_CODE)?>
	</td>
</tr>	
<?php } elseif(!$this->isAutentificated) { ?>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_infusionsoft">
	<th scope="row">
		<?php _e('InfusionSoft Setup', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('You must authorize to use InfusionSoft features', PPS_LANG_CODE))?>"></i>
	</th>
	<td>
		<a class="button button-primary" href="<?php echo $this->authUrl?>"><?php _e('Authorize in InfusionSoft')?></a>
	</td>
</tr>	
<?php } else { ?>
<?php /*no used for now: it have some strange behaviour with 3companies - check http://community.infusionsoft.com/showthread.php/693-API-addToCampaign-Error for example*/ ?>
<?php /*?><tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_infusionsoft">
	<th scope="row">
		<?php _e('Companies for subscribe', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Enter Companies IDs for subscribe. You can get it from your InfusionSoft account -> Companies (Id column). You can enter several companies ids here, separate them by comma sign - ",".', PPS_LANG_CODE)?>"></i>
	</th>
	<td>
		<?php echo htmlPps::text('params[tpl][sub_is_companies]', array(
			'value' => (isset($this->popup['params']['tpl']['sub_is_companies']) ? $this->popup['params']['tpl']['sub_is_companies'] : ''),
		))?>
	</td>
</tr><?php */?>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_infusionsoft">
	<th scope="row">
		<?php _e('Tags for subscribe', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Enter Tags IDs for subscribe. You can get it from your InfusionSoft account -> Settings -> Tags (Id column). You can enter several tags ids here, separate them by comma sign - ",".', PPS_LANG_CODE)?>"></i>
	</th>
	<td>
		<?php echo htmlPps::text('params[tpl][sub_is_tags]', array(
			'value' => (isset($this->popup['params']['tpl']['sub_is_tags']) ? $this->popup['params']['tpl']['sub_is_tags'] : ''),
		))?>
	</td>
</tr>
<?php }?>