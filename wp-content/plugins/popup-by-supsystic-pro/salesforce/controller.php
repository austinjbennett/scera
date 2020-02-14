<?php
class salesforceControllerPps extends controllerPps {
	public function getPermissions() {
		return array(
			PPS_USERLEVELS => array(
				PPS_ADMIN => array()
			),
		);
	}
}