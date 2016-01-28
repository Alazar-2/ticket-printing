<?php
/* IssuesFieldsRecord Fixture generated on: 2016-01-21 13:01:17 : 1453382297 */
class IssuesFieldsRecordFixture extends CakeTestFixture {
	var $name = 'IssuesFieldsRecord';

	var $fields = array(
		'issueId' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'branchid' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'ticket_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL),
		'fieldId' => array('type' => 'integer', 'null' => false, 'default' => '0'),
		'label' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 100),
		'value' => array('type' => 'text', 'null' => false, 'default' => NULL),
		'indexes' => array(),
		'tableParameters' => array()
	);

	var $records = array(
		array(
			'issueId' => 1,
			'branchid' => 1,
			'ticket_id' => 1,
			'created' => '2016-01-21 13:18:17',
			'fieldId' => 1,
			'label' => 'Lorem ipsum dolor sit amet',
			'type' => 'Lorem ipsum dolor sit amet',
			'value' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.'
		),
	);
}
?>