
		
<?php $warehouse_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $warehouse['Warehouse']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('String', true) . ":</th><td><b>" . $warehouse['Warehouse']['string'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Format', true) . ":</th><td><b>" . $warehouse['Warehouse']['format'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Start', true) . ":</th><td><b>" . $warehouse['Warehouse']['start'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('End', true) . ":</th><td><b>" . $warehouse['Warehouse']['end'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Current', true) . ":</th><td><b>" . $warehouse['Warehouse']['current'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Status', true) . ":</th><td><b>" . $warehouse['Warehouse']['status'] . "</b></td></tr>" . 
"</table>"; 
?>
		var warehouse_view_panel_1 = {
			html : '<?php echo $warehouse_html; ?>',
			frame : true,
			height: 80
		}
		var warehouse_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var WarehouseViewWindow = new Ext.Window({
			title: '<?php __('View Warehouse'); ?>: <?php echo $warehouse['Warehouse']['name']; ?>',
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
				warehouse_view_panel_1,
				warehouse_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					WarehouseViewWindow.close();
				}
			}]
		});
