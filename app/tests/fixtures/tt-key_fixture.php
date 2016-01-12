<?php
/* Tt-key Fixture generated on: 2013-02-13 10:02:21 : 1360751961 */
class Tt-keyFixture extends CakeTestFixture {
	var $name = 'Tt-key';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'from_branch_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'to_branch_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'key' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50),
		'tt_direction' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
		'amount_range' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 30),
		'created' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'modified' => array('type' => 'date', 'null' => false, 'default' => NULL),
		'indexes' => array(),
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
			'created' => '2013-02-13',
			'modified' => '2013-02-13'
		),
	);
}
?>