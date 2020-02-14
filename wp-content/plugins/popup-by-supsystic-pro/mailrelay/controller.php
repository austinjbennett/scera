<?php
class mailrelayControllerPps extends controllerPps {
	public function getLists() {
		$res = new responsePps();
		$this->getModule()->saveHostKey(reqPps::get('post'));
		if(($lists = $this->getModel()->getLists()) !== false) {
			$res->addData('lists', $lists);
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			PPS_USERLEVELS => array(
				PPS_ADMIN => array('getLists')
			),
		);
	}
}