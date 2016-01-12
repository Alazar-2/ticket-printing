<?php
/* Issues Test cases generated on: 2013-11-11 02:11:59 : 1384135259*/
App::import('Controller', 'Issues');

class TestIssuesController extends IssuesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class IssuesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.issue', 'app.ticket', 'app.field', 'app.coordinate', 'app.record', 'app.slip', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user');

	function startTest() {
		$this->Issues =& new TestIssuesController();
		$this->Issues->constructClasses();
	}

	function endTest() {
		unset($this->Issues);
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