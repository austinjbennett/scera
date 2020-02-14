<?php
class sub_fieldsViewPps extends viewPps {
	public function displayAdminControls() {
		$this->assign('availableHtmlTypes', array(
			'text' => __('Text', PPS_LANG_CODE),
			'email' => __('Email', PPS_LANG_CODE),
			'textarea' => __('Text area', PPS_LANG_CODE),
			'selectbox' => __('Selectbox', PPS_LANG_CODE),
			'checkbox' => __('Checkbox', PPS_LANG_CODE),
			'hidden' => __('Hidden Field', PPS_LANG_CODE),
			'mailchimp_groups_list' => __('MailChimp Groups List', PPS_LANG_CODE),
		));
		parent::display('sfAdminControls');
	}
}
