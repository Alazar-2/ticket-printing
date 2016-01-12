<?php
/* Record Test cases generated on: 2013-11-11 02:11:14 : 1384135334*/
App::import('Model', 'Record');

class RecordTestCase extends CakeTestCase {
	var $fixtures = array('app.record', 'app.issue', 'app.ticket', 'app.field', 'app.coordinate', 'app.slip', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user');

	function startTest() {
		$this->Record =& ClassRegistry::init('Record');
	}

	function endTest() {
		unset($this->Record);
		ClassRegistry::flush();
	}

}
?>