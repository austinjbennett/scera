<?php
class arpreachPps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addFilter('subDestList', array($this, 'addSubDestList'));
	}
	public function addSubDestList($subDestList) {
		$subDestList['arpreach'] = array('label' => __('arpReach', PPS_LANG_CODE), 'require_confirm' => false);
		return $subDestList;
	}
	public function isSupported() {
		return true;
	}
	public function generateFormStart($popup, $subDest) {
		return '<form method="post" action="'. (isset($popup['params']['tpl']['sub_ar_form_action']) ? $popup['params']['tpl']['sub_ar_form_action'] : '').'">';
	}
	public function generateFormEnd($popup) {
		return '</form>';
	}
}

