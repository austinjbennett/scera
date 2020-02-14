<?php
class ab_testingViewPps extends viewPps {
	public function getPopupEditTab($popup) {
		framePps::_()->getModule('templates')->loadJqGrid();
		framePps::_()->addScript('admin.ab_testing', $this->getModule()->getModPath(). 'js/admin.ab_testing.js');
		framePps::_()->addJSVar('admin.ab_testing', 'ppsAbTblDataUrl', uriPps::mod('ab_testing', 'getListForTbl', array('reqType' => 'ajax', 'base_id' => $popup['id'])));
		
		$this->assign('popup', $popup);
		add_action('admin_footer', array($this, 'showAdNewForm'));
		return parent::getContent('abPopupEditTab');
	}
	/**
	 * For show be on footer - as in other case it will be in base popup edit form - and this is incorrect for HTML: have child form element in other form element
	 */
	public function showAdNewForm() {
		parent::display('abNewForm');
	}
	public function displayEditFormControls($baseId) {
		$this->assign('editBaseLink', framePps::_()->getModule('popup')->getEditLink( $baseId, 'ppsPopupAbTesting' ));
		parent::display('abEditFormControls');
	}
}
