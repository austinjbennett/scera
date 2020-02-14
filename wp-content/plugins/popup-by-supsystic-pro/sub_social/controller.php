<?php
class sub_socialControllerPps extends controllerPps {
	public function fbLogin() {
		$redirectUrl = PPS_SITE_URL;
		$errors = $msgs = array();
		$code = reqPps::getVar('code', 'get');
		$pid = (int) reqPps::getVar('pid', 'get');
		if(!$pid) {
			$errors[] = __('PopUp ID is not specified.', PPS_LANG_CODE);
		} else {
			$popup = framePps::_()->getModule('popup')->getModel()->getById( $pid );
			if (!$code) {
				$errors[] = __('Authorization code is not specified.', PPS_LANG_CODE);
			} else {
				try {
					$socBtns = $this->getModule()->getSocBtns();
					$client = $this->getModule()->getFbClient();
					$client->accessToken($code, $socBtns['fb']['facebook_redirect'] . '/complete/');
					try {
						$userData = $client->api('me?fields=id,name,email');
						if(isset($userData['email']) && !empty($userData['email'])) {
							$email = $userData['email'];
							$login = isset($userData['first_name']) ? $userData['first_name'] : '';
							if(isset($userData['last_name']) && !empty($userData['last_name'])) {
								$login .= empty($login) ? $userData['last_name'] : ' '. $userData['last_name'];
							}
							$formHtml = '';
							$formHtml .= str_replace('<form', '<form style="display:none;"', framePps::_()->getModule('subscribe')->generateFormStart( $popup ));
							$formHtml .= htmlPps::hidden('email', array('value' => $email));
							$formHtml .= htmlPps::hidden('name', array('value' => $login));
							$formHtml .= htmlPps::hidden('pl', array('value' => PPS_CODE));
							$formHtml .= htmlPps::submit('subscribe', array('value' => 'Finish', 'attrs' => 'style="display: none;"'));
							$formHtml .= framePps::_()->getModule('subscribe')->generateFormEnd( $popup );
							$formHtml .= '<div style="width: 100%;">'
									. '<div style="margin: 0 auto; text-align: center;font-weight: bold;width: 400px;padding: 20px;border: 2px solid black;border-radius: 10px;background-color: #fafafa;">'
										. __('Subscribing', PPS_LANG_CODE)
										. '<br />'
										. '<img src="'. uriPps::_(PPS_LOADER_IMG). '" />'
									. '</div>'
									. '</div>';
							$formHtml .= '<script type="text/javascript"> document.forms[0].submit(); </script>';
							echo $formHtml;
							exit();
						} else 
							$errors[] = __('Cannot get your email address', PPS_LANG_CODE);
					} catch (Exception $e) {
						$errors[] = $e->getMessage();
					}
					/*echo '<pre>';
					var_dump($client->api('me'));
					echo '</pre>';
					exit();*/
					//$client->startLoginSession($code);
				   // update_option('facebook_photos', $client->getUserPhotos());
				} catch (Exception $e) {
					$errors[] = $e->getMessage();
				}
			}
		}
		$redirectData = array();
		if(!empty($errors)) {
			$redirectData['ppsErrors'] = $errors;
		}
		if(!empty($msgs)) {
			$redirectData['ppsMsgs'] = $msgs;
		}
        return redirectPps($redirectUrl. '?'. http_build_query($redirectData));
	}
	public function getPermissions() {
		return array(
			PPS_USERLEVELS => array(
				PPS_ADMIN => array()
			),
		);
	}
}

