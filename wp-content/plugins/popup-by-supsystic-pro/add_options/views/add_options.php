<?php
class add_optionsViewPps extends viewPps {
	public function showAdminOption($popup) {
		$this->assign('popup', $popup);
		parent::display('addOptAdminOption');
	}
}
