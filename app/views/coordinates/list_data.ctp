{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($coordinates as $coordinate){ if($st) echo ","; ?>			{
				"id":"<?php echo $coordinate['Coordinate']['id']; ?>",
				"x":"<?php echo $coordinate['Coordinate']['x']; ?>",
				"y":"<?php echo $coordinate['Coordinate']['y']; ?>",
				"length":"<?php echo $coordinate['Coordinate']['length']; ?>",
				"order":"<?php echo $coordinate['Coordinate']['order']; ?>",
				"ticket":"<?php echo $coordinate['Ticket']['name']; ?>",
				"field":"<?php echo $coordinate['Field']['name']; ?>",
				"alignment":"<?php echo $coordinate['Coordinate']['alignment']; ?>"			}
<?php $st = true; } ?>		]
}