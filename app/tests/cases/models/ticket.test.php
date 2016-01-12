<?php
/* Ticket Test cases generated on: 2013-11-13 03:11:45 : 1384312905*/
App::import('Model', 'Ticket');

class TicketTestCase extends CakeTestCase {
	var $fixtures = array('app.ticket', 'app.coordinate', 'app.field', 'app.record', 'app.issue', 'app.user', 'app.person', 'app.location', 'app.location_type', 'app.branch', 'app.bank', 'app.group', 'app.permission', 'app.groups_permission', 'app.groups_user', 'app.slip');

	function startTest() {
		$this->Ticket =& ClassRegistry::init('Ticket');
	}

	function endTest() {
		unset($this->Ticket);
		ClassRegistry::flush();
	}

}
?>