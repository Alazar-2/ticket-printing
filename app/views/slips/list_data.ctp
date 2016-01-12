{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($slips as $slip){ if($st) echo ","; ?>			{
				"id":"<?php echo $slip['Slip']['id']; ?>",
				"url":"<?php echo $slip['Slip']['url']; ?>",
				"ticket":"<?php echo $slip['Ticket']['name']; ?>",
				"order":"<?php echo $slip['Slip']['order']; ?>"			}
<?php $st = true; } ?>		]
}