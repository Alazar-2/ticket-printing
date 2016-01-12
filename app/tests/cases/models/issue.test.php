<?php
/* Issue Test cases generated on: 2013-11-11 02:11:57 : 1384135257*/
App::import('Model', 'Issue');

class IssueTestCase extends CakeTestCase {
	var $fixtures = array('app.issue', 'app.ticket', 'app.field', 'app.coordinate', 'app.record', 'app.slip', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user');

	function startTest() {
		$this->Issue =& ClassRegistry::init('Issue');
	}

	function endTest() {
		unset($this->Issue);
		ClassRegistry::flush();
	}

}
?>