<?php
class sgautorepondeurModelPps extends modelPps {
	private $_requireConfirm = false;
	public function subscribe($d, $popup, $validateIp = false) {
		$email = isset($d['email']) ? trim($d['email']) : false;
		if(!empty($email)) {
			if(is_email($email)) {
				$list = isset($popup['params']['tpl']['sub_sga_list_id']) ? $popup['params']['tpl']['sub_sga_list_id'] : '';
				if(!empty($list)) {
					if(!$validateIp || $validateIp && framePps::_()->getModule('subscribe')->getModel()->checkOftenAccess(array('only_check' => true))) {
						$name = isset($d['name']) ? trim($d['name']) : '';
						$inscriptionNormale = 'oui';	// oui = inscription comme si l'abonné avait lui même rempli le formulaire et donc il y a redirection par l'auto-répondeur.
						$sendData = array(
							'membreid' => $popup['params']['tpl']['sub_sga_id'],
							'codeactivationclient' => $popup['params']['tpl']['sub_sga_activate_code'],
							'inscription_normale' => $inscriptionNormale,
							'listeid' => $popup['params']['tpl']['sub_sga_list_id'],
							'email' => $email,
							'nom' => $name,
							'ip' => utilsPps::getIP(),
						);
						if(isset($popup['params']['tpl']['sub_fields'])
							&& !empty($popup['params']['tpl']['sub_fields'])
						) {
							foreach($popup['params']['tpl']['sub_fields'] as $k => $f) {
								if(in_array($k, array('name', 'email'))) continue;	// Ignore standard fields
								if(isset($d[ $k ])) {
									$sendData[ $k ] = $d[ $k ];
								}
							}
						}
						/*And now - actually subscribe API code*/
						$data_sg_autorep = http_build_query($sendData);

						$fp = fsockopen('sg-autorepondeur.com', 80);

						fwrite($fp, "POST /inscr_decrypt.php HTTP/1.1\r\n");
						fwrite($fp, "Host: sg-autorepondeur.com\r\n");
						fwrite($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
						fwrite($fp, "Content-Length: ".strlen($data_sg_autorep)."\r\n");
						fwrite($fp, "Connection: close\r\n");
						fwrite($fp, "\r\n");

						fwrite($fp, $data_sg_autorep);

						$headers = array();
						$body    = array();
						$i      = 0;
						$inBody = false;

						while (!feof($fp)) {
							if (!$inBody) {
								// Read HTTP headers
								$line = trim(fgets($fp, 1024));
								if ($line != '') {
									$headers[] = $line;
								} else {
									$inBody = true;
									continue;
								}
							} else {
								// Read HTTP body
								$body[] = fgets($fp, 1024);
							}
							$i++;
						}

						// Success means: doesn't return anything at all in the body
						$bodyContent = trim(implode("", $body));
						$result = array('headers' => $headers, 'body' => $bodyContent, 'successful' => !strlen($bodyContent));
						if(!$result['successful']) {
							if(!$this->_parseErrorsFromRes($bodyContent));
								return !$this->haveErrors();
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
	private function _parseErrorsFromRes($bodyContent) {
		if (stristr($bodyContent,'informationmanquante')) { $this->pushError(__('Required fields are missing', PPS_LANG_CODE));/* il manque des champs obligatoires, vous pouvez ajouter ici votre code de gestion. */ };
		if (stristr($bodyContent,'emailexistant')) { $this->pushError(__('The email is already in the list', PPS_LANG_CODE));/* L'email existe déjà dans la liste, vous pouvez ajouter ici votre code de gestion. */ };
		if (stristr($bodyContent,'emailblackliste')) { $this->pushError(__('Registration was refused-blacklisted', PPS_LANG_CODE));/* L'inscription a été refusée-blacklistée, vous pouvez ajouter ici votre code de gestion. */ };
		if (stristr($bodyContent,'paysblackliste')) { $this->pushError(__('The country has been blocked', PPS_LANG_CODE));/* Le pays a été bloqué, vous pouvez ajouter ici votre code de gestion. */ };
		if (stristr($bodyContent,'nombreipimportant')) { $this->pushError(__('Too many entries with the same IP address', PPS_LANG_CODE));/* Trop d'inscriptions avec la même adresse IP, vous pouvez ajouter ici votre code de gestion. */ };
		if (stristr($bodyContent,'nouvellelisteok')) { $this->pushError(__('Ok Register following a behavioral segmentation', PPS_LANG_CODE));/* Inscription OK suite à une segmentation comportementale, vous pouvez ajouter ici votre code de gestion. */ };
		if (stristr($bodyContent,'demandeconfirmation')) { $this->_requireConfirm = true; /*Confirmation request sent - it's ok actually*/ return true; /* Demande de confirmation envoyée (double optin), vous pouvez ajouter ici votre code de gestion. */ };
		if (stristr($bodyContent,'inscriptionok')) { /*Registration is saved - it's ok too? hm...*/ return true; /* L'inscription est enregistrée (simple optin), vous pouvez ajouter ici votre code de gestion. */ };
		if (stristr($bodyContent,'mailformatincorrect')) { $this->pushError(__('The email is not the right format', PPS_LANG_CODE));/* L'email n'est pas au bon format, vous pouvez ajouter ici votre code de gestion. */ };
		if (stristr($bodyContent,'accesinterdit')) { $this->pushError(__('Error on one of the variables - User ID or List ID or Activation Code', PPS_LANG_CODE));/* Erreur sur une des variables $membreid ou $listeid ou $codeactivationclient, vous pouvez ajouter ici votre code de gestion. */ };
		return false;
	}
	public function requireConfirm() {
		if($this->_requireConfirm)
			return true;
		$destData = framePps::_()->getModule('subscribe')->getDestByKey( $this->getCode() );
		return $destData['require_confirm'];
	}
}