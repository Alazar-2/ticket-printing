{
	success:true,
	results: <?php echo $results; ?>,
	rows: [
<?php $st = false; foreach($issues_fields_records as $issues_fields_record){ if($st) echo ","; ?>			{
				"issueId":"<?php echo $issues_fields_record['IssuesFieldsRecord']['issueId']; ?>",
				"branchid":"<?php echo $issues_fields_record['IssuesFieldsRecord']['branchid']; ?>",
				"ticket":"<?php echo $issues_fields_record['Ticket']['name']; ?>",
				"created":"<?php echo $issues_fields_record['IssuesFieldsRecord']['created']; ?>",
				"fieldId":"<?php echo $issues_fields_record['IssuesFieldsRecord']['fieldId']; ?>",
				"label":"<?php echo $issues_fields_record['IssuesFieldsRecord']['label']; ?>",
				"type":"<?php echo $issues_fields_record['IssuesFieldsRecord']['type']; ?>",
				"value":"<?php echo $issues_fields_record['IssuesFieldsRecord']['value']; ?>"			}
<?php $st = true; } ?>		]
}