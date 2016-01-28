<?php
/* IssuesRecord Fixture generated on: 2016-01-14 14:01:29 : 1452780629 */
class IssuesRecordFixture extends CakeTestFixture {
	var $name = 'IssuesRecord';

	var $fields = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => '0', 'key' => 'primary'),
		'ticket_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'field_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'value' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'indexes' => array(),
		'tableParameters' => array()
	);

	var $records = array(
		array(
			'id' => 1,
			'ticket_id' => 1,
			'created' => '2016-01-14 14:10:29',
			'field_id' => 1,
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
		),
	);
}
?>