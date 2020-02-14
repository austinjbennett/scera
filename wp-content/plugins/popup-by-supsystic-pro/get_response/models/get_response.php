<?php
class get_responseModelPps extends modelPps {
	private $_client = null;
	private $_lastRequireConfirm = false;
	public function getClient() {
		if($this->_client)
			return $this->_client;
		if(!class_exists('jsonRPCClient')) {
			require_once($this->getModule()->getModDir(). 'classes'. DS. 'jsonRPCClient.php');
		}
		$apiUrl = 'http://api2.getresponse.com';
		$this->_client = new jsonRPCClient($apiUrl);
		return $this->_client;
	}
	public function getLists($d) {
		$apiKey = isset($d['api_key']) ? trim($d['api_key']) : false;
		if(!empty($apiKey)) {
			$listsData = array();
			$campaigns = $this->getClient()->get_campaigns($apiKey);
			if(!empty($campaigns)) {
				foreach($campaigns as $cId => $cData) {
					$listsData[] = array('id' => $cId, 'name' => $cData['name']);
				}
			}
			return empty($listsData) ? false : $listsData;
		} else
			$this->pushError(__('Please enter your API key', PPS_LANG_CODE), 'params[tpl][sub_gr_api_key]');
		return false;
		
	}
	public function subscribe($d, $popup, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email)) {
			if(is_email($email)) {
				$lists = isset($popup['params']['tpl']['sub_gr_lists']) ? $popup['params']['tpl']['sub_gr_lists'] : array();
				if(!empty($lists)) {
					if(!$validateIp || $validateIp && framePps::_()->getModule('subscribe')->getModel()->checkOftenAccess(array('only_check' => true))) {
						$apiKey = $popup['params']['tpl']['sub_gr_api_key'];
						$name = isset($d['name']) ? trim($d['name']) : '';
						$client =  $this->getClient();
						$addData = array('email' => $email);
						$allCampaigns = $client->get_campaigns($apiKey);
						if(!empty($name)) {
							$addData['name'] = $name;
						}
						if(isset($popup['params']['tpl']['sub_gr_cycle_day']) 
							&& is_numeric($popup['params']['tpl']['sub_gr_cycle_day'])
						) {
							$addData['cycle_day'] = $popup['params']['tpl']['sub_gr_cycle_day'];
						}
						if(isset($popup['params']['tpl']['sub_fields'])
							&& !empty($popup['params']['tpl']['sub_fields'])
						) {
							foreach($popup['params']['tpl']['sub_fields'] as $k => $f) {
								if(in_array($k, array('name', 'email'))) continue;	// Ignore standard fields
								if(isset($d[ $k ])) {
									if(!isset($addData['customs']))
										$addData['customs'] = array();
									$addData['customs'][] = array('name' => $k, 'content' => $d[ $k ]); 
								}
							}
						}
						foreach($lists as $listId) {
							$addData['campaign'] = $listId;
							try {
								$res = $client->add_contact(
									$apiKey,
									$addData
								);
								if($res) {
									$campaign = isset($allCampaigns[ $listId ]) ? $allCampaigns[ $listId ] : false;
									if($campaign && isset($campaign['optin']) && $campaign['optin'] == 'double') {
										$this->_lastRequireConfirm = true;
									}
								}
							} catch(Exception $e) {
								$errorMsg = $e->getMessage();
								if(strpos($errorMsg, ';')) {
									$errorMsgArr = explode(';', $errorMsg);
									$errorMsg = trim( $errorMsgArr[0] );
								}
								$this->pushError( $errorMsg );
								return false;
							}
						}
						return true;
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
}