<?php
/* Field Test cases generated on: 2013-11-11 02:11:51 : 1384135251*/
App::import('Model', 'Field');

class FieldTestCase extends CakeTestCase {
	var $fixtures = array('app.field', 'app.ticket', 'app.issue', 'app.slip', 'app.coordinate', 'app.record');

	function startTest() {
		$this->Field =& ClassRegistry::init('Field');
	}

	function endTest() {
		unset($this->Field);
		ClassRegistry::flush();
	}

}
?>