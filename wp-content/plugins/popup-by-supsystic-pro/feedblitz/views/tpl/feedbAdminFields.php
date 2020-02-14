<?php if(!$this->isSupported) { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_feedblitz">
		<th scope="row">
			<?php _e('FeedBlitz not supported', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('This module is not supported by your server configuration.', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<?php _e('FeedBlitz is not supported on your server', PPS_LANG_CODE)?>
		</td>
	</tr>
<?php } else { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_feedblitz">
		<th scope="row">
			<?php _e('FeedBlitz API Key', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Your FeedBlitz API Key', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<?php echo htmlPps::text('params[tpl][sub_feedb_key]', array(
				'value' => (isset($popup['params']['tpl']['sub_feedb_key']) ? $popup['params']['tpl']['sub_feedb_key'] : ''),
			))?>
		</td>
	</tr>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_feedblitz">
		<th scope="row">
			<?php _e('Lists for subscribe', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Select lists for subscribe. They are taken from your FeedBlitz account.', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<div id="ppsSubFeedbListsShell" style="display: none;">
				<?php echo htmlPps::selectlist('params[tpl][sub_feedb_lists]', array(
					'value' => (isset($this->popup['params']['tpl']['sub_feedb_lists']) ? $this->popup['params']['tpl']['sub_feedb_lists'] : ''),
					'attrs' => 'id="ppsSubFeedbLists" class="chosen" data-placeholder="'. __('Choose Lists', PPS_LANG_CODE). '"',
				))?>
			</div>
			<span id="ppsSubFeedbMsg"></span>
		</td>
	</tr>
<?php }?>
