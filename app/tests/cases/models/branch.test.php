<?php
/* Branch Test cases generated on: 2013-02-13 12:02:14 : 1360760294*/
App::import('Model', 'Branch');

class BranchTestCase extends CakeTestCase {
	var $fixtures = array('app.branch', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user');

	function startTest() {
		$this->Branch =& ClassRegistry::init('Branch');
	}

	function endTest() {
		unset($this->Branch);
		ClassRegistry::flush();
	}

}
?>