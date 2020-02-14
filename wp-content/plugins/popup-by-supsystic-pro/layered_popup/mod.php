<?php
class layered_popupPps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addFilter('popupEditDesignTabs', array($this, 'addPopupEditTab'), 10, 2);	// Add AB tab
		dispatcherPps::addFilter('popupListBeforeRender', array($this, 'checkPopupListBeforeRender'));
	}
	public function addPopupEditTab($tabs, $popup) {
		$tabs['ppsPopupLayeredPopup'] = array(
			'title' => __('Popup Location', PPS_LANG_CODE), 
			'content' => $this->getView()->getPopupEditTab($popup),
			'fa_icon' => 'fa-arrows',
			'sort_order' => 15,
		);
		return $tabs;
	}
	public function checkPopupListBeforeRender($popups) {
		$connectAssets = false;
		foreach($popups as $i => $p) {
			if((isset($p['params']['tpl']['enb_layered']) && $p['params']['tpl']['enb_layered'])
				|| (isset($p['ab_id']) && $p['ab_id'] && isset($p['ab_base_params']['tpl']['enb_layered']) && $p['ab_base_params']['tpl']['enb_layered'])	// If 
			) {
				$popups[ $i ]['params']['tpl']['enb_layered'] = 1;	// Save it as layered - if  AB test
				$connectAssets = true;
			}
		}
		if($connectAssets) {
			framePps::_()->addScript('frontend.layered_popup', $this->getModPath(). 'js/frontend.layered_popup.js', array('jquery'));
		}
		return $popups;
	}
}

