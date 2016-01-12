<?php
/* Coordinates Test cases generated on: 2013-11-13 03:11:16 : 1384312996*/
App::import('Controller', 'Coordinates');

class TestCoordinatesController extends CoordinatesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class CoordinatesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.coordinate', 'app.ticket', 'app.field', 'app.record', 'app.issue', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.slip');

	function startTest() {
		$this->Coordinates =& new TestCoordinatesController();
		$this->Coordinates->constructClasses();
	}

	function endTest() {
		unset($this->Coordinates);
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