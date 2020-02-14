<?php
class campaignmonitorModelPps extends modelPps {
	private $_clientSecret = '6b661f5WB6SF6YYEyh6U6u66PsE6oD6tbH5E6YIun8d666UTYU5GlXWCBYgYbaEpB6V667m66abY6UZU';
	private $_clientId = '101457';
	private $_redirectUri = 'https://supsystic.com/campaignmonitor/api/index.php';
	
	private $_requireConfirm = false;
	public function getLists() {
		$listsDta = array();
		$lists = $this->getFullLists();
		if($lists) {
			foreach($lists as $list) {
				$listsDta[ $list->ListID ] = $list->Name;
			}
		} else
			$this->pushError(sprintf(__('You have no lists for now. Go to your <a href="%s" target="_blank">Campaign Monitor Account -> Lists & Subscribers</a> and create list at first, then just reload this page', PPS_LANG_CODE), 'https://supsysticcom.createsend.com/subscribers/'));
		return empty($listsDta) ? false : $listsDta;
	}
	public function getAccessToken() {
		return framePps::_()->getModule('options')->get($this->getCode(). '_access_token');
	}
	public function getRefreshToken() {
		return framePps::_()->getModule('options')->get($this->getCode(). '_refresh_token');
	}
	public function getSyncLists() {
		return framePps::_()->getModule('options')->get($this->getCode(). '_sync_lists');
	}
	public function isLoggedIn() {
		if(!framePps::_()->getModule('options')->isEmpty($this->getCode(). '_access_token') 
			&& !framePps::_()->getModule('options')->isEmpty($this->getCode(). '_refresh_token')
			&& !framePps::_()->getModule('options')->isEmpty($this->getCode(). '_expire_in')
		) {
			$expireIn = framePps::_()->getModule('options')->get($this->getCode(). '_expire_in');
			$timeRequested = framePps::_()->getModule('options')->get($this->getCode(). '_time_requested');
			// Tokens will be expired after request time + time in seconds that it can be active
			if($timeRequested + $expireIn > time()) {
				return true;
			}
		}
		return false;
	}
	public function saveAccessToken($d = array()) {
		if(isset($d['error']) && !empty($d['error'])) {
			$this->pushError($d['error']);
		} elseif(isset($d['code']) && !empty($d['code'])) {
			$popupId = isset($d['id']) ? (int) $d['id'] : 0;
			$result = $this->exchangeToken($d['code'], $popupId);
			if($result->was_successful()) {
				framePps::_()->getModule('options')->getModel()->saveGroup(array('opt_values' => array(
					$this->getCode(). '_access_token' => $result->response->access_token,
					$this->getCode(). '_refresh_token' => $result->response->refresh_token,
					$this->getCode(). '_expire_in' => $result->response->expires_in,
					$this->getCode(). '_time_requested' => time(),
				)));
				return true;
			} else {
				$this->pushError($result->response->error.': '.$result->response->error_description);
			}
		} else {
			$this->pushError('Empty code returned');
		}
		return false;
	}
	public function getFullLists() {
		$lists = array();
		$this->loadLib();
		$clientIds = $this->getClientIds();
		if($clientIds) {
			foreach($clientIds as $cid) {
				$wrap = $this->getClientsWrap($cid);
				if($wrap) {
					$listsRes = $wrap->get_lists();
					if($listsRes->was_successful()) {
						foreach($listsRes->response as $list) {
							$lists[] = $list;
						}
					}
				}
			}
		}
		return empty($lists) ? false : $lists;
	}
	public function getFullRedirectUri($popupId = 0) {
		return $this->_redirectUri. '?site='. admin_url(). '&pl='. PPS_CODE. '&id='. $popupId;
	}
	public function getAuthUrl($popupId = 0) {
		$this->loadLib();
		$state = '';
		$scope = 'ManageLists,ImportSubscribers';
		return CS_REST_General::authorize_url(
			$this->_clientId, 
			$this->getFullRedirectUri( $popupId ), 
			$scope, 
			$state
		);
	}
	public function exchangeToken($code, $popupId = 0) {
		$this->loadLib();
		return CS_REST_General::exchange_token($this->_clientId, $this->_clientSecret, $this->getFullRedirectUri( $popupId ), $code);
	}
	public function getAuthArray() {
		return array(
			'access_token' => $this->getAccessToken(),
			'refresh_token' => $this->getRefreshToken(),
		);
	}
	public function getClientsWrap($clientId) {
		$this->loadLib();
		return new CS_REST_Clients(
			$clientId, 
			$this->getAuthArray());
	}
	public function loadLib() {
		static $loaded;
		if(!$loaded) {
			require_once($this->getModule()->getModDir(). 'classes'. DS. 'csrest_general.php');
			require_once($this->getModule()->getModDir(). 'classes'. DS. 'csrest_clients.php');
			require_once($this->getModule()->getModDir(). 'classes'. DS. 'csrest_subscribers.php');
			require_once($this->getModule()->getModDir(). 'classes'. DS. 'csrest_lists.php');
			$loaded = true;
		}
	}
	public function getClientIds() {
		$this->loadLib();
		$wrap = new CS_REST_General($this->getAuthArray());
		$result = $wrap->get_clients();
		if($result->was_successful()) {
			$res = array();
			foreach($result->response as $client) {
				$res[] = $client->ClientID;
			}
			return $res;
		}
		return false;
	}
	public function subscribe($d, $popup, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email)) {
			if(is_email($email)) {
				$lists = isset($popup['params']['tpl']['sub_cm_lists']) ? $popup['params']['tpl']['sub_cm_lists'] : array();
				if(!empty($lists)) {
					if($this->isLoggedIn()) {
						if(!$validateIp || $validateIp && framePps::_()->getModule('subscribe')->getModel()->checkOftenAccess(array('only_check' => true))) {
							$name = isset($d['name']) ? trim($d['name']) : '';
							$this->loadLib();

							foreach($lists as $listId) {
								$wrap = $this->getSubscribersObj($listId);
								$addSubscriberParams = array(
									'EmailAddress' => $email,
									'Resubscribe' => true,
									//'RestartSubscriptionBasedAutoresponders' => isset($params['RestartSubscriptionBasedAutoresponders']) ? $params['RestartSubscriptionBasedAutoresponders'] : false,
								);
								if(!empty($name)) {
									$addSubscriberParams['Name'] = $name;
								}
								if(isset($popup['params']['tpl']['sub_fields'])
									&& !empty($popup['params']['tpl']['sub_fields'])
								) {
									$addSubscriberParams['CustomFields'] = array();
									foreach($popup['params']['tpl']['sub_fields'] as $k => $f) {
										if(in_array($k, array('name', 'email'))) continue;	// Ignore standard fields
										if(isset($d[ $k ])) {
											if(!isset($addSubscriberParams['CustomFields']))
												$addSubscriberParams['CustomFields'] = array();
											$addSubscriberParams['CustomFields'][] = array(
												'Key' => $k,
												'Value' => $d[ $k ],
											);
										}
									}
								}
								$result = $wrap->add( $addSubscriberParams );
								if($result->was_successful()) {
									if(!$this->_requireConfirm) {	// There were no required confirm lists for now
										$listObj = $this->getListObj($listId);
										if($listObj && ($listData = $listObj->get())) {
											if($listData->response->ConfirmedOptIn) {	// If at least one list require confirnation - show message that confirnation link was sent
												$this->_requireConfirm = true;
											}
										}
									}
								} else {
									// If there will be error here - try to check $result->response property
									$this->pushError (__('Something going wrong while trying to send data to mail list service. Please contact site owner.', PPS_LANG_CODE));
									return false;
								}
							}
							if($validateIp) {
								framePps::_()->getModule('subscribe')->getModel()->checkOftenAccess(array('only_add' => true));
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
	public function getSubscribersObj($listId) {
		$this->loadLib();
		return new CS_REST_Subscribers($listId, $this->getAuthArray());
	}
	public function getListObj($listId) {
		$this->loadLib();
		return new CS_REST_Lists($listId, $this->getAuthArray());
	}
	public function requireConfirm() {
		if($this->_requireConfirm)
			return true;
		$destData = framePps::_()->getModule('subscribe')->getDestByKey( $this->getCode() );
		return $destData['require_confirm'];
	}
}