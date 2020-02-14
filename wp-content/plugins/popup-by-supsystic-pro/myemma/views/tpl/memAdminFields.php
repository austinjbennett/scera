<?php if(!$this->isSupported) { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_myemma">
		<th scope="row">
			<?php _e('Emma not supported', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('This module is not supported by your server configuration.', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<?php _e('Emma is not supported on your server, please nstall cUrl PHP Library on your server first - it\'s free.', PPS_LANG_CODE)?>
		</td>
	</tr>
<?php } else { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_myemma">
		<th scope="row">
			<?php _e('Emma Account ID', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Your account ID', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<?php echo htmlPps::text('params[tpl][sub_mem_acc_id]', array(
				'value' => (isset($this->popup['params']['tpl']['sub_mem_acc_id']) ? $this->popup['params']['tpl']['sub_mem_acc_id'] : ''),
			))?>
		</td>
	</tr>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_myemma">
		<th scope="row">
			<?php _e('Emma Public API key', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Your Public API key', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<?php echo htmlPps::text('params[tpl][sub_mem_pud_key]', array(
				'value' => (isset($this->popup['params']['tpl']['sub_mem_pud_key']) ? $this->popup['params']['tpl']['sub_mem_pud_key'] : ''),
			))?>
		</td>
	</tr>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_myemma">
		<th scope="row">
			<?php _e('Emma Private API key', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Your Private API key', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<?php echo htmlPps::password('params[tpl][sub_mem_priv_key]', array(
				'value' => (isset($this->popup['params']['tpl']['sub_mem_priv_key']) ? $this->popup['params']['tpl']['sub_mem_priv_key'] : ''),
			))?>
		</td>
	</tr>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_myemma">
		<th scope="row">
			<?php _e('Groups for subscribe', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('To create new groups in Mailrelay, you must login into the control panel and click into the Mail Relay > Subscribers groups. Once there you can add a new group for your Wordpress users, or edit an existing one', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<div id="ppsSubMemListsShell" style="display: none;">
				<?php echo htmlPps::selectlist('params[tpl][sub_mem_lists]', array(
					'value' => (isset($this->popup['params']['tpl']['sub_mem_lists']) ? $this->popup['params']['tpl']['sub_mem_lists'] : ''),
					'attrs' => 'id="ppsSubMemLists" class="chosen" data-placeholder="'. __('Choose Lists', PPS_LANG_CODE). '"',
				))?>
			</div>
			<span id="ppsSubMemMsg"></span>
		</td>
	</tr>
<?php }?>
