<?php
class verticalresponseModelPps extends modelPps {
	private $_clientSecret = 'ugwenVVkJ9g9U2k3WkQnRFj5';
	private $_clientId = 'j6xwup96gxy6snb6r62gee84';
	public function getAccessToken() {
		return framePps::_()->getModule('options')->get($this->getCode(). '_access_token');
	}
	public function isLoggedIn() {
		$accessToken = $this->getAccessToken();
		return !empty($accessToken);
	}
	public function makeRequest($address, $data = array()) {
		$res = array();
		$url = 'https://vrapi.verticalresponse.com/api/v1/';
		//newsletter/lists/get
		$request =  $url. $address;
		// Generate curl request
		$session = curl_init($request);
		if(!empty($data)) {
			curl_setopt ($session, CURLOPT_POST, true);
			curl_setopt ($session, CURLOPT_POSTFIELDS, json_encode($data));
			curl_setopt ($session, CURLOPT_HTTPHEADER, array("Content-Type:application/json"));
		}
		// Tell curl not to return headers, but do return the response
		curl_setopt($session, CURLOPT_HEADER, false);
		curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
		// Verify SSL
		curl_setopt($session, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($session, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($session, CURLOPT_CAINFO, dirname(__FILE__). DS. 'cacert.pem');
		// obtain response
		$response = curl_exec($session);
		$curlError = curl_error($session);
		curl_close($session);
		if(empty($response)) {
			$res['error'] = empty($curlError) ? langPps::_('Some error occured during connection to the server') : $curlError;
		} else {
			$res = utilsPps::jsonDecode($response);
		}
		if(!empty($res['error'])) {
			if(isset($res['error']['failures'])) {
				foreach($res['error']['failures'] as $failKey => $failData) {
					if(is_array($failData)) {
						foreach($failData as $fdKey1 => $fdData1) {
							if(is_array($fdData1)) {
								foreach($fdData1 as $fdKey2 => $fdData2) {
									$this->pushError((is_numeric($fdKey2) ? $fdKey1 : $fdKey2). ': '. $fdData2);
								}
							} else {
								$this->pushError($fdKey1. ': '. $fdData1);
							}
						}
					} else {
						$this->pushError($failKey. ': '. $failData);
					}
				}
			}
			//var_dump();
			$this->pushError(isset($res['error']['message']) ? $res['error']['message'] : $res['error']);
			return false;
		}
		return $res;
	}
	public function getLists() {
		$listsDta = array();
		$lists = $this->makeRequest('lists?access_token='. $this->getAccessToken());
		if($lists && isset($lists['items']) && !empty($lists['items']) && is_array($lists['items'])) {
			foreach($lists['items'] as $l) {
				$listsDta[] = $l['attributes'];
			}
		}
		return empty($listsDta) ? false : $listsDta;
	}
	public function getSyncLists() {
		return framePps::_()->getModule('options')->get($this->getCode(). '_sync_lists');
	}
	public function addRecord($params) {
		$listIds = $this->getSyncLists();
		if(!empty($listIds)) {
			foreach($listIds as $listId) {
				$addData = array('email' => $params['email']);
				if(isset($params['name']) && !empty($params['name'])) {
					if(strpos($params['name'], ' ')) {
						$firstLastName = explode(' ', $params['name']);
						$addData['first_name'] = $firstLastName[0];
						$addData['last_name'] = $firstLastName[1];
					} else
						$addData['first_name'] = $params['name'];
				}
				$this->makeRequest('lists/'. $listId. '/contacts?access_token='. $this->getAccessToken(), $addData);
			}
		}
	}
	public function saveAccessToken($d = array()) {
		if(isset($d['code']) && !empty($d['code'])) {
			$popupId = isset($d['id']) ? (int) $d['id'] : 0;
			$authRes = $this->makeRequest('oauth/access_token?client_id='. $this->getClientId(). 
				'&client_secret='. $this->getClientSecret(). 
				'&redirect_uri='. $this->getFullRedirectUri($popupId). '&code='. $d['code']);
			if($authRes && is_array($authRes)) {
				framePps::_()->getModule('options')->getModel()->save($this->getCode(). '_access_token', $authRes['access_token']);
				return true;
			} else
				$this->pushError(__('Can not access to Vertical Responce server'));
		} else 
			$this->pushError(__('Empty code returned'));
		return false;
	}
	public function getAuthUrl($popuId = 0) {
		return 'https://vrapi.verticalresponse.com/api/v1/oauth/authorize?client_id='. $this->_clientId. '&redirect_uri='. urlencode($this->getFullRedirectUri($popuId));
	}
	public function getFullRedirectUri($popupId = 0) {
		return admin_url(). $this->getModule()->getRedirectAlias(). '/'. $popupId;
	}
	public function getClientId() {
		return $this->_clientId;
	}
	public function getClientSecret() {
		return $this->_clientSecret;
	}
	public function subscribe($d, $popup, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email)) {
			if(is_email($email)) {
				$lists = isset($popup['params']['tpl']['sub_vr_lists']) ? $popup['params']['tpl']['sub_vr_lists'] : array();
				if(!empty($lists)) {
					if($this->isLoggedIn()) {
						if(!$validateIp || $validateIp && framePps::_()->getModule('subscribe')->getModel()->checkOftenAccess(array('only_check' => true))) {
							$name = isset($d['name']) ? trim($d['name']) : '';
							foreach($lists as $listId) {
								$addData = array('email' => $email);
								if(!empty($name)) {
									if(strpos($name, ' ')) {
										$firstLastName = explode(' ',$name);
										$addData['first_name'] = $firstLastName[0];
										$addData['last_name'] = $firstLastName[1];
									} else
										$addData['first_name'] = $name;
								}
								if(isset($popup['params']['tpl']['sub_fields'])
									&& !empty($popup['params']['tpl']['sub_fields'])
								) {
									foreach($popup['params']['tpl']['sub_fields'] as $k => $f) {
										if(in_array($k, array('name', 'email'))) continue;	// Ignore standard fields
										if(isset($d[ $k ])) {
											if(in_array($k, array('first_name', 'last_name'))) {	// This can be in standard fields
												$addData[ $k ] = $d[ $k ];
											} else {
												if(!isset($addData['custom']))
													$addData['custom'] = array();
												$addData['custom'][ $k ] = $d[ $k ]; 
											}
										}
									}
								}
								$res = $this->makeRequest('lists/'. $listId. '/contacts?access_token='. $this->getAccessToken(), $addData);
								if(!$res)
									return false;
							}
							return true;
						}
					} else
						$this->pushError (__('Can not detect authorization fo account owner. Contact site owner to resolve this issue.', PPS_LANG_CODE));
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