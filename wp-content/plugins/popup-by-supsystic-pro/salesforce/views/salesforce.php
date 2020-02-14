<?php
class salesforceViewPps extends viewPps {
	public function generateAdminFields($popup) {
		$isSupported = $this->getModule()->isSupported();
		if($isSupported) {
			$this->assign('popup', $popup);
			framePps::_()->addScript('admin.'. $this->getCode(), $this->getModule()->getModPath(). 'js/admin.'. $this->getCode(). '.js');
		}
		$this->assign('helpImgUrl', $this->getModule()->getModPath(). 'img/help.jpg');
		$this->assign('isSupported', $isSupported);
		return parent::getContent('sfAdminFields');
	}
}