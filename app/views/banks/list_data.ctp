{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($banks as $bank){ if($st) echo ","; ?>			{
				"id":"<?php echo $bank['Bank']['id']; ?>",
				"name":"<?php echo $bank['Bank']['name']; ?>",
				"ats_code":"<?php echo $bank['Bank']['ats_code']; ?>",
				"BIC":"<?php echo $bank['Bank']['BIC']; ?>",
				"created":"<?php echo $bank['Bank']['created']; ?>",
				"modified":"<?php echo $bank['Bank']['modified']; ?>"			}
<?php $st = true; } ?>		]
}