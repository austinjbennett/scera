<?php
class infusionsoftPps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addFilter('subDestList', array($this, 'addSubDestList'));
		add_action('init', array($this, 'trySetAuthCode'));
		
	}
	public function isSupported() {
		return function_exists('curl_setopt') && version_compare(phpversion(), '5.5', '>=');
	}
	public function addSubDestList($subDestList) {
		$subDestList['infusionsoft'] = array('label' => __('InfusionSoft', PPS_LANG_CODE), 'require_confirm' => false);
		return $subDestList;
	}
	public function trySetAuthCode() {
		if($this->isSupported()) {
			$this->getModel()->trySetAuthCode();
		}
	}
}

