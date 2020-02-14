<!--Add/edit subscribe fields popup-->
<div id="ppsSfEditFieldsWnd" title="<?php _e('Subscribe Field Settings', PPS_LANG_CODE)?>" style="display: none;">
	<table class="form-table">
		<tr>
			<th scope="row">
				<?php _e('Name', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(sprintf(__('Name (key) for your field. This parameter is for system - to be able to determine the field. Use here only latin letters, numbers, symbols -_+ and space. For more info about this parameter - your can check <a href="%s" target="_blank">this page</a>.', PPS_LANG_CODE), 'http://supsystic.com/subscribe-custom-fields-builder/'))?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('name')?>
			</td>
		</tr>
		<tr class="ppsSfLabelShell">
			<th scope="row">
				<?php _e('Label', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Label that will be visible for your subscribers.', PPS_LANG_CODE))?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('label')?>
			</td>
		</tr>
		<tr class="ppsSfValueShell">
			<th scope="row">
				<?php _e('Value', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Default value for your field', PPS_LANG_CODE))?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('value')?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Html Type', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('This parameter will show - how we must render this field.', PPS_LANG_CODE))?>"></i>
			</th>
			<td>
				<?php echo htmlPps::selectbox('html', array(
					'options' => $this->availableHtmlTypes,
				))?>
			</td>
		</tr>
		<tr class="ppsSfFieldSelectOptsRow" style="display: none;">
			<th colspan="2">
				<?php _e('Select Options', PPS_LANG_CODE)?>
				<a class="button button-small ppsSfFieldSelectOptAddBtn">
					<i class="fa fa-plus"></i>
				</a>
			</th>
		</tr>
		<tr class="ppsSfFieldSelectOptsRow" style="display: none; height: auto;">
			<td colspan="2" style="padding: 0;">
				<div id="ppsSfFieldSelectOptsShell">
					<div id="ppsSfFieldSelectOptShellExl" class="ppsSfFieldSelectOptShell">
						<?php echo htmlPps::text('options[][name]', array(
							'placeholder' => __('Name', PPS_LANG_CODE),
							'disabled' => true,
						))?>
						<?php echo htmlPps::text('options[][label]', array(
							'placeholder' => __('Label', PPS_LANG_CODE),
							'disabled' => true,
						))?>
						<a href="#" class="button button-small ppsSfFieldSelectOptRemoveBtn" title="<?php _e('Remove', PPS_LANG_CODE)?>">
							<i class="fa fa-trash-o"></i>
						</a>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Mandatory', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Is this field mandatory to fill-in. If yes - then users will not be able to continue without filling-in this field.', PPS_LANG_CODE))?>"></i>
			</th>
			<td>
				<?php echo htmlPps::checkbox('mandatory', array(
					'value' => 1,
				))?>
			</td>
		</tr>
	</table>
</div>
<div id="ppsSfFieldToolbarExl" class="ppsSfFieldToolbar">
	<a class="ppsSfFieldSettingsBtn" href="#" title="<?php _e('Settings', PPS_LANG_CODE)?>">
		<i class="fa fa-gear"></i>
	</a>
	<a class="ppsSfFieldRemoveBtn" href="#" title="<?php _e('Remove', PPS_LANG_CODE)?>">
		<i class="fa fa-trash-o"></i>
	</a>
</div>