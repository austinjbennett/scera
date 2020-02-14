<?php
class mailrelayPps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addFilter('subDestList', array($this, 'addSubDestList'));
		dispatcherPps::addAction('afterPopUpUpdate', array($this, 'saveHostKey'));
	}
	public function addSubDestList($subDestList) {
		$subDestList['mailrelay'] = array('label' => __('Mailrelay', PPS_LANG_CODE), 'require_confirm' => false);
		return $subDestList;
	}
	public function isLoggedIn() {
		return $this->getModel()->isLoggedIn();
	}
	public function isSupported() {
		return true;
	}
	public function saveHostKey($d = array()) {
		if(isset($d['sub_mr_api_host'])) {
			$this->getModel()->setApiHost( $d['sub_mr_api_host'] );
		}
		if(isset($d['sub_mr_api_key'])) {
			$this->getModel()->setApiKey( $d['sub_mr_api_key'] );
		}
	}
}

