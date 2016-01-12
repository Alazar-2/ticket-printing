<?php
/* Warehouse Test cases generated on: 2013-11-19 01:11:11 : 1384825511*/
App::import('Model', 'Warehouse');

class WarehouseTestCase extends CakeTestCase {
	var $fixtures = array('app.warehouse');

	function startTest() {
		$this->Warehouse =& ClassRegistry::init('Warehouse');
	}

	function endTest() {
		unset($this->Warehouse);
		ClassRegistry::flush();
	}

}
?>