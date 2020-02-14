<?php
class constantcontactViewPps extends viewPps {
	public function generateAdminFields($popup) {
		$isSupported = $this->getModule()->isSupported();
		if($isSupported) {
			$isLoggedIn = $this->getModule()->isLoggedIn();
			$this->assign('popup', $popup);
			$this->assign('isLoggedIn', $isLoggedIn);
			if($isLoggedIn) {
				framePps::_()->addScript('admin.'. $this->getCode(), $this->getModule()->getModPath(). 'js/admin.'. $this->getCode(). '.js');
			} else {
				$this->assign('authObj', $this->getModel()->getAuthObj( $popup['id'] ));
			}
		}
		$this->assign('isSupported', $isSupported);
		return parent::getContent('ccAdminFields');
	}
}
