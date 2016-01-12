<?php
/* Coordinate Fixture generated on: 2013-11-13 03:11:15 : 1384312995 */
class CoordinateFixture extends CakeTestFixture {
	var $name = 'Coordinate';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'x' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'y' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'length' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'order' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'ticket_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'field_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'alignment' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'x' => 1,
			'y' => 1,
			'length' => 1,
			'order' => 1,
			'ticket_id' => 1,
			'field_id' => 1,
			'alignment' => 'Lorem ipsum dolor sit amet'
		),
	);
}
?>