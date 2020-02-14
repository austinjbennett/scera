<div class="description"><?php _e('This option will add Login / Registration forms', PPS_LANG_CODE)?></div>
<div class="ppsPopupOptRow">
	<label>
		<?php echo htmlPps::checkbox('params[tpl][enb_login]', array(
			'checked' => htmlPps::checkedOpt($this->popup['params']['tpl'], 'enb_login'),
			'attrs' => 'data-switch-block="loginShell"',
		))?>
		<?php  _e('Enable Login', PPS_LANG_CODE)?>
	</label>
</div>
<span data-block-to-switch="loginShell">
	<table class="form-table ppsRegLoginShellMainTbl" style="width: auto;">
		<tr>
			<th scope="row">
				<?php _e('Login by', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Choose what info needs to be entered for login. Password will be included by default.', PPS_LANG_CODE))?>"></i>
			</th>
			<td>
				<?php
					$loginUsernameChecked = htmlPps::checkedOpt($this->popup['params']['tpl'], 'login_by', 'username');
					$loginEmailChecked = htmlPps::checkedOpt($this->popup['params']['tpl'], 'login_by', 'email');
					if(!$loginUsernameChecked && !$loginEmailChecked) {
						$loginUsernameChecked = true;	// Username by default
					}
				?>
				<label>
					<?php echo htmlPps::radiobutton('params[tpl][login_by]', array(
						'value' => 'username', 
						'checked' => $loginUsernameChecked))?>
					<?php _e('Username', PPS_LANG_CODE)?>
				</label>
				<label>
					<?php echo htmlPps::radiobutton('params[tpl][login_by]', array(
						'value' => 'email', 
						'checked' => $loginEmailChecked))?>
					<?php _e('E-Mail', PPS_LANG_CODE)?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Redirect after login URL', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('You can enable redirection after login, just enter here URL that you want to redirect to after login - and user will be redirected there. If you don\'t need this feature - just leave this field empty: browser window will be just reloaded after successful login.', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][login_redirect_url]', array(
					'value' => (isset($this->popup['params']['tpl']['login_redirect_url']) ? esc_url( $this->popup['params']['tpl']['login_redirect_url'] ) : ''),
					'attrs' => 'placeholder="http://example.com"',
				))?>
				<label>
					<?php echo htmlPps::checkbox('params[tpl][login_redirect_new_wnd]', array(
						'checked' => htmlPps::checkedOpt($this->popup['params']['tpl'], 'login_redirect_new_wnd')))?>
					<?php _e('Open in a new window (tab)', PPS_LANG_CODE)?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Login button name', PPS_LANG_CODE)?>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][login_btn_label]', array(
					'value' => (isset($this->popup['params']['tpl']['login_btn_label']) ? $this->popup['params']['tpl']['login_btn_label'] : __('Login', PPS_LANG_CODE))))?>
			</td>
		</tr>
	</table>
</span>
<div class="ppsPopupOptRow">
	<label>
		<?php echo htmlPps::checkbox('params[tpl][enb_reg]', array(
			'checked' => htmlPps::checkedOpt($this->popup['params']['tpl'], 'enb_reg'),
			'attrs' => 'data-switch-block="regShell"',
		))?>
		<?php  _e('Enable Registration', PPS_LANG_CODE)?>
	</label>
