<?php
if(!class_exists('\\Ctct\\SplClassLoader')) {
	require_once(dirname(__FILE__). DS. '..'. DS. 'classes'. DS. 'Ctct'. DS. 'autoload.php');
}

use Ctct\Auth\CtctOAuth2;
use Ctct\Auth\SessionDataStore;
use Ctct\Exceptions\OAuth2Exception;

use Ctct\ConstantContact;
use Ctct\Components\Contacts\Contact;
use Ctct\Components\Contacts\ContactList;
use Ctct\Components\Contacts\EmailAddress;
use Ctct\Exceptions\CtctException;

class constantcontactModelPps extends modelPps {
	private $_authObjList = array();
	private $_appSecret = 'UTSXWDUZFbZgUZ84EwzQZQau';
	private $_appKey = '27hezqc8nq6w7y3fbvgvmjhe';
	private $_redirectUri = 'https://supsystic.com/constantcontact/api/index.php';
	private $_ccObj = null;
	public function getLists() {
		$ccObj = $this->getCcObj();
		$listsDta = array();
		if($ccObj) {
			if($this->isLoggedIn()) {
				try {
					$lists = $ccObj->getLists( $this->getAccessToken() );
					$listsDta = array();
					foreach($lists as $list) {
						$listsDta[ $list->id ] = $list->name;
					}
				} catch (CtctException $ex) {
					foreach ($ex->getErrors() as $error) {
						$this->pushError($error);
					}
				}
			} else
				$this->pushError(langPps::_('You are not logged-in'));
		} else
			$this->pushError(langPps::_('Can not get cc obj'));
		return empty($listsDta) ? false : $listsDta;
	}
	public function getAccessToken() {
		return framePps::_()->getModule('options')->get($this->getCode(). '_access_token');
	}
	public function getSyncLists() {
		return framePps::_()->getModule('options')->get($this->getCode(). '_sync_lists');
	}
	public function isLoggedIn() {
		return (!framePps::_()->getModule('options')->isEmpty($this->getCode(). '_access_token'));
	}
	public function saveAccessToken($d = array()) {
		if(isset($d['error']) && !empty($d['error'])) {
			$this->pushError($d['error']. ':'. $d['error_description']);
		} elseif(isset($d['code']) && !empty($d['code'])) {
			try {
				$popupId = isset($d['id']) ? (int) $d['id'] : 0;
				$accessTokenData = $this->getAuthObj( $popupId )->getAccessToken($d['code']);
				framePps::_()->getModule('options')->getModel()->save($this->getCode(). '_access_token', $accessTokenData['access_token']);
				return true;
			} catch (OAuth2Exception $ex) {
				$this->pushError($ex->getMessage());
			}
		} else {
			$this->pushError('Empty code returned');
		}
		return false;
	} 
	public function getAuthObj($popupId = 0) {
		if(!isset($this->_authObjList[ $popupId ])) {
			$this->_authObjList[ $popupId ] = new CtctOAuth2(
				$this->_appKey, 
				$this->_appSecret,
				$this->_redirectUri. '?site='. admin_url(). '&pl='. PPS_CODE. '&id='. $popupId
			);
		}
		return $this->_authObjList[ $popupId ];
	}
	public function getCcObj() {
		if(!$this->_ccObj) {
			$this->_ccObj = new ConstantContact($this->_appKey);
		}
		return $this->_ccObj;
	}
	public function subscribe($d, $popup, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email)) {
			if(is_email($email)) {
				$lists = isset($popup['params']['tpl']['sub_cc_lists']) ? $popup['params']['tpl']['sub_cc_lists'] : array();
				if(!empty($lists)) {
					if($this->isLoggedIn()) {
						if(!$validateIp || $validateIp && framePps::_()->getModule('subscribe')->getModel()->checkOftenAccess(array('only_check' => true))) {
							$name = isset($d['name']) ? trim($d['name']) : '';
							try {
								$cc = $this->getCcObj();
								$accessToken = $this->getAccessToken();
								// Check if contact already exists
								$contactRes = $cc->getContactByEmail($accessToken, $email);
								if(isset($contactRes->results, $contactRes->results[ 0 ]) 
									&& !empty($contactRes->results[ 0 ]) 
									&& is_object($contactRes->results[ 0 ])
								) {	// In this case - just add all required lists to contact
									$contact = $contactRes->results[ 0 ];
									foreach($lists as $lId) {
										$contact->addList( $lId );
									}
									$cc->updateContact($accessToken, $contact);
								} else {
									$contactProps = array(
										'email_addresses' => array(array('email_address' => $email)),
										'lists' => $lists,
									);
									$customFields = array();
									if(!empty($name)) {
										$firstLastNames = array_map('trim', explode(' ', $name));
										$contactProps['first_name'] = $firstLastNames[ 0 ];
										if(isset($firstLastNames[ 1 ]) && !empty($firstLastNames[ 1 ])) {
											$contactProps['last_name'] = $firstLastNames[ 1 ];
										}
										$customFields['name'] = $name;	// For some case - when crated suc additional field for example
									}
									if(isset($popup['params']['tpl']['sub_fields'])
										&& !empty($popup['params']['tpl']['sub_fields'])
									) {
										foreach($popup['params']['tpl']['sub_fields'] as $k => $f) {
											if(in_array($k, array('name', 'email'))) continue;	// Ignore standard fields
											if(isset($d[ $k ])) {
												$customFields[ $k ] = $d[ $k ];
												if(!in_array($k, array('id', 'status', 'confirmed', 'source', 'email_addresses')))
													$contactProps[ $k ] = $d[ $k ];	// 
											}
										}
									}
									$contact = Contact::create($contactProps);
									$contactRes = $cc->addContact($accessToken, $contact);
								}
								if($validateIp) {
									framePps::_()->getModule('subscribe')->getModel()->checkOftenAccess(array('only_add' => true));
								}
							// catch any exceptions thrown during the process and print the errors to screen
							} catch (CtctException $ex) {
								$errors = $ex->getErrors();
								if(!empty($errors)) {
									foreach($errors as $e) {
										$this->pushError($e['error_message']);
									}	
								} else {
									$this->pushError (__('Something going wrong while trying to send data to mail list service. Please contact site owner.', PPS_LANG_CODE));
								}
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