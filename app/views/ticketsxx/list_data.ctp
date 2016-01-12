{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($tickets as $ticket){ if($st) echo ","; ?>			{
				"id":"<?php echo $ticket['Ticket']['id']; ?>",
				"name":"<?php echo $ticket['Ticket']['name']; ?>"			}
<?php $st = true; } ?>		]
}