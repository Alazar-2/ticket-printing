
		
<?php $coordinate_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('X', true) . ":</th><td><b>" . $coordinate['Coordinate']['x'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Y', true) . ":</th><td><b>" . $coordinate['Coordinate']['y'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Length', true) . ":</th><td><b>" . $coordinate['Coordinate']['length'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Order', true) . ":</th><td><b>" . $coordinate['Coordinate']['order'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Ticket', true) . ":</th><td><b>" . $coordinate['Ticket']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Field', true) . ":</th><td><b>" . $coordinate['Field']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Alignment', true) . ":</th><td><b>" . $coordinate['Coordinate']['alignment'] . "</b></td></tr>" . 
"</table>"; 
?>
		var coordinate_view_panel_1 = {
			html : '<?php echo $coordinate_html; ?>',
			frame : true,
			height: 80
		}
		var coordinate_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var CoordinateViewWindow = new Ext.Window({
			title: '<?php __('View Coordinate'); ?>: <?php echo $coordinate['Coordinate']['id']; ?>',
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
				coordinate_view_panel_1,
				coordinate_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					CoordinateViewWindow.close();
				}
			}]
		});
