<?php
class accessPps extends modulePps {
	private $_accessRoles = array();
	public function init() {
		parent::init();
		dispatcherPps::addFilter('adminMenuAccessCap', array($this, 'modifyAccessCap'));
		dispatcherPps::addFilter('popupListBeforeRender', array($this, 'checkPopupListBeforeRender'));
	}
	public function modifyAccessCap($mainCap) {
		if($this->onlyForAdmin()) {
			return $mainCap;
		}
		$accessRoles = $this->getAccessRolesList();
		$inCaps = array();
		foreach($accessRoles as $role) {
			$allRoleData = get_role( $role );
			if($allRoleData && $allRoleData->capabilities) {
				$roleInCaps = array();
				foreach($allRoleData->capabilities as $cKey => $cVal) {
					if($cVal) {
						$roleInCaps[] = $cKey;
					}
				}
				if(empty($inCaps))
					$inCaps = $roleInCaps;
				else
					$inCaps = array_intersect ($inCaps, $roleInCaps);
			}
		}
		if(!empty($inCaps))
			return array_shift($inCaps);
		return false;
	}
	public function onlyForAdmin() {
		$accessRoles = $this->getAccessRolesList();
		if(empty($accessRoles) || count($accessRoles) == 1 && in_array('administrator', $accessRoles))
			return true;
		return false;
	}
	public function getAccessRolesList() {
		if(empty($this->_accessRoles)) {
			$this->_accessRoles = framePps::_()->getModule('options')->get('access_roles');
			if(empty($this->_accessRoles) || !is_array($this->_accessRoles))
				$this->_accessRoles = array();
			if(!in_array('administrator', $this->_accessRoles))	// Admin should always have access to plugin
				$this->_accessRoles[] = 'administrator';
		}
		return $this->_accessRoles;
	}
	public function checkPopupListBeforeRender($popups) {
		if(!empty($popups)) {
			$currentUserRoles = NULL;
			$dataRemoved = false;
			foreach($popups as $i => $p) {
				if(isset($p['params']['main']['hide_for_user_roles'])
					&& !empty($p['params']['main']['hide_for_user_roles'])
				) { // Check if popup need to be hidden for some user roles
					if(is_null($currentUserRoles)) {
						$currentUserRoles = utilsPps::getCurrentUserRoleList();
					}
					$roleFound = $currentUserRoles && array_intersect($currentUserRoles, $p['params']['main']['hide_for_user_roles']);
					$hideShowRevert = isset($p['params']['main']['hide_for_user_roles_show']) && (int) $p['params']['main']['hide_for_user_roles_show'];
					if(((!$hideShowRevert && $roleFound) 
						|| ($hideShowRevert && !$roleFound)
					)) {
						unset($popups[ $i ]);
						$dataRemoved = true;
					}
				}
			}
			if($dataRemoved) {
				$popups = array_values( $popups );
			}
		}
		return $popups;
	}
}