</div>
<span data-block-to-switch="regShell">
	<table class="form-table ppsRegLoginShellMainTbl" style="width: auto;">
		<tr>
			<th scope="row">
				<?php _e('Create user with the chosen role after registration', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('Use this only if you really need it. Remember! After you change this option - your new users will have more privileges than usual subscribers, so be careful with this option!', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::selectbox('params[tpl][reg_wp_create_user_role]', array(
					'options' => $this->availableUserRoles,
					'value' => (isset($this->popup['params']['tpl']['reg_wp_create_user_role']) ? $this->popup['params']['tpl']['reg_wp_create_user_role'] : 'subscriber')))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Create User without confirmation', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('Usually, after user registration, we send an email with the confirmation link - to confirm the email address, and only after user clicks on the link from this email - we will create a new user. This option allows you to create user - right after registration, without the email confirmation process.', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::checkbox('params[tpl][reg_ignore_confirm]', array(
					'checked' => htmlPps::checkedOpt($this->popup['params']['tpl'], 'reg_ignore_confirm')))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Export Users', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('Export all users, who registered using your PopUp, as CSV file.', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<a href="<?php echo $this->regCsvExportUrl;?>" class="button"><?php _e('Get CSV List', PPS_LANG_CODE)?></a>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Registration button name', PPS_LANG_CODE)?>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][reg_btn_label]', array(
					'value' => (isset($this->popup['params']['tpl']['reg_btn_label']) ? $this->popup['params']['tpl']['reg_btn_label'] : __('Register', PPS_LANG_CODE))))?>
			</td>
		</tr>
	</table>
	<div class="ppsPopupOptRow">
		<fieldset id="ppoPopupRegFields" class="ppoPopupRegFields" style="padding: 10px;">
			<legend>
				<?php _e('Registration fields', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('To change field position - just drag-&-drop it to required place between other fields. To add new field to Registration form - click on "+ Add" button.', PPS_LANG_CODE))?>"></i>
			</legend>
			<?php foreach($this->popup['params']['tpl']['reg_fields'] as $k => $f) { ?>
				<?php
					$labelClass = 'ppsRegFieldShell';
					if($k == 'email')
						$labelClass .= ' supsystic-tooltip-bottom ppsRegFieldEmailShell';
				?>
				<div
					class="<?php echo $labelClass?>"
					data-name="<?php echo $k?>"
					<?php if($k == 'email') { ?>
						title="Email field is mandatory for registration - so it should be always enabled"
					<?php }?>
				>
					<span class="ppsSortHolder"></span>
					<?php 
						if($k == 'email') {
							$checkParams = array('checked' => 1, 'disabled' => 1);
						} else {
							$checkParams = array('checked' => htmlPps::checkedOpt($f, 'enb'));
						}
					?>
					<?php echo htmlPps::checkbox('params[tpl][reg_fields]['. $k. '][enb]', $checkParams)?>
					
					<span class="ppsRegFieldLabel"><?php echo $f['label']?></span>
					
					<?php echo htmlPps::hidden('params[tpl][reg_fields]['. $k. '][name]', array('value' => isset($f['name']) ? $f['name'] : $k))?>
					<?php echo htmlPps::hidden('params[tpl][reg_fields]['. $k. '][html]', array('value' => $f['html']))?>
					<?php echo htmlPps::hidden('params[tpl][reg_fields]['. $k. '][label]', array('value' => $f['label']))?>
					<?php echo htmlPps::hidden('params[tpl][reg_fields]['. $k. '][value]', array('value' => isset($f['value']) ? $f['value'] : ''))?>
					<?php echo htmlPps::hidden('params[tpl][reg_fields]['. $k. '][custom]', array('value' => isset($f['custom']) ? $f['custom'] : 0))?>
					<?php echo htmlPps::hidden('params[tpl][reg_fields]['. $k. '][mandatory]', array('value' => isset($f['mandatory']) ? $f['mandatory'] : 0))?>
					<?php if(isset($f['options']) && !empty($f['options'])) {
						foreach($f['options'] as $i => $opt) {
							echo htmlPps::hidden('params[tpl][reg_fields]['. $k. '][options]['. $i. '][name]', array('value' => $opt['name']));
							echo htmlPps::hidden('params[tpl][reg_fields]['. $k. '][options]['. $i. '][label]', array('value' => $opt['label']));
						}
					}?>
					<?php 
						if($k == 'email') {	// Email is always checked
							echo htmlPps::hidden('params[tpl][reg_fields]['. $k. '][enb]', array('value' => 1));
						}
					?>
				</div>
			<?php }?>
			<label id="ppsRegAddFieldShell">
				<a id="ppsRegAddFieldBtn" href="#" class="button button-primary">
					<i class="fa fa-plus"></i>
					<?php _e('Add', PPS_LANG_CODE)?>
				</a>
			</label>
		</fieldset>
	</div>
	<table class="form-table ppsRegShellOptsTbl">
		<tr>
			<th scope="row">
				<?php _e('"Confirmation sent" message', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('This is the message that the user will see after registration, when letter with confirmation link was sent.', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][reg_txt_confirm_sent]', array(
					'value' => (isset($this->popup['params']['tpl']['reg_txt_confirm_sent']) ? esc_html( $this->popup['params']['tpl']['reg_txt_confirm_sent'] ) : __('Confirmation link was sent to your email address. Check your email!', PPS_LANG_CODE)),
				))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Registration success message', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('Right after new user will be created and confirmed - this message will be shown.', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][reg_txt_success]', array(
					'value' => (isset($this->popup['params']['tpl']['reg_txt_success']) ? esc_html( $this->popup['params']['tpl']['reg_txt_success'] ) : __('Thank you for registration!', PPS_LANG_CODE)),
				))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Email error message', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('If email that was entered by user is invalid, user will see this message', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][reg_txt_invalid_email]', array(
					'value' => (isset($this->popup['params']['tpl']['reg_txt_invalid_email']) ? esc_html( $this->popup['params']['tpl']['reg_txt_invalid_email'] ) : __('Empty or invalid email', PPS_LANG_CODE)),
				))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Redirect after registration URL', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('You can enable redirection after registration, just enter here URL that you want to redirect to after registration - and user will be redirected there. If you don\'t need this feature - just leave this field empty.', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][reg_redirect_url]', array(
					'value' => (isset($this->popup['params']['tpl']['reg_redirect_url']) ? esc_url( $this->popup['params']['tpl']['reg_redirect_url'] ) : ''),
					'attrs' => 'placeholder="http://example.com"',
				))?>
				<label>
					<?php echo htmlPps::checkbox('params[tpl][reg_redirect_new_wnd]', array(
						'checked' => htmlPps::checkedOpt($this->popup['params']['tpl'], 'reg_redirect_new_wnd')))?>
					<?php _e('Open in a new window (tab)', PPS_LANG_CODE)?>
				</label>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Confirmation email subject', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('Email with confirmation link subject', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][reg_txt_confirm_mail_subject]', array(
					'value' => esc_html ( isset($this->popup['params']['tpl']['reg_txt_confirm_mail_subject']) 
						? $this->popup['params']['tpl']['reg_txt_confirm_mail_subject'] 
						: __('Confirm registration on [sitename]', PPS_LANG_CODE)),
				))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Confirmation email From field', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('Email with confirmation link From field', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][reg_txt_confirm_mail_from]', array(
					'value' => esc_html ( isset($this->popup['params']['tpl']['reg_txt_confirm_mail_from']) 
						? $this->popup['params']['tpl']['reg_txt_confirm_mail_from'] 
						: $this->adminEmail),
				))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('Confirmation email text', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('Email with confirmation link content', PPS_LANG_CODE)?>"></i>
				<?php $allowVarsInMail = array('sitename', 'siteurl', 'confirm_link');?>
				<div class="description"><?php printf(__('You can use next variables here: %s', PPS_LANG_CODE), '['. implode('], [', $allowVarsInMail).']')?></div>
			</th>
			<td>
				<?php echo htmlPps::textarea('params[tpl][reg_txt_confirm_mail_message]', array(
					'value' => esc_html( isset($this->popup['params']['tpl']['reg_txt_confirm_mail_message']) 
						? $this->popup['params']['tpl']['reg_txt_confirm_mail_message'] 
						: __('You registered on site <a href="[siteurl]">[sitename]</a>. Follow <a href="[confirm_link]">this link</a> to complete your registration. If you did not register here - just ignore this message.', PPS_LANG_CODE)),
				))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('New Member email subject', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('Email to New Member subject', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][reg_txt_subscriber_mail_subject]', array(
					'value' => esc_html ( isset($this->popup['params']['tpl']['reg_txt_subscriber_mail_subject']) 
						? $this->popup['params']['tpl']['reg_txt_subscriber_mail_subject'] 
						: __('[sitename] Your username and password', PPS_LANG_CODE)),
				))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('New Member email From field', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('New Member email From field', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][reg_txt_subscriber_mail_from]', array(
					'value' => esc_html ( isset($this->popup['params']['tpl']['reg_txt_subscriber_mail_from']) 
						? $this->popup['params']['tpl']['reg_txt_subscriber_mail_from'] 
						: $this->adminEmail),
				))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('New Member email text', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('Email to New Member content', PPS_LANG_CODE)?>"></i>
				<?php $allowVarsInMail = array('user_login', 'user_email', 'password', 'login_url', 'sitename', 'siteurl');?>
				<div class="description" style=""><?php printf(__('You can use next variables here: %s', PPS_LANG_CODE), '['. implode('], [', $allowVarsInMail).']')?></div>
			</th>
			<td>
				<?php echo htmlPps::textarea('params[tpl][reg_txt_subscriber_mail_message]', array(
					'value' => esc_html( isset($this->popup['params']['tpl']['reg_txt_subscriber_mail_message']) 
						? $this->popup['params']['tpl']['reg_txt_subscriber_mail_message'] 
						: __('Username: [user_login]<br />Password: [password]<br />[login_url]', PPS_LANG_CODE)),
				))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('New Member Notification', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('Enter the email addresses that should receive notifications (separate by comma). Leave it blank - and you will not get any notifications.', PPS_LANG_CODE)?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('params[tpl][reg_new_email]', array(
					'value' => isset($this->popup['params']['tpl']['reg_new_email']) 
						? $this->popup['params']['tpl']['reg_new_email'] 
						: $this->adminEmail,
				))?>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<?php _e('New Member Notification email text', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php _e('Message that you will receive about new members on your site.', PPS_LANG_CODE)?>"></i>
				<?php $allowVarsInMail = array('sitename', 'siteurl', 'subscriber_data');?>
				<div class="description" style=""><?php printf(__('You can use next variables here: %s', PPS_LANG_CODE), '['. implode('], [', $allowVarsInMail).']')?></div>
			</th>
			<td>
				
				<?php echo htmlPps::textarea('params[tpl][reg_new_message]', array(
					'value' => isset($this->popup['params']['tpl']['reg_new_message']) 
						? $this->popup['params']['tpl']['reg_new_message'] 
						: __('You have new member on your site <a href="[siteurl]">[sitename]</a>, here us member information:<br />[subscriber_data]', PPS_LANG_CODE),
				))?>
			</td>
		</tr>
	</table>
</span>
<!--Add/edit registration fields popup-->
<div id="ppsRfEditFieldsWnd" title="<?php _e('Registration Field Settings', PPS_LANG_CODE)?>" style="display: none;">
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
		<tr class="ppsRfLabelShell">
			<th scope="row">
				<?php _e('Label', PPS_LANG_CODE)?>
				<i class="fa fa-question supsystic-tooltip" title="<?php echo esc_html(__('Label that will be visible for your members.', PPS_LANG_CODE))?>"></i>
			</th>
			<td>
				<?php echo htmlPps::text('label')?>
			</td>
		</tr>
		<tr class="ppsRfValueShell">
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
		<tr class="ppsRfFieldSelectOptsRow" style="display: none;">
			<th colspan="2">
				<?php _e('Select Options', PPS_LANG_CODE)?>
				<a class="button button-small ppsRfFieldSelectOptAddBtn">
					<i class="fa fa-plus"></i>
				</a>
			</th>
		</tr>
		<tr class="ppsRfFieldSelectOptsRow" style="display: none; height: auto;">
			<td colspan="2" style="padding: 0;">
				<div id="ppsRfFieldSelectOptsShell">
					<div id="ppsRfFieldSelectOptShellExl" class="ppsRfFieldSelectOptShell">
						<?php echo htmlPps::text('options[][name]', array(
							'placeholder' => __('Name', PPS_LANG_CODE),
							'disabled' => true,
						))?>
						<?php echo htmlPps::text('options[][label]', array(
							'placeholder' => __('Label', PPS_LANG_CODE),
							'disabled' => true,
						))?>
						<a href="#" class="button button-small ppsRfFieldSelectOptRemoveBtn" title="<?php _e('Remove', PPS_LANG_CODE)?>">
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
<div id="ppsRfFieldToolbarExl" class="ppsRfFieldToolbar">
	<a class="ppsRfFieldSettingsBtn" href="#" title="<?php _e('Settings', PPS_LANG_CODE)?>">
		<i class="fa fa-gear"></i>
	</a>
	<a class="ppsRfFieldRemoveBtn" href="#" title="<?php _e('Remove', PPS_LANG_CODE)?>">
		<i class="fa fa-trash-o"></i>
	</a>
</div>