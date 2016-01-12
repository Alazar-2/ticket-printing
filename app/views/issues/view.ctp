
		
<?php $issue_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Ticket', true) . ":</th><td><b>" . $issue['Ticket']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('User', true) . ":</th><td><b>" . $issue['User']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Status', true) . ":</th><td><b>" . $issue['Issue']['status'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $issue['Issue']['created'] . "</b></td></tr>" . 
"</table>"; 
?>
		var issue_view_panel_1 = {
			html : '<?php echo $issue_html; ?>',
			frame : true,
			height: 80
		}
		var issue_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var IssueViewWindow = new Ext.Window({
			title: '<?php __('View Issue'); ?>: <?php echo $issue['Issue']['id']; ?>',
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
				issue_view_panel_1,
				issue_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					IssueViewWindow.close();
				}
			}]
		});
