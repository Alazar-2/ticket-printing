<?php
/* IssuesFieldsRecords Test cases generated on: 2016-01-21 13:01:19 : 1453382299*/
App::import('Controller', 'IssuesFieldsRecords');

class TestIssuesFieldsRecordsController extends IssuesFieldsRecordsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class IssuesFieldsRecordsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.issues_fields_record', 'app.ticket', 'app.coordinate', 'app.field', 'app.record', 'app.issue', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.slip');

	function startTest() {
		$this->IssuesFieldsRecords =& new TestIssuesFieldsRecordsController();
		$this->IssuesFieldsRecords->constructClasses();
	}

	function endTest() {
		unset($this->IssuesFieldsRecords);
		ClassRegistry::flush();
	}

	function testIndex() {

	}

	function testIndex2() {

	}

	function testSearch() {

	}

	function testListDatum() {

	}

	function testView() {

	}

	function testAdd() {

	}

	function testEdit() {

	}

	function testDelete() {

	}

}
?>