<?php
class loginControllerPps extends controllerPps {
	public function login() {
		$res = new responsePps();
		$data = reqPps::get('post');
		$id = isset($data['id']) ? (int) $data['id'] : 0;
		$nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : reqPps::getVar('_wpnonce');
		if(!wp_verify_nonce($nonce, 'login-'. $id)) {
			die('Some error with your request.........');
		}
		if($this->getModel()->login( $data )) {
			$lastPopup = $this->getModel()->getLastPopup();
			$redirectUrl = isset($lastPopup['params']['tpl']['login_redirect_url']) && !empty($lastPopup['params']['tpl']['login_redirect_url'])
					? $lastPopup['params']['tpl']['login_redirect_url']
					: false;
			if(!empty($redirectUrl)) {
				$res->addData('redirect', uriPps::normal($redirectUrl));
			}
			$res->addMessage(__('Login Success!', PPS_LANG_CODE));
		} else {
			$res->pushError ($this->getModel()->getErrors());
		}
		return $res->ajaxExec();
	}
	public function register() {
		$res = new responsePps();
		$data = reqPps::get('post');
		$id = isset($data['id']) ? (int) $data['id'] : 0;
		$nonce = isset($_REQUEST['_wpnonce']) ? $_REQUEST['_wpnonce'] : reqPps::getVar('_wpnonce');
		if(!wp_verify_nonce($nonce, 'register-'. $id)) {
			die('Some error with your request.........');
		}
		if($this->getModel()->register( $data )) {
			$lastPopup = $this->getModel()->getLastPopup();
			$withoutConfirm = (isset($lastPopup['params']['tpl']['reg_ignore_confirm']) && $lastPopup['params']['tpl']['reg_ignore_confirm']);
			if($withoutConfirm)
				$res->addMessage(isset($lastPopup['params']['tpl']['reg_txt_success'])
						? $lastPopup['params']['tpl']['reg_txt_success']
						: __('Thank you for registration!', PPS_LANG_CODE));
			else
				$res->addMessage(isset($lastPopup['params']['tpl']['reg_txt_confirm_sent']) 
						? $lastPopup['params']['tpl']['reg_txt_confirm_sent'] : 
						__('Confirmation link was sent to your email address. Check your email!', PPS_LANG_CODE));
			
			$redirectUrl = isset($lastPopup['params']['tpl']['reg_redirect_url']) && !empty($lastPopup['params']['tpl']['reg_redirect_url'])
					? $lastPopup['params']['tpl']['reg_redirect_url']
					: false;
			if(!empty($redirectUrl)) {
				$res->addData('redirect', uriPps::normal($redirectUrl));
			}
		} else {
			$res->pushError ($this->getModel()->getErrors());
		}
		return $res->ajaxExec();
	}
	public function getPermissions() {
		return array(
			PPS_USERLEVELS => array(
				PPS_ADMIN => array()
			),
		);
	}
}

