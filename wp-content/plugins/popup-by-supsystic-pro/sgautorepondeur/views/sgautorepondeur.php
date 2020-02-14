<?php
class sgautorepondeurViewPps extends viewPps {
	public function generateAdminFields($popup) {
		$isSupported = $this->getModule()->isSupported();
		if($isSupported) {
			$this->assign('popup', $popup);
		}
		$this->assign('isSupported', $isSupported);
		return parent::getContent('sgaAdminFields');
	}
}