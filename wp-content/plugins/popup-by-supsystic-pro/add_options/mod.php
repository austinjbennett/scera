<?php
class add_optionsPps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addAction('editPopupMainOptsCloseOn', array($this, 'showAdminOption'));
		dispatcherPps::addFilter('popupCss', array($this, 'modifyPopupCss'), 10, 2);
		dispatcherPps::addFilter('popupListBeforeRender', array($this, 'checkPopupListBeforeRender'));
		dispatcherPps::addFilter('popupCheckCondition', array($this, 'modifyPopupsCheckCondition'));
		add_filter('comment_post_redirect', array($this, 'modifyRedirectAfterCommentAdd'), 10, 2);
		add_shortcode(PPS_SHORTCODE, array($this, 'showPopupShortcode'));
		dispatcherPps::addFilter('popupListFilterBeforeRender', array($this, 'checkPopupListFilterBeforeRender'));
		// Build-in page content
		add_shortcode(PPS_SHORTCODE_BUILD_IN, array($this, 'showPopupBuildIn'));
	}
	public function modifyPopupsCheckCondition($condition) {
		// Do not render "after comment" popups if this is not required
		if(!$this->_canShowAfterComment()) {
			$popupModel = framePps::_()->getModule('popup')->getModel();
			$showOnCommentId = $popupModel->getShowOnIdByKey('after_comment');
			if($showOnCommentId) {
				$condition .= ' AND show_on != '. $showOnCommentId;
			}
		}
		return $condition;
	}
	private function _canShowAfterComment() {
		$afterCommentAddedTime = (int) reqPps::getVar('ppsCtAdded', 'get');
		return ($afterCommentAddedTime && time() - $afterCommentAddedTime < 10);
	}
	public function showAdminOption($popup) {
		$this->getView()->showAdminOption( $popup );
	}
	public function modifyPopupCss($css, $popup) {
		// Hide close button
		if(isset($popup['params']['main']['close_on']) 
			&& in_array($popup['params']['main']['close_on'], array('after_action'))
			&& !in_array($popup['type'], array('age_verify'))
		) {
			$css .= '#ppsPopupShell_'. $popup['view_id']. ' .ppsPopupClose { display: none !important; }';
		}
		return $css;
	}
	public function checkPopupListBeforeRender($popups) {
		if(!empty($popups)) {
			$needLoadAssets = false;
			$loadAdBlockAssets = false;
			foreach($popups as $p) {
				if(isset($p['params'])) {
					if(isset($p['params']['main']['close_on']) 
						&& in_array($p['params']['main']['close_on'], array('after_action', 'after_time'))
					) {
						$needLoadAssets = true;
					}
					if(isset($p['params']['main']['show_on']) 
						&& in_array($p['params']['main']['show_on'], array('page_bottom', 'after_inactive', 'after_comment', 'link_follow', 'build_in_page'))
					) {
						$needLoadAssets = true;
					}
					if(isset($p['params']['main']['show_on']) 
						&& in_array($p['params']['main']['show_on'], array('adblock_detected'))
					) {
						$needLoadAssets = true;
						$loadAdBlockAssets = true;
					}
				}
			}
			if($needLoadAssets) {
				$this->loadAssets();
			}
			if($loadAdBlockAssets) {
				$this->loadAdBlockAssets();
			}
		}
		return $popups;
	}
	public function loadAdBlockAssets() {
		framePps::_()->addScript(PPS_CODE. '.ads.check', $this->getModPath(). 'js/ads.js');
	}
	public function loadAssets() {
		framePps::_()->addScript('frontend.add_options', $this->getModPath(). 'js/frontend.add_options.js', array('jquery'));
	}
	public function modifyRedirectAfterCommentAdd($location, $comment) {
		$popupModel = framePps::_()->getModule('popup')->getModel();
		$showOnCommentId = $popupModel->getShowOnIdByKey('after_comment');
		if($showOnCommentId) {
			$popupsShowAfterComment = $popupModel->addWhere(array('show_on' => $showOnCommentId))->addWhere(array('active' => 1))->getCount();
			if($popupsShowAfterComment) {	// PopUps with such type exists
				$urlComment = explode('#', $location);
				$url = $urlComment[0];
				$url .= (strpos($url, '?') ? '&' : '?'). 'ppsCtAdded='. time();
				$urlComment[0] = $url;
				$location = implode('#', $urlComment);
			}
		}
		return $location;
	}
	public function showPopupShortcode($params) {
		$id = isset($params['id']) ? (int) $params['id'] : 0;
		if(!$id && isset($params[0]) && !empty($params[0])) {	// For some reason - for some cases it convert space in shortcode - to %20 im this place
			$id = explode('=', $params[0]);
			$id = isset($id[1]) ? (int) $id[1] : 0;
		}
		return '<script> jQuery(document).ready(function(){ setTimeout(function(){ ppsCheckShowPopup('. $id. '); }, 100) }); </script>';
	}
	public function showPopupBuildIn($params) {
		$id = isset($params['id']) ? (int) $params['id'] : 0;
		if(!$id && isset($params[0]) && !empty($params[0])) {	// For some reason - for some cases it convert space in shortcode - to %20 im this place
			$id = explode('=', $params[0]);
			$id = isset($id[1]) ? (int) $id[1] : 0;
		}
		if($id) {
			framePps::_()->getModule('popup')->addToFooterId( $id );
			return '<div class="ppsBuildInPopup" data-id="'. $id. '"></div>';
		}
		return '';
	}
	public function checkPopupListFilterBeforeRender($popups) {
		if(!empty($popups)) {
			$dataRemoved = false;
			$refHost = false;
			$currUrl = false;
			foreach($popups as $i => $p) {
				if(isset($p['params']['main']['hide_for_search_engines']) 
					&& !empty($p['params']['main']['hide_for_search_engines'])
				) {	// Check if popup need to be hidden for some search engines
					if(!$refHost) {	// It can be really empty
						$refHost = utilsPps::getReferalHost();
					}
					$refFound = false;
					foreach($p['params']['main']['hide_for_search_engines'] as $h) {
						if($refHost && strpos($refHost, $h) !== false) {
							$refFound = true;
							break;
						}
					}
					$hideShowRevert = isset($p['params']['main']['hide_search_engines_show']) && (int) $p['params']['main']['hide_search_engines_show'];
					if((!$hideShowRevert && $refFound)
						|| ($hideShowRevert && !$refFound)
					) {
						unset($popups[ $i ]);
						$dataRemoved = true;
					}
				}
				if(isset($p['params']['main']['hide_preg_url']) 
					&& !empty($p['params']['main']['hide_preg_url'])
				) {	// Check if popup need to be hidden for entered URL pattern
					if(!$currUrl) {
						$currUrl = uriPps::getFullUrl();
					}
					$pattern = trim( $p['params']['main']['hide_preg_url'] );
					if(!empty($currUrl) && !empty($pattern)) {
						$matched = false;
						if(preg_match('/'. $pattern. '/i', $currUrl)) {
							$matched = true;
						}
						$hideShowRevert = isset($p['params']['main']['hide_preg_url_show']) && (int) $p['params']['main']['hide_preg_url_show'];
						if((!$hideShowRevert && $matched)
							|| ($hideShowRevert && !$matched)
						) {
							unset($popups[ $i ]);
							$dataRemoved = true;
						}
					}
				}
			}
			if($dataRemoved) {
				$popups = array_values( $popups );
			}
		}
		return $popups;
	}
}

