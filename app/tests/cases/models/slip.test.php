<?php
/* Slip Test cases generated on: 2013-11-13 03:11:59 : 1384313279*/
App::import('Model', 'Slip');

class SlipTestCase extends CakeTestCase {
	var $fixtures = array('app.slip', 'app.ticket', 'app.coordinate', 'app.field', 'app.record', 'app.issue', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user');

	function startTest() {
		$this->Slip =& ClassRegistry::init('Slip');
	}

	function endTest() {
		unset($this->Slip);
		ClassRegistry::flush();
	}

}
?>