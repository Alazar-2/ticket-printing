<?php
/* Branch Fixture generated on: 2013-02-13 12:02:10 : 1360760290 */
class BranchFixture extends CakeTestFixture {
	var $name = 'Branch';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'list_order' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'fc_code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 10),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'name' => 'Lorem ipsum dolor sit amet',
			'list_order' => 1,
			'fc_code' => 'Lorem ip',
			'created' => '2013-02-13 12:58:10',
			'modified' => '2013-02-13 12:58:10'
		),
	);
}
?>