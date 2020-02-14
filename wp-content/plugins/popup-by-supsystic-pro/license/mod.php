<?php
/**
 * For now this is just dummy mode to identify that we have installed licensed version
 */
class licensePps extends modulePps {
	public function init() {
		parent::init();
		dispatcherPps::addFilter('mainAdminTabs', array($this, 'addAdminTab'));
		add_action('admin_notices', array($this, 'checkActivation'));
		add_action('init', array($this, 'addAfterInit'));
		$this->_licenseCheck();
		$this->_updateDb();

        // add licence activate/renew link to plugin control panel on plugins list
		if(is_admin()) {
			$pathInfo = pathinfo(dirname(__FILE__));
			$plugName = plugin_basename($pathInfo['dirname'] . DS. 'popup-by-supsystic-pro.php');
			add_filter('plugin_action_links_'. $plugName, array($this, 'addLicenseLinkForPlug') );
		}
	}
	public function addAfterInit() {
		if(!function_exists('getProPlugDirPps'))
			return;
		add_action('in_plugin_update_message-'. getProPlugDirPps(). '/'. getProPlugFilePps(), array($this, 'checkDisabledMsgOnList'), 1, 2);
	}
	public function checkDisabledMsgOnList($plugin_data, $r) {
		if($this->getModel()->isExpired()) {
			$licenseTabUrl = framePps::_()->getModule('options')->getTabUrl('license');
			echo '<br />'
			. sprintf(__('Your license has expired. Once you extend your license - you will be able to Update PRO version. To extend PRO version license - follow <a href="%s" target="_blank">this link</a>, then - go to <a href="%s">License</a> tab anc click on "Re-activate" button to re-activate your PRO version.', PPS_LANG_CODE), $this->getExtendUrl(), $licenseTabUrl);
		}
	}
	public function checkActivation() {
		if(!$this->getModel()->isActive()) {
			$plugName = PPS_WP_PLUGIN_NAME;
			$licenseTabUrl = framePps::_()->getModule('options')->getTabUrl('license');
			if($this->getModel()->isExpired()) {
				$msg = sprintf(__('Your license for PRO version of %s plugin has expired. You can <a href="%s" target="_blank">click here</a> to extend your license, then - go to <a href="%s">License</a> tab and click on "Re-activate" button to re-activate your PRO version.', PPS_LANG_CODE), PPS_WP_PLUGIN_NAME, $this->getExtendUrl(), $licenseTabUrl);
			} else {
				$msg = 'You need to activate your copy of PRO version '. $plugName. ' plugin. Go to <a href="'. $licenseTabUrl. '">License</a> tab and finish your software activation process.';
			}
			$html = '<div class="error">'. $msg. '</div>';
			echo $html;
		}
	}
	public function getExtendUrl() {
		return $this->getModel()->getExtendUrl();
	}
	public function addAdminTab($tabs) {
		$tabs[ $this->getCode() ] = array(
			'label' => __('License', PPS_LANG_CODE), 'callback' => array($this, 'getTabContent'), 'fa_icon' => 'fa-hand-o-right', 'sort_order' => 999,
		);
		return $tabs;
	}
	public function getTabContent() {
		return $this->getView()->getTabContent();
	}
	private function _licenseCheck() {
		if($this->getModel()->isActive()) {
			$this->getModel()->check();
			$this->getModel()->checkPreDeactivateNotify();
		}
	}
	private function _updateDb() {
		$this->getModel()->updateDb();
	}
    public function addLicenseLinkForPlug($links) {
        if(is_array($links)) {
            $linkTitle = '';
            $expired = $this->getController()->getModel()->isExpired();
            $isActive = $this->getController()->getModel()->isActive();

            if(!$isActive) {
                $linkTitle = __('Activate License', PPS_LANG_CODE);
            } elseif ($expired) {
                $linkTitle = __('Renew License', PPS_LANG_CODE);
            }
            if($linkTitle) {
                $href = framePps::_()->getModule('options')->getTabUrl('license');
                $links[] = '<a href="' . $href . '">' . $linkTitle . '</a>';
            }
        }
        return $links;
    }
}
