<?php
/* Issue Fixture generated on: 2013-11-11 02:11:57 : 1384135257 */
class IssueFixture extends CakeTestFixture {
	var $name = 'Issue';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'ticket_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'status' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'ticket_id' => 1,
			'user_id' => 1,
			'status' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-11-11 02:00:57'
		),
	);
}
?>