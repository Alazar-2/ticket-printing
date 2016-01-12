<?php
/* Fields Test cases generated on: 2013-11-11 02:11:51 : 1384135251*/
App::import('Controller', 'Fields');

class TestFieldsController extends FieldsController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class FieldsControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.field', 'app.ticket', 'app.issue', 'app.slip', 'app.coordinate', 'app.record');

	function startTest() {
		$this->Fields =& new TestFieldsController();
		$this->Fields->constructClasses();
	}

	function endTest() {
		unset($this->Fields);
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