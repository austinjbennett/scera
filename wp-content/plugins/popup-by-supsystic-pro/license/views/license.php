<?php
class licenseViewPps extends viewPps {
	public function getTabContent() {
		framePps::_()->addScript('admin.license', $this->getModule()->getModPath(). 'js/admin.license.js');
		framePps::_()->getModule('templates')->loadJqueryUi();
		$this->assign('credentials', $this->getModel()->getCredentials());
		$this->assign('isActive', $this->getModel()->isActive());
		$this->assign('isExpired', $this->getModel()->isExpired());
		$this->assign('extendUrl', $this->getModel()->getExtendUrl());
		return parent::getContent('licenseAdmin');
	}
}
