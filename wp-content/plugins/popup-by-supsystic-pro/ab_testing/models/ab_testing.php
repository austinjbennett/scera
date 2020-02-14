<?php
class ab_testingModelPps extends modelPps {
	public function create($d = array()) {
		$d['label'] = isset($d['label']) ? trim($d['label']) : '';
		$d['base_id'] = isset($d['base_id']) ? (int) $d['base_id'] : 0;
		if($d['base_id']) {
			if(!empty($d['label'])) {
				$popupModel = framePps::_()->getModule('popup')->getModel();
				$base = $popupModel->getById($d['base_id']);
				unset($base['id']);
				$base['label'] = $d['label'];
				$base['ab_id'] = $d['base_id'];
				if($popupModel->insertFromOriginal( $base )) {
					return true;
				} else
					$this->pushError( $popupModel->getErrors() );
			} else
				$this->pushError(__('Enter Name', PPS_LANG_CODE), 'label');
		} else
			$this->pushError(__('Empty Base ID', PPS_LANG_CODE));
		return false;
	}
	public function clearForBase($baseId) {
		$baseId = (int) $baseId;
		if($baseId) {
			$popupModel = framePps::_()->getModule('popup')->getModel();
			if($popupModel->delete(array('ab_id' => $baseId))) {
				return true;
			} else
				$this->pushError( $popupModel->getErrors() );
		} else
			$this->pushError(__('Empty Base ID', PPS_LANG_CODE));
		return false;
	}
}
