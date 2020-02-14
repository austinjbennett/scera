<?php
class ab_testingControllerPps extends controllerPps {
	public function create() {
		$res = new responsePps();
		if($this->getModel()->create(reqPps::get('post'))) {
			$res->addMessage(__('Done', PPS_LANG_CODE));
		} else
			$res->pushError ($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function getListForTbl() {
		$this->getModule()->setListForBaseId( (int)reqPps::getVar('base_id') );
		framePps::_()->getModule('popup')->getController()->getListForTbl();
	}
	public function clear() {
		$res = new responsePps();
		if($this->getModel()->clearForBase(reqPps::getVar('base_id', 'post'))) {
			$res->addMessage(__('Done', PPS_LANG_CODE));
		} else
			$res->pushError($this->getModel()->getErrors());
		$res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			PPS_USERLEVELS => array(
				PPS_ADMIN => array('create', 'getListForTbl', 'clear')
			),
		);
	}
}

