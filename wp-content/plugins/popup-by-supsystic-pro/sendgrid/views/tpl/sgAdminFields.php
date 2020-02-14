<?php if(!$this->isSupported) { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_sendgrid">
		<th scope="row">
			<?php _e('SendGrid not supported', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('This module is not supported by your server configuration.', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<?php _e('SendGrid is not supported on your server', PPS_LANG_CODE)?>
		</td>
	</tr>
<?php } else { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_sendgrid">
		<th scope="row">
			<?php _e('SendGrid Username', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Your SendGrid name', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<?php echo htmlPps::password('sub_sg_api_name', array(
				'value' => $this->getModel()->getApiUsername(),
			))?>
		</td>
	</tr>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_sendgrid">
		<th scope="row">
			<?php _e('SendGrid Password', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Your SendGrid password', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<?php echo htmlPps::password('sub_sg_api_key', array(
				'value' => $this->getModel()->getApiPassword(),
			))?>
		</td>
	</tr>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_sendgrid">
		<th scope="row">
			<?php _e('Lists for subscribe', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Select lists for subscribe. They are taken from your SendGrid account.', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<div id="ppsSubSgListsShell" style="display: none;">
				<?php echo htmlPps::selectlist('params[tpl][sub_sg_lists]', array(
					'value' => (isset($this->popup['params']['tpl']['sub_sg_lists']) ? $this->popup['params']['tpl']['sub_sg_lists'] : ''),
					//'options' => array('' => __('Loading...', PPS_LANG_CODE)),
					'attrs' => 'id="ppsSubSgLists" class="chosen" data-placeholder="'. __('Choose Lists', PPS_LANG_CODE). '"',
				))?>
			</div>
			<span id="ppsSubSgMsg"></span>
		</td>
	</tr>
<?php }?>
