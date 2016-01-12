<?php
/* Warehouses Test cases generated on: 2013-11-19 01:11:12 : 1384825512*/
App::import('Controller', 'Warehouses');

class TestWarehousesController extends WarehousesController {
	var $autoRender = false;

	function redirect($url, $status = null, $exit = true) {
		$this->redirectUrl = $url;
	}
}

class WarehousesControllerTestCase extends CakeTestCase {
	var $fixtures = array('app.warehouse');

	function startTest() {
		$this->Warehouses =& new TestWarehousesController();
		$this->Warehouses->constructClasses();
	}

	function endTest() {
		unset($this->Warehouses);
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