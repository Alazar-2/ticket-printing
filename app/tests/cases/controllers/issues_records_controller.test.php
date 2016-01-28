<?php
/* IssuesRecords Test cases generated on: 2016-01-14 14:01:30 : 1452780630*/
App::import('Controller', 'IssuesRecords');

class TestIssuesRecordsController extends IssuesRecordsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class IssuesRecordsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.issues_record', 'app.ticket', 'app.coordinate', 'app.field', 'app.record', 'app.issue', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.slip');

	function startTest() {
		$this->IssuesRecords =& new TestIssuesRecordsController();
		$this->IssuesRecords->constructClasses();
	}

	function endTest() {
		unset($this->IssuesRecords);
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