<?php if(!$this->isSupported) { ?>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_benchmarkemail">
	<th scope="row">
		<?php _e('Not supported by Server', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(__('Not supported on your server', PPS_LANG_CODE))?>"></i>
	</th>
	<td>
		<?php _e('This module require to have cUrl library for PHP on server installed. Please contact your hosting provider and ask them to enable cUrl for you, this is Free library.', PPS_LANG_CODE)?>
	</td>
</tr>	
<?php } else {?>

<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_benchmarkemail">
	<th scope="row">
		<?php _e('Benchmark Login', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(__('Login for your Benchmark account.', PPS_LANG_CODE))?>"></i>
	</th>
	<td>
		<?php echo htmlPps::text('sub_be_login', array(
			'value' => $this->getModel()->getApiUsername(),
			'attr' => 'style="width: 100%;"',
		))?>
	</td>
</tr>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_benchmarkemail">
	<th scope="row">
		<?php _e('Benchmark Password', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php echo esc_html(__('Password for your Benchmark account.', PPS_LANG_CODE))?>"></i>
	</th>
	<td>
		<?php echo htmlPps::text('sub_be_pass', array(
			'value' => $this->getModel()->getApiPassword(),
			'attr' => 'style="width: 100%;"',
		))?>
	</td>
</tr>
<tr class="ppsPopupSubDestOpts ppsPopupSubDestOpts_benchmarkemail">
	<th scope="row">
		<?php _e('Campaigns for subscribe', PPS_LANG_CODE)?>
		<i class="fa fa-question supsystic-tooltip-bottom" title="<?php _e('Select Campaigns for subscribe. They are taken from your Benchmark account.', PPS_LANG_CODE)?>"></i>
	</th>
	<td>
		<div id="ppsSubBeListsShell" style="display: none;">
			<?php echo htmlPps::selectlist('params[tpl][sub_be_lists]', array(
				'value' => (isset($this->popup['params']['tpl']['sub_be_lists']) ? $this->popup['params']['tpl']['sub_be_lists'] : ''),
				'attrs' => 'id="ppsSubBeLists" class="chosen" data-placeholder="'. __('Choose Lists', PPS_LANG_CODE). '"',
			))?>
		</div>
		<span id="ppsSubBeMsg"></span>
	</td>
</tr>
<?php }?>

