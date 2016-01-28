
		
<?php $issuesRecord_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Ticket', true) . ":</th><td><b>" . $issuesRecord['Ticket']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $issuesRecord['IssuesRecord']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Field', true) . ":</th><td><b>" . $issuesRecord['Field']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Value', true) . ":</th><td><b>" . $issuesRecord['IssuesRecord']['value'] . "</b></td></tr>" . 
"</table>"; 
?>
		var issuesRecord_view_panel_1 = {
			html : '<?php echo $issuesRecord_html; ?>',
			frame : true,
			height: 80
		}
		var issuesRecord_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var IssuesRecordViewWindow = new Ext.Window({
			title: '<?php __('View IssuesRecord'); ?>: <?php echo $issuesRecord['IssuesRecord']['id']; ?>',
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
				issuesRecord_view_panel_1,
				issuesRecord_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					IssuesRecordViewWindow.close();
				}
			}]
		});
