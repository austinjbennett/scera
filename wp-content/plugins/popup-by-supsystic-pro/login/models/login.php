<?php
class loginModelPps extends modelPps {
	private $_lastPopup = null;	// Some small internal caching
	public function __construct() {
		$this->_setTbl('registrations');
	}
	public function login($d = array()) {
		$id = isset($d['id']) ? $d['id'] : 0;
		if($id) {
			$popup = framePps::_()->getModule('popup')->getModel()->getById($id);
			if($popup && isset($popup['params']) 
				&& isset($popup['params']['tpl']['enb_login']) 
			) {
				if(($d = $this->_validateLogin($d, $popup))) {
					$creds = array('user_password' => $d['password']);
					if(isset($popup['params']['tpl']['login_by']) 
						&& $popup['params']['tpl']['login_by'] == 'email'
					) {
						$user = get_user_by('email', $d['email']);
						if(!$user) {
							$this->pushError ($this->_getInvalidEmailMsg(), 'email');
							return false;
						}
						$creds['user_login'] = $user->data->user_login;
					} else {
						$creds['user_login'] = $d['username'];
					}
					$user = wp_signon( $creds, false );
					if(!is_wp_error($user)) {
						$this->_lastPopup = $popup;
						return true;
					} else {
						$this->pushError( $user->get_error_message() );
					}
				}
			} else
				$this->pushError (__('Empty or invalid ID', PPS_LANG_CODE));
		} else
			$this->pushError (__('Empty or invalid ID', PPS_LANG_CODE));
		return false;
	}
	public function register($d = array()) {
		$id = isset($d['id']) ? $d['id'] : 0;
		if($id) {
			$popup = framePps::_()->getModule('popup')->getModel()->getById($id);
			if($popup && isset($popup['params']) 
				&& isset($popup['params']['tpl']['enb_reg']) 
			) {
				$d = dbPps::prepareHtmlIn($d);
				$subscribeModel = framePps::_()->getModule('subscribe')->getModel();
				if($subscribeModel->validateFields($d, $popup, true)) {
					$res = $subscribeModel->subscribe_wordpress($d, $popup, true, true);
					if($res) {
						$subscribeModel->checkSendNewNotification($d, $popup, true);
						$this->_lastPopup = $popup;
						return true;
					} else {
						$this->pushError( $subscribeModel->getErrors() );
					}
				} else {
					$this->pushError( $subscribeModel->getErrors() );
				}
			} else
				$this->pushError (__('Empty or invalid ID', PPS_LANG_CODE));
		} else
			$this->pushError (__('Empty or invalid ID', PPS_LANG_CODE));
		return false;
	}
	private function _getInvalidEmailMsg() {
		return framePps::_()->getModule('subscribe')->getModel()->getInvalidEmailMsg();
	}
	private function _validateLogin($d, $popup) {
		if(isset($popup['params']['tpl']['login_by']) 
			&& $popup['params']['tpl']['login_by'] == 'email'
		) {
			$d['email'] = isset($d['email']) ? trim($d['email']) : false;
			if(empty($d['email'])) {
				$this->pushError ($this->_getInvalidEmailMsg(), 'email');
			} elseif(!is_email($d['email'])) {
				$this->pushError ($this->_getInvalidEmailMsg(), 'email');
			}
		} else {
			$d['username'] = isset($d['username']) ? trim($d['username']) : false;
			if(empty($d['username'])) {
				$this->pushError (__('Empty or invalid username', PPS_LANG_CODE), 'username');
			}
		}
		$d['password'] = isset($d['password']) ? trim($d['password']) : false;
		if(empty($d['password'])) {
			$this->pushError (__('Empty or invalid password', PPS_LANG_CODE), 'username');
		}
		return $this->haveErrors() ? false : $d;
	}
	public function _validateRegistration($d, $popup) {
		
	}
	public function getLastPopup() {
		return $this->_lastPopup;
	}
}
