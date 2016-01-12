<?php
/* Key Fixture generated on: 2013-02-14 11:02:30 : 1360841670 */
class KeyFixture extends CakeTestFixture {
	var $name = 'Key';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'from_branch_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'to_branch_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'tt_direction' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
		'amount_range' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
		'created' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1)),
		'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
	);

	var $records = array(
		array(
			'id' => 1,
			'from_branch_id' => 1,
			'to_branch_id' => 1,
			'key' => 'Lorem ipsum dolor sit amet',
			'tt_direction' => 'Lorem ipsum dolor sit amet',
			'amount_range' => 'Lorem ipsum dolor sit amet',
			'created' => '2013-02-14',
			'modified' => '2013-02-14'
		),
	);
}
?>