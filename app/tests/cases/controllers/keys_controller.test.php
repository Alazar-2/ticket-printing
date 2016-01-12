<?php
/* Keys Test cases generated on: 2013-02-14 11:02:11 : 1360841951*/
App::import('Controller', 'Keys');

class TestKeysController extends KeysController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class KeysControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.key', 'app.branch', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user');

	function startTest() {
		$this->Keys =& new TestKeysController();
		$this->Keys->constructClasses();
	}

	function endTest() {
		unset($this->Keys);
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