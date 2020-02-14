<?php
class constantcontactPps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addFilter('subDestList', array($this, 'addSubDestList'));
	}
	public function isSupported() {
		return version_compare(phpversion(), '5.3', '>=');
	}
	public function addSubDestList($subDestList) {
		$subDestList['constantcontact'] = array('label' => __('Constant Contact', PPS_LANG_CODE), 'require_confirm' => false);
		return $subDestList;
	}
	public function isLoggedIn() {
		return $this->getModel()->isLoggedIn();
	}
}

