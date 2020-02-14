<?php
class sub_socialPps extends modulePps {
	private $_socialBtns = array(
		'fb' => array(
			'facebook_key' => '1619389244949993', 
			'facebook_secret' => '8011c81a02efe909a6cccb6c4c2c323e',
			'facebook_redirect' => 'http://supsystic.com/authenticator/index.php/authenticator/facebook'),
	);
	private $_fbClient = null;
	public function init() {
		parent::init();
		dispatcherPps::addFilter('subFormEnd', array($this, 'addBottomSocialBtns'), 10, 2);
		dispatcherPps::addFilter('popupCss', array($this, 'addSocialBtnsCss'), 10, 2);
		//dispatcherPps::applyFilters('popupCss', $popup['css'], $popup);
	}
	public function addBottomSocialBtns($content, $popup) {
		foreach($this->_socialBtns as $k => $data) {
			if(isset($popup['params']['tpl']['sub_enb_'. $k. '_subscribe']) && $popup['params']['tpl']['sub_enb_'. $k. '_subscribe']) {
				$htmlMtd = '_getBtnHtml_'. $k;
				if(method_exists($this, $htmlMtd)) {
					$content = $this->$htmlMtd( $popup ). $content;//''. $content;
				}
				//$content = '<a style="clear:both; display: block;" href="">Connect with FB</a>'. $content;
			}
		}
		return $content;
	}
	public function addSocialBtnsCss($css, $popup) {
		$hasBtn = false;
		foreach($this->_socialBtns as $k => $data) {
			if(isset($popup['params']['tpl']['sub_enb_'. $k. '_subscribe']) && $popup['params']['tpl']['sub_enb_'. $k. '_subscribe']) {
				$cssMtd = '_getCss_'. $k;
				if(method_exists($this, $cssMtd)) {
					$css .= $this->$cssMtd( $popup );
				}
				$hasBtn = true;
			}
		}
		if($hasBtn) {
			$css .= $this->_getCommonCss( $popup );
		}
		return $css;
	}
	private function _getCommonCss($popup) {
		$css = '#'. $popup['view_html_id']. ' .ppsPopupSubSocBtn {'
				. 'display: block;'
				. 'clear: both;'
				. 'color: #fff;'
				. 'text-decoration: none;'
				. '-webkit-box-shadow: inset 0px -5px 5px -4px rgba(0,0,0,0.4);
-moz-box-shadow: inset 0px -5px 5px -4px rgba(0,0,0,0.4);
box-shadow: inset 0px -5px 5px -4px rgba(0,0,0,0.4);'
				. '}';
		$css .= '#'. $popup['view_html_id']. ' .ppsSocBtnLeft {'
				. 'background-repeat: no-repeat;'
				. 'background-position: right center;'
				. 'float: left;'
				. 'width: 30px;'
				. 'height: 30px;'
				. 'padding: 5px 0 5px 10px;'
				. 'border-right: 2px groove rgba(150, 150, 150, 0.4);'
				. '}';
		return $css;
	}
	private function _getCss_fb($popup) {
		$css = '#'. $popup['view_html_id']. ' .ppsPopupSubSocBtn_fb { background-color: #2980b9; }';
		$css .= '#'. $popup['view_html_id']. ' .ppsSocBtnLeft_fb { background-image: url("'. $this->getModPath(). 'img/fb_standard.png"); }';
		$css .= '#'. $popup['view_html_id']. ' .ppsSocBtnRight_fb { display: block; float: right; padding: 10px; }';
		return $css;
	}
	private function _getBtnHtml_fb($popup) {
		return '<a class="ppsPopupSubSocBtn ppsPopupSubSocBtn_standard ppsPopupSubSocBtn_fb" href="'. $this->getFbLoginUrl($popup). '">'
			. '<span class="ppsSocBtnLeft ppsSocBtnLeft_fb"></span>'
			. '<span class="ppsSocBtnRight ppsSocBtnRight_fb">'
				. __('Sign in with', PPS_LANG_CODE)
				. ' <b>'. __('Facebook', PPS_LANG_CODE). '</b>'
			. '</span><div style="clear: both;"></div>'
		. '</a>';
	}
	public function getFbClient() {
		if(!$this->_fbClient) {
			require_once($this->getModDir(). 'classes/facebook.php');
			$this->_fbClient = new Facebook(array(
				'appId'  => $this->_socialBtns['fb']['facebook_key'],
				'secret' => $this->_socialBtns['fb']['facebook_secret'],
			));
			
		}
		return $this->_fbClient;
	}
	public function getFbLoginUrl($popup) {
		$loginUrl = $this->getFbClient()->getLoginUrl(
            array(
                'scope' => 'email',
                'redirect_uri'  => $this->_socialBtns['fb']['facebook_redirect'] . '/complete/'
            )
        );
		return $this->_socialBtns['fb']['facebook_redirect']. '?'. http_build_query(array(
			'url' => $loginUrl,
			'state' => uriPps::mod('sub_social', 'fbLogin', array('pid' => $popup['id'])),
		));

	}
	public function getSocBtns() {
		return $this->_socialBtns;
	}
}

