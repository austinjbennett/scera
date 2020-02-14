<?php
class licenseModelPps extends modelPps {
	private $_apiUrl = '';
	public function __construct() {
		$this->_initApiUrl();
	}
	public function check() {
		$time = time();
		$lastCheck = (int) get_option('_last_important_check_'. PPS_CODE);
		if(!$lastCheck || ($time - $lastCheck) >= 5 * 24 * 3600 /** 0/*remove last!!!*/) {
			$resData = $this->_req('check', array_merge(array(
				'url' => PPS_SITE_URL,
				'plugin_code' => $this->_getPluginCode(),
			), $this->getCredentials()));
			if($resData) {
				$this->_updateLicenseData( $resData['data']['save_data'] );
			} else {
				$this->_setExpired();
			}
			update_option('_last_important_check_'. PPS_CODE, $time);
		} else {
			$daysLeft = (int) framePps::_()->getModule('options')->getModel()->get('license_days_left');
			if($daysLeft) {
				$lastServerCheck = (int) framePps::_()->getModule('options')->getModel()->get('license_last_check');
				$day = 24 * 3600;
				$daysPassed = floor(($time - $lastServerCheck) / $day);
				if($daysPassed > 0) {
					$daysLeft -= $daysPassed;
					framePps::_()->getModule('options')->getModel()->save('license_days_left', $daysLeft);
					framePps::_()->getModule('options')->getModel()->save('license_last_check', time());
					if($daysLeft < 0) {
						$this->_setExpired();
					}
				}
			}
		}
		return true;
	}
	public function activate($d = array()) {
		$d['email'] = isset($d['email']) ? trim($d['email']) : '';
		$d['key'] = isset($d['key']) ? trim($d['key']) : '';
		if(!empty($d['email'])) {
			if(!empty($d['key'])) {
				$this->setCredentials($d['email'], $d['key']);
				if(($resData = $this->_req('activate', array_merge(array(
					'url' => PPS_SITE_URL,
					'plugin_code' => $this->_getPluginCode(),
				), $this->getCredentials()))) != false) {
					$this->_updateLicenseData( $resData['data']['save_data'] );
					$this->_setActive();
					return true;
				}
			} else
				$this->pushError(__('Please enter your License Key', PPS_LANG_CODE), 'key');
		} else
			$this->pushError(__('Please enter your Email address', PPS_LANG_CODE), 'email');
		$this->_removeActive();
		return false;
	}
	private function _updateLicenseData($saveData) {
		framePps::_()->getModule('options')->getModel()->save('license_save_name', $saveData['license_save_name']);
		framePps::_()->getModule('options')->getModel()->save('license_save_val', $saveData['license_save_val']);
		framePps::_()->getModule('options')->getModel()->save('license_days_left', $saveData['days_left']);
		framePps::_()->getModule('options')->getModel()->save('license_last_check', time());
		dbPps::query('UPDATE @__modules SET active = 1 WHERE ex_plug_dir IS NOT NULL AND ex_plug_dir != "" AND code != "license"');
	}
	private function _setExpired() {
		update_option('_last_expire_'. PPS_CODE, 1);
		$this->_removeActive();
		if($this->enbOptimization()) {
			dbPps::query('UPDATE @__modules SET active = 0 WHERE ex_plug_dir IS NOT NULL AND ex_plug_dir != "" AND code != "license"');
			framePps::_()->getModule('options')->getModel()->save('license_days_left', -1);
		}
	}
	public function isExpired() {
		return (int) get_option('_last_expire_'. PPS_CODE);
	}
	public function isActive() {
		$option = get_option(framePps::_()->getModule('options')->get('license_save_name'));
		return ($option && $option == framePps::_()->getModule('options')->get('license_save_val'));
	}
	public function _setActive() {
		update_option('_site_transient_update_plugins', ''); // Trigger plugins updates check
		update_option(framePps::_()->getModule('options')->get('license_save_name'), framePps::_()->getModule('options')->get('license_save_val'));
		delete_option('_last_expire_'. PPS_CODE);
	}
	public function _removeActive() {
		$name = framePps::_()->getModule('options')->get('license_save_name');
		if(!empty($name)) {
			delete_option($name);
		}
	}
	public function setCredentials($email, $key) {
		$this->setEmail($email);
		$this->setLicenseKey($key);
	}
	public function setEmail($email) {
		framePps::_()->getModule('options')->getModel()->save('license_email', base64_encode( $email ));
	}
	public function setLicenseKey($key) {
		framePps::_()->getModule('options')->getModel()->save('license_key', base64_encode( $key ));
	}
	public function getEmail() {
		return base64_decode( framePps::_()->getModule('options')->get('license_email') );
	}
	public function getLicenseKey() {
		return base64_decode( framePps::_()->getModule('options')->get('license_key') );
	}
	public function getCredentials() {
		return array(
			'email' => $this->getEmail(),
			'key' => $this->getLicenseKey(),
		);
	}
	private function _req($action, $data = array()) {
		$data = array_merge($data, array(
			'mod' => 'manager',
			'pl' => 'lms',
			'action' => $action,
		));
		$response = wp_remote_post($this->_apiUrl, array(
			'body' => $data
		));
		if(is_wp_error($response)) {
			// Try it with native CURL - maybe this will work
			$curlNativeTry = $this->_reqWithCurl($data);
			if(!$curlNativeTry) {
				$curlNativeTry = $this->_reqWithCurl($data, true);
			}
			if($curlNativeTry) {
				$response = array('body' => $curlNativeTry);
			}
		}
		if (!is_wp_error($response)) {
			if(isset($response['body']) && !empty($response['body']) && ($resArr = utilsPps::jsonDecode($response['body']))) {
				if(!$resArr['error']) {
					return $resArr;
				} else
					$this->pushError($resArr['errors']);
			} else
				$this->pushError(__('There was a problem with sending request to our authentication server. Please try later.', PPS_LANG_CODE));
		} else
			$this->pushError( $response->get_error_message() );
		return false;
	}
	private function _reqWithCurl($data, $dsblSsl = false) {
		if(!function_exists('curl_init')) return false;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->_apiUrl);
		if($dsblSsl) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($ch, CURLOPT_SSLVERSION, 3);
		} else {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_CAINFO, framePps::_()->getModule('subscribe')->getModDir(). 'classes'. DS. 'cacert.pem');
		}

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$result = curl_exec($ch);
//var_dump(curl_error($ch));
		curl_close($ch);
		return $result ? $result : false;
	}
	private function _initApiUrl() {
		if(empty($this->_apiUrl)) {
			$this->_apiUrl = 'http://supsystic.com/';
		}
	}
	public function enbOptimization() {
		return false;
	}
	public function checkPreDeactivateNotify() {
		$daysLeft = (int) framePps::_()->getModule('options')->getModel()->get('license_days_left');
		if($daysLeft > 0 && $daysLeft <= 3) {	// Notify before 3 days
			add_action('admin_notices', array($this, 'showPreDeactivationNotify'));
		}
	}
	public function showPreDeactivationNotify() {
		$daysLeft = (int) framePps::_()->getModule('options')->getModel()->get('license_days_left');
		$msg = '';
		if($daysLeft == 0) {
			$msg = sprintf(__('License for plugin %s will expire today.', PPS_LANG_CODE), PPS_WP_PLUGIN_NAME);
		} elseif($daysLeft == 1) {
			$msg = sprintf(__('License for plugin %s will expire tomorrow.', PPS_LANG_CODE), PPS_WP_PLUGIN_NAME);
		} else {
			$msg = sprintf(__('License for plugin %s will expire in %d days.', PPS_LANG_CODE), PPS_WP_PLUGIN_NAME, $daysLeft);
		}
		echo '<div class="error">'. $msg. '</div>';
	}
	public function updateDb() {
		if(!$this->enbOptimization())
			return;
		$time = time();
		$lastCheck = (int) get_option('_last_wp_check_imp_'. PPS_CODE);
		if(!$lastCheck || ($time - $lastCheck) >= 5 * 24 * 3600 /** 0/*remove last!!!*/) {
			if($this->isActive()) {
				dbPps::query('UPDATE @__modules SET active = 1 WHERE ex_plug_dir IS NOT NULL AND ex_plug_dir != "" AND code != "license"');
			} else {
				dbPps::query('UPDATE @__modules SET active = 0 WHERE ex_plug_dir IS NOT NULL AND ex_plug_dir != "" AND code != "license"');
			}
			update_option('_last_wp_check_imp_'. PPS_CODE, $time);
		}
	}
	private function _getPluginCode() {
		return 'popup_by_supsystic_pro';
	}
	public function getExtendUrl() {
		$license = $this->getCredentials();
		$license['key'] = md5($license['key']);
		$license = urlencode(base64_encode(implode('|', $license)));
		return $this->_apiUrl. '?mod=manager&pl=lms&action=extend&plugin_code='. $this->_getPluginCode(). '&lic='. $license;
	}
}
