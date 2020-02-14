<?php
class ab_testingPps extends modulePps {
	private $_getListForBase = 0;
	private $_currBaseId = 0;
	public function init() {
		parent::init();
		dispatcherPps::addFilter('popupEditTabs', array($this, 'addPopupEditTab'), 10, 2);	// Add AB tab
		dispatcherPps::addFilter('popupEditTabs', array($this, 'removeUnusedPpupsForAb'), 99, 2);	// Remove unused tabs - for AB popups
		framePps::_()->getTable('popup')->addField('ab_id', 'text', 'int');
		dispatcherPps::addAction('popupModelBeforeGetList', array($this, 'modifyModelList'));
		//dispatcherPps::addFilter('popupCheckCondition', array($this, 'modifyCheckCondition'));
		dispatcherPps::addFilter('popupListBeforeRender', array($this, 'checkPopupListBeforeRender'));
		dispatcherPps::addFilter('popup_getListForTblResults', array($this, 'modifyListResultsForAb'));
		dispatcherPps::addAction('beforePopupEdit', array($this, 'addTopBtns'));
		dispatcherPps::addFilter('popupStatsAdminData', array($this, 'addAbTestStats'), 10, 2);
		dispatcherPps::addFilter('popupShareStatsAdminData', array($this, 'addAbTestShareStats'), 10, 2);
		dispatcherPps::addFilter('popupChangeTpl', array($this, 'popupChangeTpl'), 10, 2);
	}
	public function addPopupEditTab($tabs, $popup) {
		$tabs['ppsPopupAbTesting'] = array(
			'title' => __('Testing', PPS_LANG_CODE), 
			'content' => $this->getView()->getPopupEditTab($popup),
			'icon_content' => '<b>A/B</b>',
			'avoid_hide_icon' => true,
			'sort_order' => 55,
		);
		return $tabs;
	}
	public function removeUnusedPpupsForAb($tabs, $popup) {
		if(isset($popup['ab_id']) && !empty($popup['ab_id'])) {	// Is AB testing popup
			$removeTabs = array(/*'ppsPopupMainOpts',*/ 'ppsPopupAbTesting');
			foreach($removeTabs as $tabKey) {
				if(isset($tabs[ $tabKey ])) {
					unset($tabs[ $tabKey ]);
				}
			}
		}
		return $tabs;
	}
	public function activate() {
		$this->install();	// Just try to do same things for now
	}
	public function install() {
		if(!dbPps::exist("@__popup", "ab_id")) {
			dbPps::query("ALTER TABLE `@__popup` ADD COLUMN `ab_id` INT(11) NOT NULL DEFAULT '0';");
		}
	}
	/**
	 * Don't show AB popups on admin listing
	 */
	public function modifyModelList($model) {
		if($this->_getListForBase) {
			$model->addWhere('ab_id = '. $this->_getListForBase);
		} else
			$model->addWhere('ab_id = 0');

	}
	/**
	 * Don't select AB popups on fronted selection - it will be done later
	 */
	public function modifyCheckCondition($condition) {
		$condition .= ' AND ab_id = 0';
		return $condition;
	}
	public function checkPopupListBeforeRender($popups) {
		// Check if there are AB Test popups in database
		$basePopupIds = array();
		foreach($popups as $p) {
			$basePopupIds[] = $p['id'];
		}
		//$abPopups = framePps::_()->getModule('popup')->getModel()->addWhere('ab_id IN ('. implode(',', $basePopupIds). ') AND active = 1')->getFromTbl();
		$abIds = array();
		
		foreach($popups as $i => $p) {
			if(isset($p['ab_id']) && !empty($p['ab_id'])) {
				$abPopups[] = $p;
				$abIds[] = $p['id'];
			}
		}
		if(!empty($abPopups)) {
			$withoutAb = array();
			// Release main PopUps array - from AB tested PopUps
			foreach($popups as $p) {
				if(!in_array($p['id'], $abIds)) {
					$withoutAb[] = $p;
				}
			}
			$popups = $withoutAb;
			$popupBaseIdToTest = array();
			foreach($abPopups as $p) {
				if(!isset($popupBaseIdToTest[ $p['ab_id'] ])) {
					$popupBaseIdToTest[ $p['ab_id'] ] = array( $p['ab_id'] );
				}
				$popupBaseIdToTest[ $p['ab_id'] ][] = $p['id'];
			}
			foreach($popupBaseIdToTest as $baseId => $selectIds) {
				$randId = $selectIds[ mt_rand(0, count($selectIds) - 1) ];
				if($randId != $baseId) {	// As it can be just base ID
					foreach($popups as $i => $p) {
						if($p['id'] == $baseId) {	// Substitute popup with it's test 
							foreach($abPopups as $abP) {
								if($abP['id'] == $randId) {
									//$abP['params']['main'] = $popups[ $i ]['params']['main'];	// Save Main options from Base popup
									$abP['ab_base_params'] = $popups[ $i ]['params'];			// Save Main options - to be able to look into them in some cases
									$popups[ $i ] = $abP;
									break;
								}
							}
							break;
						}
					}
				}
			}
		}
		return $popups;
	}
	public function setListForBaseId($id) {
		$this->_getListForBase = $id;
	}
	public function modifyListResultsForAb($res) {
		if(!empty($this->_getListForBase)) {
			/*foreach($res->rows as $i => $row) {
				// TODO: modify actions and other info for AB list here
				$res->rows[ $i ]['action'] = 'lalala';
			}*/
		}
		return $res;
	}
	public function addTopBtns($popup) {
		if(isset($popup['ab_id']) && !empty($popup['ab_id'])) {	// Is AB testing popup
			$this->_currBaseId = $popup['ab_id'];
			dispatcherPps::addAction('beforeAdminBreadcrumbs', array($this, 'showEditPopupFormControls'));
			dispatcherPps::addFilter('mainBreadcrumbs', array($this, 'mainBreadcrumbsClear'));
			
		}
	}
	public function mainBreadcrumbsClear($breadcrumbs) {
		if(!empty($this->_currBaseId)) {
			$breadcrumbs = array();	// No breadcrumbs - for AB test popups
		}
		return $breadcrumbs;
	}
	public function showEditPopupFormControls() {
		$this->getView()->displayEditFormControls( $this->_currBaseId );
	}
	public function addAbTestStats($allStats, $popup) {
		if(empty($popup['ab_id'])) {	// Is not AB testing popup
			$abPopups = framePps::_()->getModule('popup')->getModel()->addWhere(array('ab_id' => $popup['id']))->getFromTbl();
			if(!empty($abPopups)) {
				$statModel = framePps::_()->getModule('statistics')->getModel();
				if(!empty($allStats)) {
					// Change initial popup name in graphs
					foreach($allStats as $i => $stat) {
						$allStats[ $i ]['label'] = $allStats[ $i ]['label']. ' - '. $popup['label'];
					}
				}
				foreach($abPopups as $p) {
					$statsForAb = $statModel->getAllForPopupId($p['id']);
					if($statsForAb) {
						foreach($statsForAb as $i => $stat) {
							$statsForAb[ $i ]['label'] = $statsForAb[ $i ]['label']. ' - '. $p['label'];
						}
						if(!$allStats) {
							$allStats = array();
						}
						$allStats = array_merge($allStats, $statsForAb);
					}
				}
			}
		}
		return $allStats;
	}
	public function addAbTestShareStats($allSmAction, $popup) {
		if(empty($popup['ab_id'])) {	// Is not AB testing popup
			$abPopups = framePps::_()->getModule('popup')->getModel()->addWhere(array('ab_id' => $popup['id']))->getFromTbl();
			if(!empty($abPopups)) {
				$statModel = framePps::_()->getModule('statistics')->getModel();
				foreach($abPopups as $p) {
					$statsForAb = $statModel->getSmActionForPopup( $p['id'] );
					if($statsForAb) {
						foreach($statsForAb as $i => $stat) {
							$statsForAb[ $i ]['sm_type']['label'] = $statsForAb[ $i ]['sm_type']['label']. ' - '. $p['label'];
						}
						if(!$allSmAction) {
							$allSmAction = array();
						}
						$allSmAction = array_merge($allSmAction, $statsForAb);
					}
				}
			}
		}
		return $allSmAction;
	}
	public function popupChangeTpl($newTpl, $currentPopup) {
		if(isset($currentPopup['ab_id']) && !empty($currentPopup['ab_id'])) {	// Is AB testing popup
			$newTpl['ab_id'] = $currentPopup['ab_id'];
		}
		return $newTpl;
	}
}

