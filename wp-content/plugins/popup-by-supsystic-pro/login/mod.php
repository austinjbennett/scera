<?php
class loginPps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addFilter('popupEditTabs', array($this, 'addPopupEditTab'), 10, 2);	// Add admin tab
		dispatcherPps::addFilter('beforePopUpRender', array($this, 'beforePopUpRender'));
		dispatcherPps::addFilter('popupListBeforeRender', array($this, 'checkPopupListBeforeRender'));
	}
	public function addPopupEditTab($tabs, $popup) {
		if(!in_array($popup['type'], array(PPS_FB_LIKE, PPS_IFRAME, PPS_SIMPLE_HTML, PPS_PDF, PPS_AGE_VERIFY, PPS_FULL_SCREEN))) {
			framePps::_()->addScript(PPS_CODE. '.admin.login', $this->getModPath(). 'js/admin.login.js');
			$tabs['ppsLoginRegister'] = array(
				'title' => __('Login/Registration', PPS_LANG_CODE), 
				'content' => $this->getView()->getPopupEditTab($popup),
				'fa_icon' => 'fa-sign-in',
				'sort_order' => 25,
			);
		}
		return $tabs;
	}
	public function generateLoginFormStart($popup) {
		return $this->_geenerateCommonFormStart($popup, 'login');
	}
	public function generateLoginFormEnd($popup) {
		return $this->_generateCommonFormEnd($popup, 'login');
	}
	public function generateLoginFields($popup) {
		$fields = array();
		if(isset($popup['params']['tpl']['login_by']) && $popup['params']['tpl']['login_by'] == 'email') {
			$fields['email'] = array('html' => 'text', 'label' => __('E-Mail', PPS_LANG_CODE));
		} else {
			$fields['username'] = array('html' => 'text', 'label' => __('Username', PPS_LANG_CODE));
		}
		$fields['password'] = array('html' => 'password', 'label' => __('Password', PPS_LANG_CODE));
		foreach($fields as $k => $f) {
			$fields[ $k ]['mandatory'] = true;	// All fields will be required on login form
			$fields[ $k ]['enb'] = true;	// All login fields are always enable - this is logic, isn't it?:)
		}
		return $this->_generateCommonFields( $fields );
	}
	public function generateRegFormStart($popup, $bothForms = false) {
		return $this->_geenerateCommonFormStart($popup, 'registration', $bothForms);
	}
	public function generateRegFormEnd($popup, $bothForms = false) {
		return $this->_generateCommonFormEnd($popup, 'registration', $bothForms);
	}
	public function generateRegFields($popup) {
		return $this->_generateCommonFields( $popup['params']['tpl']['reg_fields'] );
	}
	private function _geenerateCommonFormStart($popup, $key) {
		$formClass = $key == 'login' ? 'ppsLoginForm' : 'ppsRegForm';
		$msgClass = $key == 'login' ? 'ppsLoginMsg' : 'ppsRegMsg';
		$res = '<form class="'. $formClass. '" action="'. PPS_SITE_URL. '" method="post">';
		if(in_array($popup['original_id'], array(31))) {	// For those templates - put message up to the form
			$res .= '<div class="'. $msgClass. '"></div>';
		}
		return $res;
	}
	private function _generateCommonFormEnd($popup, $key) {
		$action = $key == 'login' ? 'login' : 'register';
		$msgClass = $key == 'login' ? 'ppsLoginMsg' : 'ppsRegMsg';
		$res = '';
		$res .= htmlPps::hidden('mod', array('value' => 'login'));
		$res .= htmlPps::hidden('action', array('value' => $action));
		$res .= htmlPps::hidden('id', array('value' => $popup['id']));
		$res .= htmlPps::hidden('_wpnonce', array('value' => wp_create_nonce($action. '-'. $popup['id'])));
		if(!in_array($popup['original_id'], array(31))) {	// For those templates - put message up to the form
			$res .= '<div class="'. $msgClass. '"></div>';
		}
		$res .= '</form>';
		return $res;
	}
	private function _generateCommonFields($fields) {
		$resHtml = '';
		foreach($fields as $k => $f) {
			if(isset($f['enb']) && $f['enb']) {
				$htmlType = $f['html'];
				$name = $k;
				$htmlParams = array(
					'placeholder' => $f['label'],
				);
				if($htmlType == 'selectbox' && isset($f['options']) && !empty($f['options'])) {
					$htmlParams['options'] = array();
					foreach($f['options'] as $opt) {
						$htmlParams['options'][ $opt['name'] ] = $opt['label'];
					}
				}
				if(isset($f['value']) && !empty($f['value'])) {
					$htmlParams['value'] = $f['value'];
				}
				if(isset($f['mandatory']) && !empty($f['mandatory']) && (int)$f['mandatory']) {
					$htmlParams['required'] = true;
				}
				$inputHtml = htmlPps::$htmlType($name, $htmlParams);
				if($htmlType == 'selectbox') {
					$inputHtml = '<label class="ppsSubSelect"><span class="ppsSubSelectLabel">'. $f['label']. ': </span>'. $inputHtml. '</label>';
				}
				$resHtml .= $inputHtml;
			}
		}
		return $resHtml;
	}	
	public function beforePopUpRender($popup) {
		if(isset($popup['params']['tpl']['enb_login']) 
			&& !empty($popup['params']['tpl']['enb_login']) 
			&& isset($popup['params']['tpl']['login_btn_label'])
			&& !empty($popup['params']['tpl']['login_btn_label'])
		) {
			// Substitute login button name
			$popup['params']['tpl']['original_sub_label'] = $popup['params']['tpl']['sub_btn_label'];
			$popup['params']['tpl']['sub_btn_label'] = $popup['params']['tpl']['login_btn_label'];
		} elseif(isset($popup['params']['tpl']['enb_reg']) 
			&& !empty($popup['params']['tpl']['enb_reg']) 
			&& isset($popup['params']['tpl']['reg_btn_label'])
			&& !empty($popup['params']['tpl']['reg_btn_label'])
		) {
			// Substitute registration button name
			$popup['params']['tpl']['original_sub_label'] = $popup['params']['tpl']['sub_btn_label'];
			$popup['params']['tpl']['sub_btn_label'] = $popup['params']['tpl']['reg_btn_label'];
		}
		return $popup;
	}
	public function checkPopupListBeforeRender($popups) {
		if(!empty($popups)) {
			$needLoadAssets = false;
			foreach($popups as $p) {
				if(isset($p['params'])) {
					if(isset($p['params']['tpl']['enb_login']) 
						&& !empty($p['params']['tpl']['enb_login']) 
					) {
						$needLoadAssets = true;
						break;
					}
					if(isset($p['params']['tpl']['enb_reg']) 
						&& !empty($p['params']['tpl']['enb_reg']) 
					) {
						$needLoadAssets = true;
						break;
					}
				}
			}
			if($needLoadAssets) {
				$this->loadAssets();
			}
		}
		return $popups;
	}
	public function loadAssets() {
		framePps::_()->addScript('frontend.login', $this->getModPath(). 'js/frontend.login.js', array('jquery'));
	}
	public function install() {
		/**
		 * Registrations table
		 */
		if (!dbPps::exist("@__registrations")) {
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta(dbPps::prepareQuery("CREATE TABLE IF NOT EXISTS `@__registrations` (
			   `id` int(11) NOT NULL AUTO_INCREMENT,
			   `username` VARCHAR(128) NULL DEFAULT NULL,
			   `email` VARCHAR(128) NOT NULL,
			   `hash` VARCHAR(128) NOT NULL,
			   `activated` TINYINT(1) NOT NULL DEFAULT '0',
			   `popup_id` int(11) NOT NULL DEFAULT '0',
			   `date_created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
			   `all_data` TEXT NOT NULL,
			   PRIMARY KEY (`id`)
			 ) DEFAULT CHARSET=utf8;"));
		}
	}
	public function activate() {
		$this->install();
	}
}
