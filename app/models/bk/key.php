<?php
class Key extends AppModel {
	var $name = 'Key';
	//The Associations below have been created with all possible keys, those that are not needed can be removed

	var $belongsTo = array(
		'FromBranch' => array(
			'className' => 'Branch',
			'foreignKey' => 'from_branch_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		),
		'ToBranch' => array(
			'className' => 'Branch',
			'foreignKey' => 'to_branch_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);
}
?>