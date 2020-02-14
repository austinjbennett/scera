<?php
class benchmarkemailModelPps extends modelPps {
	private $_clients = array();
	private $_lastRequireConfirm = false;
	
	private $_CIPHER = null;//MCRYPT_RIJNDAEL_128; // Rijndael-128 is AES
    private $_MODE   = null;//MCRYPT_MODE_CBC;
	private $_username = '';
	private $_password = '';
	public function getClient($login, $pass) {
		$clientKey = md5($login. '_'. $pass);
		if(isset($this->_clients[ $clientKey ]))
			return $this->_clients[ $clientKey ];
		if(!class_exists('BMEAPI')) {
			require_once($this->getModule()->getModDir(). 'classes'. DS. 'bme-apiphp'. DS. 'inc'. DS. 'BMEAPI.class.php');
		}
		// It is hardcoded in BMEAPI class - so let's disable it as hardcode here, sorry ...........
		$_GET['debug'] = 0;
		$this->_clients[ $clientKey ] = new BMEAPI($login, $pass, 'http://www.benchmarkemail.com/api/1.0');
		if($this->_clients[ $clientKey ]->errorCode) {
			$this->pushError( $this->_clients[ $clientKey ]->errorMessage );
			return false;
		}
		return $this->_clients[ $clientKey ];
	}
	public function getLists($d) {
		$login = $this->getApiUsername();
		$password = $this->getApiPassword();
		if(!empty($login)) {
			if(!empty($password)) {
				$client = $this->getClient($login, $password);
				if($client) {
					$listsRes = $client->listGet("", 1, 100, "", "");
					if(!empty($listsRes) && count($listsRes) > 0) {
						$listsData = array();
						foreach($listsRes as $l) {
							$listsData[] = array('id' => $l['id'], 'name' => $l['listname']);
						}
						return $listsData;
					} else {
						$this->pushError(__('Please make sure that you created some Lists under your Benchmark account.', PPS_LANG_CODE));
					}
				}
			} else 
				$this->pushError(__('Please enter your Benchmark Password', PPS_LANG_CODE), 'sub_be_pass');
		} else
			$this->pushError(__('Please enter your Benchmark Login', PPS_LANG_CODE), 'sub_be_login');
		return false;
		
	}
	public function subscribe($d, $popup, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email)) {
			if(is_email($email)) {
				$lists = isset($popup['params']['tpl']['sub_be_lists']) ? $popup['params']['tpl']['sub_be_lists'] : array();
				if(!empty($lists)) {
					if(!$validateIp || $validateIp && framePps::_()->getModule('subscribe')->getModel()->checkOftenAccess(array('only_check' => true))) {
						$login = $this->getApiUsername();
						$password = $this->getApiPassword();
						
						
						$client =  $this->getClient($login, $password);
						if($client) {
							$addData = array('email' => $email);
							$name = isset($d['name']) ? trim($d['name']) : '';
							if(!empty($name)) {
								$firstLastName = explode(' ', $name);
								$addData['firstname'] = $firstLastName[ 0 ];
								if(isset($firstLastName[ 1 ]) && !empty($firstLastName[ 1 ])) {
									$addData['lastname'] = $firstLastName[ 1 ];
								}
							}
							if(isset($popup['params']['tpl']['sub_fields'])
								&& !empty($popup['params']['tpl']['sub_fields'])
							) {
								foreach($popup['params']['tpl']['sub_fields'] as $k => $f) {
									if(in_array($k, array('name', 'email'))) continue;	// Ignore standard fields
									if(isset($d[ $k ])) {
										$addData[ $k ] = $d[ $k ]; 
									}
								}
							}
							foreach($lists as $listId) {
								$retval = $client->listAddContacts($listId, array($addData));
								if (!$retval){
									$this->pushError( $client->errorMessage );
									return false;
								}
							}
							return true;
						}
					}
				} else
					$this->pushError (__('No lists to add selected in admin area - contact site owner to resolve this issue.', PPS_LANG_CODE));
			} else
				$this->pushError (framePps::_()->getModule('subscribe')->getModel()->getInvalidEmailMsg($popup), 'email');
		} else
			$this->pushError (framePps::_()->getModule('subscribe')->getModel()->getInvalidEmailMsg($popup), 'email');
		return false;
	}
	public function requireConfirm() {
		if($this->_lastRequireConfirm)
			return true;
		$destData = framePps::_()->getModule('subscribe')->getDestByKey( $this->getCode() );
		return $destData['require_confirm'];
	}
	private function _encrypt($pureString, $encryptionKey = '') {
		if(empty($encryptionKey))
			$encryptionKey = $this->_getEncriptKey();
		if(function_exists('mcrypt_encrypt')) {
			$this->_initCript();
			$encryptionKey = substr($encryptionKey, 0, 16);
			$ivSize = mcrypt_get_iv_size($this->_CIPHER, $this->_MODE);
			$iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
			$ciphertext = mcrypt_encrypt($this->_CIPHER, $encryptionKey, $pureString, $this->_MODE, $iv);
			return base64_encode($iv. $ciphertext);
		} else {
			return base64_encode($pureString);
		}
	}
	private function _decrypt($encryptedString, $encryptionKey = '') {
		if(empty($encryptionKey))
			$encryptionKey = $this->_getEncriptKey();
		if(function_exists('mcrypt_encrypt')) {
			$this->_initCript();
			$encryptionKey = substr($encryptionKey, 0, 16);
			$ciphertext = base64_decode($encryptedString);
			$ivSize = mcrypt_get_iv_size($this->_CIPHER, $this->_MODE);
			if (strlen($ciphertext) < $ivSize) {
				return false;
			}
			$iv = substr($ciphertext, 0, $ivSize);
			$ciphertext = substr($ciphertext, $ivSize);
			$plaintext = mcrypt_decrypt($this->_CIPHER, $encryptionKey, $ciphertext, $this->_MODE, $iv);
			return rtrim($plaintext, "\0");
		} else {
			$decryptedString = base64_decode($encryptedString);
			return $decryptedString;
		}
	}
	private function _initCript() {
		if(defined('MCRYPT_RIJNDAEL_128')) {
			$this->_CIPHER = MCRYPT_RIJNDAEL_128; // Rijndael-128 is AES
		}
		if(defined('MCRYPT_MODE_CBC')) {
			$this->_MODE = MCRYPT_MODE_CBC;
		}
	}
	private function _getEncriptKey() {
		$authKey = AUTH_KEY;
		if(strlen($authKey) < 16) {
			for($i = strlen($authKey); $i < 16; $i++) {
				$authKey .= '1';
			}
		}
		return $authKey;
	}
	public function getApiUsername() {
		if(empty($this->_username)) {
			$this->_username = $this->_decrypt( framePps::_()->getModule('options')->get($this->getCode(). '_username') );
		}
		return $this->_username;
	}
	public function getApiPassword() {
		if(empty($this->_password)) {
			$this->_password = $this->_decrypt( framePps::_()->getModule('options')->get($this->getCode(). '_password') );
		}
		return $this->_password;
	}
	public function setApiUsername($username) {
		framePps::_()->getModule('options')->getModel()->save($this->getCode(). '_username', $this->_encrypt($username));
	}
	public function setApiPassword($password) {
		framePps::_()->getModule('options')->getModel()->save($this->getCode(). '_password', $this->_encrypt($password));
	}
}