<?php
/* Records Test cases generated on: 2013-11-11 02:11:15 : 1384135335*/
App::import('Controller', 'Records');

class TestRecordsController extends RecordsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class RecordsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.record', 'app.issue', 'app.ticket', 'app.field', 'app.coordinate', 'app.slip', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user');

	function startTest() {
		$this->Records =& new TestRecordsController();
		$this->Records->constructClasses();
	}

	function endTest() {
		unset($this->Records);
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