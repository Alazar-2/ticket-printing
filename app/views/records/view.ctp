
		
<?php $record_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Issue', true) . ":</th><td><b>" . $record['Issue']['id'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Field', true) . ":</th><td><b>" . $record['Field']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Value', true) . ":</th><td><b>" . $record['Record']['value'] . "</b></td></tr>" . 
"</table>"; 
?>
		var record_view_panel_1 = {
			html : '<?php echo $record_html; ?>',
			frame : true,
			height: 80
		}
		var record_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var RecordViewWindow = new Ext.Window({
			title: '<?php __('View Record'); ?>: <?php echo $record['Record']['id']; ?>',
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
				record_view_panel_1,
				record_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					RecordViewWindow.close();
				}
			}]
		});
