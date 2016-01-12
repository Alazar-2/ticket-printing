
		
<?php $key_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('From Branch', true) . ":</th><td><b>" . $key['FromBranch']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('To Branch', true) . ":</th><td><b>" . $key['ToBranch']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Key', true) . ":</th><td><b>" . $key['Key']['key'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Tt Direction', true) . ":</th><td><b>" . $key['Key']['tt_direction'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Amount Range', true) . ":</th><td><b>" . $key['Key']['amount_range'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Created', true) . ":</th><td><b>" . $key['Key']['created'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Modified', true) . ":</th><td><b>" . $key['Key']['modified'] . "</b></td></tr>" . 
"</table>"; 
?>
		var key_view_panel_1 = {
			html : '<?php echo $key_html; ?>',
			frame : true,
			height: 80
		}
		var key_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
						]
		});

		var KeyViewWindow = new Ext.Window({
			title: '<?php __('View Key'); ?>: <?php echo $key['Key']['key']; ?>',
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
				key_view_panel_1,
				key_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					KeyViewWindow.close();
				}
			}]
		});
