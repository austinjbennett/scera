<?php
class myemmaPps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addFilter('subDestList', array($this, 'addSubDestList'));
	}
	public function addSubDestList($subDestList) {
		$subDestList['myemma'] = array('label' => __('Emma', PPS_LANG_CODE), 'require_confirm' => false);
		return $subDestList;
	}
	public function isSupported() {
		return function_exists('curl_setopt');
	}
}

