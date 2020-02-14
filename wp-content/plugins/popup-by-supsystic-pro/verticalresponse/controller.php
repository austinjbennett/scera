<?php
class verticalresponseControllerPps extends controllerPps {
	public function saveAccessToken() {
		$errors = array();
		$data = reqPps::get('get');
		$reqUriArr = explode('/', reqPps::getRequestUri());
		$popupId = (int) $reqUriArr[ count($reqUriArr) - 1 ];	// Last part of uri: lalala/ololo/ID
		$data['id'] = $popupId;
		if(!$this->getModel()->saveAccessToken($data)) {
			$errors = $this->getModel()->getErrors();
		}
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