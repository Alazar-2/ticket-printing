<?php
/* Warehouse Fixture generated on: 2013-11-19 01:11:11 : 1384825511 */
class WarehouseFixture extends CakeTestFixture {
	var $name = 'Warehouse';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'string' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'format' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'start' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'end' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'current' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'status' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'string' => 'Lorem ipsum dolor sit amet',
			'format' => 1,
			'start' => 1,
			'end' => 1,
			'current' => 1,
			'status' => 'Lorem ipsum dolor sit amet'
		),
	);
}
?>