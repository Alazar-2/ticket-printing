{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($fields as $field){ if($st) echo ","; ?>			{
				"id":"<?php echo $field['Field']['id']; ?>",
				"name":"<?php echo $field['Field']['name']; ?>",
				"label":"<?php echo $field['Field']['label']; ?>",
				"length":"<?php echo $field['Field']['length']; ?>",
				"type":"<?php echo $field['Field']['type']; ?>",
				"data":"<?php echo $field['Field']['data']; ?>",
				"ticket":"<?php echo $field['Ticket']['name']; ?>"			}
<?php $st = true; } ?>		]
}