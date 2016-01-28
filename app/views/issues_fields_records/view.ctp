
		
<?php $issuesFieldsRecord_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('IssueId', true) . ":</th><td><b>" . $issuesFieldsRecord['IssuesFieldsRecord']['issueId'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Branchid', true) . ":</th><td><b>" . $issuesFieldsRecord['IssuesFieldsRecord']['branchid'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Ticket', true) . ":</th><td><b>" . $issuesFieldsRecord['Ticket']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $issuesFieldsRecord['IssuesFieldsRecord']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('FieldId', true) . ":</th><td><b>" . $issuesFieldsRecord['IssuesFieldsRecord']['fieldId'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Label', true) . ":</th><td><b>" . $issuesFieldsRecord['IssuesFieldsRecord']['label'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Type', true) . ":</th><td><b>" . $issuesFieldsRecord['IssuesFieldsRecord']['type'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Value', true) . ":</th><td><b>" . $issuesFieldsRecord['IssuesFieldsRecord']['value'] . "</b></td></tr>" . 
"</table>"; 
?>
		var issuesFieldsRecord_view_panel_1 = {
			html : '<?php echo $issuesFieldsRecord_html; ?>',
			frame : true,
			height: 80
		}
		var issuesFieldsRecord_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var IssuesFieldsRecordViewWindow = new Ext.Window({
			title: '<?php __('View IssuesFieldsRecord'); ?>: <?php echo $issuesFieldsRecord['IssuesFieldsRecord']['']; ?>',
			width: 500,
			height:345,
			minWidth: 500,
			minHeight: 345,
			resizable: false,
			plain:true,
			bodyStyle:'padding:5px;',
			buttonAlign:'center',
                        modal: true,
			items: [ 
				issuesFieldsRecord_view_panel_1,
				issuesFieldsRecord_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					IssuesFieldsRecordViewWindow.close();
				}
			}]
		});
