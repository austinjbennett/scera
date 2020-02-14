<?php
class salesforceModelPps extends modelPps {
	private $_lastRequireConfirm = false;
	private function _req( $fields ) {
		$url = 'https://www.salesforce.com/servlet/servlet.WebToLead?encoding=UTF-8';
		$kv = array();
		foreach ($fields as $key => $value) {
			$kv[] = stripslashes($key). "=". stripslashes($value);
		}
		$queryString = join("&", $kv);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($kv));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $queryString);

		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		
		$result = curl_exec($ch);
		
		$curlError = curl_error($ch);
		if(!empty($curlError)) {
			$this->pushError( $curlError );
		}
		curl_close($ch);
		
		return $result;
	}
	public function subscribe($d, $popup, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email)) {
			if(is_email($email)) {
				if(!$validateIp || $validateIp && framePps::_()->getModule('subscribe')->getModel()->checkOftenAccess(array('only_check' => true))) {
					$name = isset($d['name']) ? trim($d['name']) : '';
					$addData = array('oid' => $popup['params']['tpl']['sub_sf_app_id'], 'email' => $email);
					if(!empty($name)) {
						$addData['name'] = $name;
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
					if($this->_req($addData)) {
						return true;
					}
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
}