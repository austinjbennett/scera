<?php
class layered_popupViewPps extends viewPps {
	public function getPopupEditTab($popup) {

		framePps::_()->addScript('admin.layered_popup', $this->getModule()->getModPath(). 'js/admin.layered_popup.js');
		
		framePps::_()->addStyle('admin.layered_popup', $this->getModule()->getModPath(). 'css/admin.layered_popup.css');
		
		$this->assign('popup', $popup);
		return parent::getContent('lpPopupEditTab');
	}
}
