<?php if(!$this->isSupported) { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_constantcontact">
		<th scope="row">
			<?php _e('Constant Contact not supported', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('This module is not supported by your server configuration.', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<?php _e('Constant Contact require at least PHP version 5.3. Please contact your hosting provider and ask them to switch your PHP to version 5.3. or higher.', PPS_LANG_CODE)?>
		</td>
	</tr>
<?php } elseif(!$this->isLoggedIn) { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_constantcontact">
		<th scope="row">
			<?php _e('Constant Contact Setup', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('You must authorize to use Constant Contact features', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<a class="button button-primary" href="<?php echo $this->authObj->getAuthorizationUrl()?>"><?php _e('Authorize in Constant Contact')?></a>
		</td>
	</tr>
<?php } else { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_constantcontact">
		<th scope="row">
			<?php _e('Lists for subscribe', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Select lists for subscribe. They are taken from your Constant Contact account.', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<div id="ppsSubCcListsShell" style="display: none;">
				<?php echo htmlPps::selectlist('params[tpl][sub_cc_lists]', array(
					'value' => (isset($this->popup['params']['tpl']['sub_cc_lists']) ? $this->popup['params']['tpl']['sub_cc_lists'] : ''),
					'attrs' => 'id="ppsSubCcLists" class="chosen" data-placeholder="'. __('Choose Lists', PPS_LANG_CODE). '"',
				))?>
			</div>
			<span id="ppsSubCcMsg"></span>
		</td>
	</tr>
<?php }?>
