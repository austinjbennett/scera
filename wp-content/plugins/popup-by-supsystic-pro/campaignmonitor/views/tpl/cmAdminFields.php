<?php if(!$this->isSupported) { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_campaignmonitor">
		<th scope="row">
			<?php _e('Campaign Monitor not supported', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('This module is not supported by your server configuration.', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<?php _e('Campaign Monitor require at least PHP version 5.3. Please contact your hosting provider and ask them to switch your PHP to version 5.3. or higher.', PPS_LANG_CODE)?>
		</td>
	</tr>
<?php } elseif(!$this->isLoggedIn) { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_campaignmonitor">
		<th scope="row">
			<?php _e('Campaign Monitor Setup', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('You must authorize to use Campaign Monitor features', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<a class="button button-primary" href="<?php echo $this->authUrl?>"><?php _e('Authorize in Campaign Monitor')?></a>
		</td>
	</tr>
<?php } else { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_campaignmonitor">
		<th scope="row">
			<?php _e('Lists for subscribe', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Select lists for subscribe. They are taken from your Campaign Monitor account.', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<div id="ppsSubCmListsShell" style="display: none;">
				<?php echo htmlPps::selectlist('params[tpl][sub_cm_lists]', array(
					'value' => (isset($this->popup['params']['tpl']['sub_cm_lists']) ? $this->popup['params']['tpl']['sub_cm_lists'] : ''),
					//'options' => array('' => __('Loading...', PPS_LANG_CODE)),
					'attrs' => 'id="ppsSubCmLists" class="chosen" data-placeholder="'. __('Choose Lists', PPS_LANG_CODE). '"',
				))?>
			</div>
			<span id="ppsSubCmMsg"></span>
		</td>
	</tr>
<?php }?>
