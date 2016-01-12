{
	success:true,
	results: <?php echo count($lines); ?>,
	rows: [
<?php 
	$st = false; 
	foreach($lines as $line){ if($st) 
		echo ","; 
		$date = substr($line, 0, 10);
		$time = substr($line, 11, 8);
		$content = str_replace("'", '&quote', substr($line, 20, strlen($line) - 21));
		$content = str_replace(DS, '/', $content);
?>			{
				"date":"<?php echo $date; ?>",
				"time":"<?php echo $time; ?>",
				"content":"<?php echo $content; ?>"		
			}
<?php $st = true; } ?>		
	]
}