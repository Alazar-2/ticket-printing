{
	success:true,
	results: <?php  echo $results; ?>,
	rows: [
<?php $st = false; foreach($issues as $issue){ if($st) echo ","; ?>			{
				"id":"<?php echo $issue['Issue']['id']; ?>",
				"ticket":"<?php echo $issue['Ticket']['name']; ?>",
				"user":"<?php echo $issue['User']['username']; ?>",
				"status":"<?php echo $issue['Issue']['status']; ?>",
				"created":"<?php echo $issue['Issue']['created']; ?>"			}
<?php $st = true; } ?>		]
}