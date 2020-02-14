<?php
class on_exitPps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addFIlter('popupListBeforeRender', array($this, 'checkPopupListBeforeRender'), 20);
	}
	public function checkPopupListBeforeRender($popups) {
		if(!empty($popups)) {
			foreach($popups as $p) {
				if(isset($p['params']) && isset($p['params']['main']['show_on']) && $p['params']['main']['show_on'] == 'on_exit') {
					$this->loadAssets();
					break;
				}
			}
		}
		return $popups;
	}
	public function loadAssets() {
		framePps::_()->addScript('frontend.on_exit', $this->getModPath(). 'js/frontend.on_exit.js', array('jquery'));
	}
}

