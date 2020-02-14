<?php
class campaignmonitorControllerPps extends controllerPps {
	public function saveAccessToken() {
		$errors = array();
		if(!$this->getModel()->saveAccessToken(reqPps::get('get'))) {
			$errors = $this->getModel()->getErrors();
		}
		$popupId = (int) reqPps::getVar('id');
		$redirectUrl = $popupId ? framePps::_()->getModule('popup')->getEditLink( $popupId ) : framePps::_()->getModule('options')->getTabUrl('popup');
		$redirectUrlData = array('baseUrl' => $redirectUrl);
		if(!empty($errors)) {
			$redirectUrlData['ppsErrors'] = $errors;
		}
		redirectPps( uriPps::_($redirectUrlData) );
	}
	public function getLists() {
		$res = new responsePps();
		if(($lists = $this->getModel()->getLists()) !== false) {
			$res->addData('lists', $lists);
			//$res->addData('selected_lists', $this->getModel()->getSyncLists());
		} else
			$res->pushError ($this->getModel()->getErrors());
		return $res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			PPS_USERLEVELS => array(
				PPS_ADMIN => array('saveAccessToken', 'getLists')
			),
		);
	}
}