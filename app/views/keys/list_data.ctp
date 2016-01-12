{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($keys as $key){ if($st) echo ","; ?>			{
				"id":"<?php echo $key['Key']['id']; ?>",
				"from_branch":"<?php echo $key['FromBranch']['name']; ?>",
				"to_branch":"<?php echo $key['ToBranch']['name']; ?>",
				"key":"<?php echo $key['Key']['key']; ?>",
				"amount_range":"<?php echo $key['Key']['amount_range']; ?>"			}
<?php $st = true; } ?>		]
}