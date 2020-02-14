<?php
class infusionsoftModelPps extends modelPps {
	private $_client = null;
	private $_lastRequireConfirm = false;
	
	private $_apiId = 'epfkdyv7r96gweukawn8w4s5';
	private $_apiSecret = 'FtX23GhvGJ';
	private $_apiRedirect = '';
	public function __construct() {
		$this->_apiRedirect = admin_url();
	}
	public function getClient($ignoreLastUrlUpdate = false) {
		if($this->_client)
			return $this->_client;
		if(!class_exists('Infusionsoft')) {
			require_once($this->getModule()->getModDir(). 'vendor'. DS. 'autoload.php');
		}
		$this->_client = new \Infusionsoft\Infusionsoft(array(
			'clientId' => $this->_apiId,
			'clientSecret' => $this->_apiSecret,
			'redirectUri' => $this->_apiRedirect,
		));
		
		if(is_admin() && framePps::_()->isAdminPlugOptsPage() && !$ignoreLastUrlUpdate) {
			framePps::_()->getModule('options')->getModel()->save('infusionsoft_last_url', uriPps::getFullUrl());
		}
		
		$tokenData = $this->_getTokenData();
		if(!empty($tokenData)) {
			$this->_client->setToken( $tokenData );
		}
		/*
		if($tokenData 
			&&($tokenData->accessToken || $tokenData->refreshToken) 
			&& (!isset($tokenData->extraInfo['error']) || !$tokenData->extraInfo['error'])
		) {
			$this->_client->setToken( $tokenData );
		}
		
		try {
			$tokenObj = $this->_client->refreshAccessToken();
			if($tokenObj && isset($tokenObj->extraInfo) && isset($tokenObj->extraInfo['error']) && !empty($tokenObj->extraInfo['error'])) {
				$this->_client->setToken(null);
			} else {
				$this->_saveTokenData( $tokenObj );
			}
		} catch(TokenExpiredException $e) {
			$this->_client->setToken(null);
		} catch(Exception $e) {
			$this->_client->setToken(null);
		}*/
		return $this->_client;
	}
	public function isAutentificated() {
		$client = $this->getClient();
		
		try {
			return $client->getToken() ? true : false;
		} catch(Infusionsoft\Http\HttpException $e) {
			return false;
		} catch(Exception $e) {
			return false;
		}
	}
	public function getAuthUrl() {
		return $this->getClient()->getAuthorizationUrl(base64_encode(uriPps::getFullUrl()));
	}
	public function saveTokenData($token) {
		framePps::_()->getModule('options')->getModel()->save('infusionsoft_access_token', serialize($token));
	}
	private function _getTokenData() {
		//framePps::_()->getModule('options')->getModel()->save('infusionsoft_access_token', '');
		$token = framePps::_()->getModule('options')->get('infusionsoft_access_token');
		if(!empty($token)) {
			return unserialize($token);
		}
		return false;
	}
	public function subscribe($d, $popup, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email)) {
			if(is_email($email)) {
				if(!$validateIp || $validateIp && framePps::_()->getModule('subscribe')->getModel()->checkOftenAccess(array('only_check' => true))) {
					$client = $this->getClient();
					try {
						//$client->refreshAccessToken();
					} catch(TokenExpiredException $e) {
						$this->pushError(__('Administrator of this site need to re-autentificate in InfusionSoft system from admin area', PPS_LANG_CODE));
						return false;
					} catch(Exception $e) {
						$this->pushError($e->getMessage());
						return false;
					}
					
					$addData = array('Email' => $email);
					$name = isset($d['name']) ? trim($d['name']) : '';

					if(!empty($name)) {
						$firstLastName = explode(' ', $name);
						$addData['FirstName'] = $firstLastName[ 0 ];
						if(isset($firstLastName[ 1 ]) && !empty($firstLastName[ 1 ])) {
							$addData['LastName'] = $firstLastName[ 1 ];
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
					try {
						$cid = $client->contacts()->addWithDupCheck($addData, 'Email');	// Also addWithDupCheck() can be used here
					} catch (\Infusionsoft\TokenExpiredException $e) {
						// If the request fails due to an expired access token, we can refresh
						// the token and then do the request again.
						try {
							$token = $client->refreshAccessToken();
							if($token) {
								$this->saveTokenData( $token );
							}
						} catch(Infusionsoft\Http\HttpException $eHttp) {
							$this->pushError($eHttp->getMessage());
							return false;
						}
						$cid = $client->contacts()->addWithDupCheck($addData, 'Email');
					} catch(Exception $e) {
						$this->pushError($e->getMessage());
						return false;
					}
					if($cid) {
						$client->emails()->optIn($addData['Email'], 'auto');
						// Check companies
						// Not used for now - check explanation in template view file for this module
						$companiesIds = isset($popup['params']['tpl']['sub_is_companies']) && !empty($popup['params']['tpl']['sub_is_companies'])
							? array_map('trim', explode(',', $popup['params']['tpl']['sub_is_companies']))
							: false;
						if(!empty($companiesIds)) {
							foreach($companiesIds as $campaignId) {
								$campaignId = (int) $campaignId;
								if($campaignId) {
									try {
										$client->contacts()->addToGroup($cid, $campaignId);
									} catch(Exception $e) {
										$this->pushError($e->getMessage());
										return false;
									}
								}
							}
						}
						// Check tags (groups)
						$groupsIds = isset($popup['params']['tpl']['sub_is_tags']) && !empty($popup['params']['tpl']['sub_is_tags'])
							? array_map('trim', explode(',', $popup['params']['tpl']['sub_is_tags']))
							: false;
						if(!empty($groupsIds)) {
							foreach($groupsIds as $groupId) {
								$groupId = (int) $groupId;
								if($groupId) {
									try {
										$client->contacts()->addToGroup($cid, $groupId);
									} catch(Exception $e) {
										$this->pushError($e->getMessage());
										return false;
									}
								}
							}
						}
					}
					return true;
				}
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
	public function trySetAuthCode() {
		$scope = reqPps::getVar('scope', 'get');
		$code = reqPps::getVar('code', 'get');
		if(!empty($scope) 
			&& !empty($code)
			&& strpos($scope, 'infusionsoft') !== false
			&& is_admin() 
			&& framePps::_()->getModule('user')->isAdmin()
		) {
			$lastPlugUrl = framePps::_()->getModule('options')->getModel()->get('infusionsoft_last_url');
			if(empty($lastPlugUrl)) {
				$lastPlugUrl = framePps::_()->getModule('options')->getTabUrl();
			}
			$client = $this->getClient();
			try {
				$token = $client->requestAccessToken( $code );
				if($token) {
					$this->saveTokenData( $token );
}
			} catch(Infusionsoft\Http\HttpException $e) {
				
			} catch(Exception $e) {
				
			}
			
			redirectPps( $lastPlugUrl );
		}
	}
}