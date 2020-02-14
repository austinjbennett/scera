<?php
class sub_fieldsPps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addAction('beforePopupEditRender', array($this, 'addAdminAssets'));
		dispatcherPps::addAction('afterPopupEdit', array($this, 'addAdminControls'));
		
	}
	public function addAdminAssets($popup) {
		framePps::_()->addScript(PPS_CODE. '.admin.sub_fields', $this->getModPath(). 'js/admin.sub_fields.js');
	}
	public function addAdminControls($popup) {
		$this->getView()->displayAdminControls();
	}
}