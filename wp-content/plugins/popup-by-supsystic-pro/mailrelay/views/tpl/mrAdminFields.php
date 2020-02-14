<?php if(!$this->isSupported) { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_mailrelay">
		<th scope="row">
			<?php _e('Mailrelay not supported', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('This module is not supported by your server configuration.', PPS_LANG_CODE))?>"></i>
		</th>
		<td>
			<?php _e('Mailrelay is not supported on your server', PPS_LANG_CODE)?>
		</td>
	</tr>
<?php } else { ?>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_mailrelay">
		<th scope="row">
			<?php _e('Mailrelay Host', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Please enter the host that you have in your Mairelay welcome email. Please enter it without the initial http:// (for example demo.ip-zone.com)', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<?php
				$host = $this->getModel()->getApiHost();
				if(empty($host)) {	// Let's set it's current host by default
					$siteUrlData = parse_url( get_bloginfo('url') );
					if(!empty($siteUrlData) && isset($siteUrlData['host'])) {
						$host = $siteUrlData['host'];
					}
				}
			?>
			<?php echo htmlPps::text('sub_mr_api_host', array(
				'value' => $host,
			))?>
		</td>
	</tr>
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_mailrelay">
		<th scope="row">
			<?php _e('Mailrelay API Key', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Please enter your API key. You can generate your API key on your Mailrelay panel, Configuration -> API access -> Generate API key', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<?php echo htmlPps::text('sub_mr_api_key', array(
				'value' => $this->getModel()->getApiKey(),
			))?>
		</td>
	</tr>
	
	<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_mailrelay">
		<th scope="row">
			<?php _e('Lists for subscribe', PPS_LANG_CODE)?>
			<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('To create new groups in Mailrelay, you must login into the control panel and click into the Mail Relay > Subscribers groups. Once there you can add a new group for your Wordpress users, or edit an existing one', PPS_LANG_CODE)?>"></i>
		</th>
		<td>
			<div id="ppsSubMrListsShell" style="display: none;">
				<?php echo htmlPps::selectlist('params[tpl][sub_mr_lists]', array(
					'value' => (isset($this->popup['params']['tpl']['sub_mr_lists']) ? $this->popup['params']['tpl']['sub_mr_lists'] : ''),
					'attrs' => 'id="ppsSubMrLists" class="chosen" data-placeholder="'. __('Choose Lists', PPS_LANG_CODE). '"',
				))?>
			</div>
			<span id="ppsSubMrMsg"></span>
		</td>
	</tr>
<?php }?>
