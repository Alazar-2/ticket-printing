<?php
/* Slips Test cases generated on: 2013-11-11 02:11:27 : 1384135347*/
App::import('Controller', 'Slips');

class TestSlipsController extends SlipsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class SlipsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.slip', 'app.ticket', 'app.field', 'app.coordinate', 'app.record', 'app.issue', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user');

	function startTest() {
		$this->Slips =& new TestSlipsController();
		$this->Slips->constructClasses();
	}

	function endTest() {
		unset($this->Slips);
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