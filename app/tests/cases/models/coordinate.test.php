<?php
/* Coordinate Test cases generated on: 2013-11-13 03:11:15 : 1384312995*/
App::import('Model', 'Coordinate');

class CoordinateTestCase extends CakeTestCase {
	var $fixtures = array('app.coordinate', 'app.ticket', 'app.field', 'app.record', 'app.issue', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.slip');

	function startTest() {
		$this->Coordinate =& ClassRegistry::init('Coordinate');
	}

	function endTest() {
		unset($this->Coordinate);
		ClassRegistry::flush();
	}

}
?>