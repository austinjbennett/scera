<?php if(!$this->isLoggedIn) { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_verticalresponse">
		<th scope="row">
			<?php _e('Vertical Response Setup', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('You must authorize to use Vertical Response features', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<a class="button button-primary" href="<?php echo $this->authUrl?>"><?php _e('Authorize in Vertical Response')?></a>
		</td>
	</tr>
<?php } else { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_verticalresponse">
		<th scope="row">
			<?php _e('Lists for subscribe', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Select lists for subscribe. They are taken from your Vertical Response account.', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<div id="ppsSubVrListsShell" style="display: none;">
				<?php echo htmlPps::selectlist('params[tpl][sub_vr_lists]', array(
					'value' => (isset($this->popup['params']['tpl']['sub_vr_lists']) ? $this->popup['params']['tpl']['sub_vr_lists'] : ''),
					//'options' => array('' => __('Loading...', PPS_LANG_CODE)),
					'attrs' => 'id="ppsSubVrLists" class="chosen" data-placeholder="'. __('Choose Lists', PPS_LANG_CODE). '"',
				))?>
			</div>
			<span id="ppsSubVrMsg"></span>
		</td>
	</tr>
<?php }?>
