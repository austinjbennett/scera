<?php if(!$this->isSupported) { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_sgautorepondeur">
		<th scope="row">
			<?php _e('SG Autorepondeur not supported', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('This module is not supported by your server configuration.', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<?php _e('SG Autorepondeur is not supported on your server', PPS_LANG_CODE)?>
		</td>
	</tr>
<?php } else { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_sgautorepondeur">
		<th scope="row">
			<?php _e('SG Autorepondeur User ID', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('This info available on your home page in SG Autorepondeur', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<?php echo htmlPps::text('params[tpl][sub_sga_id]', array(
				'value' => (isset($this->popup['params']['tpl']['sub_sga_id']) ? $this->popup['params']['tpl']['sub_sga_id'] : ''),
			))?>
		</td>
	</tr>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_sgautorepondeur">
		<th scope="row">
			<?php _e('SG Autorepondeur List ID', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('This info available on your home page in SG Autorepondeur', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<?php echo htmlPps::text('params[tpl][sub_sga_list_id]', array(
				'value' => (isset($this->popup['params']['tpl']['sub_sga_list_id']) ? $this->popup['params']['tpl']['sub_sga_list_id'] : ''),
			))?>
		</td>
	</tr>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_sgautorepondeur">
		<th scope="row">
			<?php _e('SG Client Activation Code', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('This info available on the member\'s area in the top menu "my account" (at the bottom of the page)', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<?php echo htmlPps::text('params[tpl][sub_sga_activate_code]', array(
				'value' => (isset($this->popup['params']['tpl']['sub_sga_activate_code']) ? $this->popup['params']['tpl']['sub_sga_activate_code'] : ''),
			))?>
		</td>
	</tr>
<?php }?>
