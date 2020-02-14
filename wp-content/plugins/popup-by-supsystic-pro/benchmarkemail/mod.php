<?php
class benchmarkemailPps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addFilter('subDestList', array($this, 'addSubDestList'));
		dispatcherPps::addAction('afterPopUpUpdate', array($this, 'saveUsernamePassword'));
	}
	public function isSupported() {
		return function_exists('curl_setopt');
	}
	public function addSubDestList($subDestList) {
		$subDestList['benchmarkemail'] = array('label' => __('Benchmark', PPS_LANG_CODE), 'require_confirm' => false);
		return $subDestList;
	}
	public function saveUsernamePassword($d = array()) {
		if(isset($d['sub_be_login'])) {
			$this->getModel()->setApiUsername( $d['sub_be_login'] );
		}
		if(isset($d['sub_be_pass'])) {
			$this->getModel()->setApiPassword( $d['sub_be_pass'] );
		}
	}
}

