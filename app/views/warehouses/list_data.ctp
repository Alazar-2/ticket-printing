{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($warehouses as $warehouse){ if($st) echo ","; ?>			{
				"id":"<?php echo $warehouse['Warehouse']['id']; ?>",
				"name":"<?php echo $warehouse['Warehouse']['name']; ?>",
				"string":"<?php echo $warehouse['Warehouse']['string']; ?>",
				"format":"<?php echo $warehouse['Warehouse']['format']; ?>",
				"start":"<?php echo $warehouse['Warehouse']['start']; ?>",
				"end":"<?php echo $warehouse['Warehouse']['end']; ?>",
				"current":"<?php echo $warehouse['Warehouse']['current']; ?>",
				"status":"<?php echo $warehouse['Warehouse']['status']; ?>"			}
<?php $st = true; } ?>		]
}