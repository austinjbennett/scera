<?php
class mailrelayModelPps extends modelPps {
	private $_CIPHER = null;//MCRYPT_RIJNDAEL_128; // Rijndael-128 is AES
    private $_MODE   = null;//MCRYPT_MODE_CBC;
	private $_host = '';
	private $_key = '';
	public function getLists() {
		$listsDta = array();
		$listsRes = $this->makeRequest('getGroups', array('sortField' => 'name', 'sortOrder' => 'ASC'));
		if($listsRes && is_array($listsRes)) {
			foreach($listsRes['data'] as $list) {
				$listsDta[ $list['id'] ] = $list['name'];
			}
		} elseif(!$this->haveErrors())
			$this->pushError(__('You have no lists. Login to your Mailrelay account and create your first list before start using this functionality.', PPS_LANG_CODE));
		return empty($listsDta) ? false : $listsDta;
	}
	
	public function getApiHost() {
		if(empty($this->_host)) {
			$this->_host = $this->_decrypt( framePps::_()->getModule('options')->get($this->getCode(). '_host') );
		}
		return $this->_host;
	}
	public function getApiKey() {
		if(empty($this->_key)) {
			$this->_key = $this->_decrypt( framePps::_()->getModule('options')->get($this->getCode(). '_key') );
		}
		return $this->_key;
	}
	public function setApiHost($host) {
		framePps::_()->getModule('options')->getModel()->save($this->getCode(). '_host', (empty($host) ? $host : $this->_encrypt($host)));
	}
	public function setApiKey($key) {
		framePps::_()->getModule('options')->getModel()->save($this->getCode(). '_key', (empty($key) ? $key : $this->_encrypt($key)));
	}
	public function makeRequest($address, $data = array()) {
		$mergeData = array(
			'function' => $address,
			'apiKey' => $this->getApiKey(),
		);
		$data = array_merge($mergeData, $data);
		
		$url = (uriPps::isHttps() ? 'https' : 'http'). '://' . $this->getApiHost(). '/ccm/admin/api/version/2/&type=json';
		$curl = curl_init($url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		$headers = array(
			'X-Request-Origin: Wordpress|'. PPS_VERSION .'|'. get_bloginfo('version'),
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
		$res = curl_exec($curl);
		$curlError = curl_error($curl);
		curl_close($curl);
		if(empty($res)) {
			$res['error'] = empty($curlError) ? langPps::_('Some error occured during connection to the server') : $curlError;
		} else {
			$res = utilsPps::jsonDecode($res);
		}
		if(!empty($res['error'])) {
			$this->pushError($res['error']);
			return false;
		}
		return $res;
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
	public function isLoggedIn() {
		$host = $this->getApiHost();
		$key = $this->getApiKey();
		return !empty($host) && !empty($key);
	}
	public function subscribe($d, $popup, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email)) {
			if(is_email($email)) {
				$lists = isset($popup['params']['tpl']['sub_mr_lists']) ? $popup['params']['tpl']['sub_mr_lists'] : array();
				if(!empty($lists)) {
					if($this->isLoggedIn()) {
						if(!$validateIp || $validateIp && framePps::_()->getModule('subscribe')->getModel()->checkOftenAccess(array('only_check' => true))) {
							
							$name = isset($d['name']) ? trim($d['name']) : '';
							$addData = array(
								'email' => $email,
								'name' => $name,
								'groups' => $lists,
							);
							if(isset($popup['params']['tpl']['sub_fields'])
								&& !empty($popup['params']['tpl']['sub_fields'])
							) {
								foreach($popup['params']['tpl']['sub_fields'] as $k => $f) {
									if(in_array($k, array('name', 'email'))) continue;	// Ignore standard fields
									if(isset($d[ $k ])) {
										if(!isset($addData['customFields']))
											$addData['customFields'] = array();
										$addData['customFields'][ $k ] = $d[ $k ];
									}
								}
							}
							// Call getSubscribers
							$checkSubscriber = $this->makeRequest('getSubscribers', array('email' => $email));
							
							if (count($checkSubscriber['data']) > 0) {
								$callFunc = 'updateSubscriber';
								$addData['id'] = $checkSubscriber['data'][0]['id'];
							} else {
								$callFunc = 'addSubscriber';
							}
							$res = $this->makeRequest($callFunc, $addData);
							if($res) {
								if((int) $res['status'] == 1) {
									return true;
								} else {
									$this->pushError(__('Failed to create subscriber.', PPS_LANG_CODE));
								}
							}
							return false;
						}
					} else
						$this->pushError (__('Can not detect Host and API key. Contact site owner to resolve this issue.', PPS_LANG_CODE));
				} else
					$this->pushError (__('No lists to add selected in admin area - contact site owner to resolve this issue.', PPS_LANG_CODE));
			} else
				$this->pushError (framePps::_()->getModule('subscribe')->getModel()->getInvalidEmailMsg($popup), 'email');
		} else
			$this->pushError (framePps::_()->getModule('subscribe')->getModel()->getInvalidEmailMsg($popup), 'email');
		return false;
	}
	public function requireConfirm() {
		$destData = framePps::_()->getModule('subscribe')->getDestByKey( $this->getCode() );
		return $destData['require_confirm'];
	}
}