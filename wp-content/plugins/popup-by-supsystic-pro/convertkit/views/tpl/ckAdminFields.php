<?php if(!$this->isSupported) { ?>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_convertkit">
	<th scope="row">
		<?php _e('Not supported by Server', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(__('Not supported on your server', PPS_LANG_CODE))?>"></i>
	</th>
	<td>
		<?php _e('This module require to have cUrl library for PHP on server installed. Please contact your hosting provider and ask them to enable cUrl for you, this is Free library.', PPS_LANG_CODE)?>
	</td>
</tr>	
<?php } else {?>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_convertkit">
	<th scope="row">
		<?php _e('API Key', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(__('You can find your API Key in the ConvertKit Account page.', PPS_LANG_CODE))?>"></i>
	</th>
	<td>
		<?php echo htmlPps::text('params[tpl][sub_ck_api_key]', array(
			'value' => (isset($this->popup['params']['tpl']['sub_ck_api_key']) ? $this->popup['params']['tpl']['sub_ck_api_key'] : ''),
		))?>
	</td>
</tr>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_convertkit">
	<th scope="row">
		<?php _e('Forms to Subscribe', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Select Forms for subscribe. They are taken from your ConvertKit account.', PPS_LANG_CODE)?>"></i>
	</th>
	<td>
		<div id="ppsSubCkListsShell" style="display: none;">
			<?php echo htmlPps::selectlist('params[tpl][sub_ck_lists]', array(
				'value' => (isset($this->popup['params']['tpl']['sub_ck_lists']) ? $this->popup['params']['tpl']['sub_ck_lists'] : ''),
				'attrs' => 'id="ppsSubCkLists" class="chosen" data-placeholder="'. __('Choose Forms', PPS_LANG_CODE). '"',
			))?>
		</div>
		<span id="ppsSubCkMsg"></span>
	</td>
</tr>
<?php }?>

