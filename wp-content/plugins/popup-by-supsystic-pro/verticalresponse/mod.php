<?php
class verticalresponsePps extends modulePps {
	private $_redirectAlias = 'verticalresponse/auth/return';
	public function init() {
		parent::init();
		dispatcherPps::addFilter('subDestList', array($this, 'addSubDestList'));
		$code = reqPps::getVar('code', 'get');
		if(!empty($code) && strpos(reqPps::getRequestUri(), $this->_redirectAlias)) {
			$this->getController()->saveAccessToken();
		}
	}
	public function isSupported() {
		return true;
	}
	public function addSubDestList($subDestList) {
		$subDestList['verticalresponse'] = array('label' => __('Vertical Response', PPS_LANG_CODE), 'require_confirm' => false);
		return $subDestList;
	}
	public function isLoggedIn() {
		return $this->getModel()->isLoggedIn();
	}
	public function getRedirectAlias() {
		return $this->_redirectAlias;
	}
}

