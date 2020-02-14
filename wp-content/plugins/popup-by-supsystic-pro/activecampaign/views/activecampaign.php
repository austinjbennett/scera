<?php
class activecampaignViewPps extends viewPps {
	public function generateAdminFields($popup) {
		$isSupported = $this->getModule()->isSupported();
		if($isSupported) {
			$this->assign('popup', $popup);
			framePps::_()->addScript('admin.'. $this->getCode(), $this->getModule()->getModPath(). 'js/admin.'. $this->getCode(). '.js');
		}
		$this->assign('isSupported', $isSupported);
		$this->assign('helpImgLink', $this->getModule()->getModPath(). 'img/help.jpg');
		return parent::getContent('acAdminFields');
	}
}