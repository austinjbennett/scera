<label class="supsystic-tooltip-right" title="<?php echo esc_html(sprintf(__('Show when user tries to exit from your site. <a target="_blank" href="%s">Check example.</a>', PPS_LANG_CODE), 'http://supsystic.com/exit-popup/'))?>">
	<?php echo htmlPps::radiobutton('params[main][show_on]', array(
		'value' => 'on_exit',
		'checked' => htmlPps::checkedOpt($this->popup['params']['main'], 'show_on', 'on_exit')))?>
	<?php _e('On Exit from Site', PPS_LANG_CODE)?>
</label>