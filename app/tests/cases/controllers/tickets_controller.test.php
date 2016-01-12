<?php
/* Tickets Test cases generated on: 2013-11-13 03:11:47 : 1384312907*/
App::import('Controller', 'Tickets');

class TestTicketsController extends TicketsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class TicketsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.ticket', 'app.coordinate', 'app.field', 'app.record', 'app.issue', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.slip');

	function startTest() {
		$this->Tickets =& new TestTicketsController();
		$this->Tickets->constructClasses();
	}

	function endTest() {
		unset($this->Tickets);
		ClassRegistry::flush();
	}

	function testIndex() {

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