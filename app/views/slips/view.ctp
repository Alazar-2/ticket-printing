
var store_slip_coordinates = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','x','y','length','order','slip','field'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'list_data', $slip['Slip']['id'])); ?>'	})
});
		
<?php $slip_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Url', true) . ":</th><td><b>" . $slip['Slip']['url'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Ticket', true) . ":</th><td><b>" . $slip['Ticket']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Order', true) . ":</th><td><b>" . $slip['Slip']['order'] . "</b></td></tr>" . 
"</table>"; 
?>
		var slip_view_panel_1 = {
			html : '<?php echo $slip_html; ?>',
			frame : true,
			height: 80
		}
		var slip_view_panel_2 = new Ext.TabPanel({
			activeTab: 0,
			anchor: '100%',
			height:190,
			plain:true,
			defaults:{autoScroll: true},
			items:[
			{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_slip_coordinates,
				title: '<?php __('Coordinates'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_slip_coordinates.getCount() == '')
							store_slip_coordinates.reload();
					}
				},
				columns: [
					{header: "<?php __('X'); ?>", dataIndex: 'x', sortable: true}
,					{header: "<?php __('Y'); ?>", dataIndex: 'y', sortable: true}
,					{header: "<?php __('Length'); ?>", dataIndex: 'length', sortable: true}
,					{header: "<?php __('Order'); ?>", dataIndex: 'order', sortable: true}
,					{header: "<?php __('Field'); ?>", dataIndex: 'field', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_slip_coordinates,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var SlipViewWindow = new Ext.Window({
			title: '<?php __('View Slip'); ?>: <?php echo $slip['Slip']['id']; ?>',
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
				slip_view_panel_1,
				slip_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					SlipViewWindow.close();
				}
			}]
		});
