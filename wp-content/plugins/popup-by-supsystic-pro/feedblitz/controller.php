<?php
class feedblitzControllerPps extends controllerPps {
	public function getLists() {
		$res = new responsePps();
		if(($lists = $this->getModel()->getLists(reqPps::get('post'))) !== false) {
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