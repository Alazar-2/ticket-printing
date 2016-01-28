<?php
/* IssuesFieldsRecord Test cases generated on: 2016-01-21 13:01:17 : 1453382297*/
App::import('Model', 'IssuesFieldsRecord');

class IssuesFieldsRecordTestCase extends CakeTestCase {
	var $fixtures = array('app.issues_fields_record', 'app.ticket', 'app.coordinate', 'app.field', 'app.record', 'app.issue', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.slip');

	function startTest() {
		$this->IssuesFieldsRecord =& ClassRegistry::init('IssuesFieldsRecord');
	}

	function endTest() {
		unset($this->IssuesFieldsRecord);
		ClassRegistry::flush();
	}

}
?>