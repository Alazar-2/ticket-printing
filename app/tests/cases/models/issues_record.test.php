<?php
/* IssuesRecord Test cases generated on: 2016-01-14 14:01:29 : 1452780629*/
App::import('Model', 'IssuesRecord');

class IssuesRecordTestCase extends CakeTestCase {
	var $fixtures = array('app.issues_record', 'app.ticket', 'app.coordinate', 'app.field', 'app.record', 'app.issue', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.slip');

	function startTest() {
		$this->IssuesRecord =& ClassRegistry::init('IssuesRecord');
	}

	function endTest() {
		unset($this->IssuesRecord);
		ClassRegistry::flush();
	}

}
?>