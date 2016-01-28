{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($issues_records as $issues_record){ if($st) echo ","; ?>			{
				"id":"<?php echo $issues_record['IssuesRecord']['id']; ?>",
				"ticket":"<?php echo $issues_record['Ticket']['name']; ?>",
				"created":"<?php echo $issues_record['IssuesRecord']['created']; ?>",
				"field":"<?php echo $issues_record['Field']['name']; ?>",
				"value":"<?php echo $issues_record['IssuesRecord']['value']; ?>"			}
<?php $st = true; } ?>		]
}