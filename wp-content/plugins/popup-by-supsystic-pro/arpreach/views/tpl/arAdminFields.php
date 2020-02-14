<?php if(!$this->isSupported) { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_arpreach">
		<th scope="row">
			<?php _e('arpReach not supported', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('This module is not supported by your server configuration.', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<?php _e('arpReach is not supported on your server.', PPS_LANG_CODE)?>
		</td>
	</tr>
<?php } else { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_arpreach">
		<th scope="row">
			<?php _e('arpReach intake form Action URL', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_attr(__('Open your script for intake form, find there form tag with form method=\'post\' action=\'http://yourdomain.coma/a/a.php/sub/a/xxxxxx\', then copy "action" attribute - and paste it in text field below.', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<?php echo htmlPps::text('params[tpl][sub_ar_form_action]', array(
				'value' => (isset($this->popup['params']['tpl']['sub_ar_form_action']) ? $this->popup['params']['tpl']['sub_ar_form_action'] : ''),
				'attrs' => 'placeholder="http://yourdomain.coma/a/a.php/sub/a/xxxxxx" style="width: 100%;"',
			))?>
		</td>
	</tr>
<?php }?>
