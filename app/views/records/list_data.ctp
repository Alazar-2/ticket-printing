{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($records as $record){ if($st) echo ","; ?>			{
				"id":"<?php echo $record['Record']['id']; ?>",
				"issue":"<?php echo $record['Issue']['id']; ?>",
				"field":"<?php echo $record['Field']['name']; ?>",
				"value":"<?php echo $record['Record']['value']; ?>"			}
<?php $st = true; } ?>		]
}