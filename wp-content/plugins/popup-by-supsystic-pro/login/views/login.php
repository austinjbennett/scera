<?php
class loginViewPps extends viewPps {
	public function getPopupEditTab($popup) {
		$this->assign('popup', $popup);
		$this->assign('regCsvExportUrl', uriPps::mod('subscribe', 'getWpCsvList', array('id' => $popup['id'], 'for_reg' => 1)));
		$this->assign('availableUserRoles', framePps::_()->getModule('subscribe')->getAvailableUserRolesForSelect());
		$this->assign('adminEmail', get_bloginfo('admin_email'));
		$this->assign('availableHtmlTypes', array(
			'text' => __('Text', PPS_LANG_CODE),
			'textarea' => __('Text area', PPS_LANG_CODE),
			'selectbox' => __('Select box', PPS_LANG_CODE),
			'hidden' => __('Hidden Field', PPS_LANG_CODE),
		));
		return parent::getContent('loginAdmin');
	}
}
