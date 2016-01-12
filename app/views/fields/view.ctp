
var store_field_coordinates = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','x','y','length','order','slip','field'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'list_data', $field['Field']['id'])); ?>'	})
});
var store_field_records = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','print','field','value'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'list_data', $field['Field']['id'])); ?>'	})
});
		
<?php $field_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $field['Field']['name'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Label', true) . ":</th><td><b>" . $field['Field']['label'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Length', true) . ":</th><td><b>" . $field['Field']['length'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Type', true) . ":</th><td><b>" . $field['Field']['type'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Data', true) . ":</th><td><b>" . $field['Field']['data'] . "</b></td></tr>" . 
		"<tr><th align=right>" . __('Ticket', true) . ":</th><td><b>" . $field['Ticket']['name'] . "</b></td></tr>" . 
"</table>"; 
?>
		var field_view_panel_1 = {
			html : '<?php echo $field_html; ?>',
			frame : true,
			height: 80
		}
		var field_view_panel_2 = new Ext.TabPanel({
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
				store: store_field_coordinates,
				title: '<?php __('Coordinates'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_field_coordinates.getCount() == '')
							store_field_coordinates.reload();
					}
				},
				columns: [
					{header: "<?php __('X'); ?>", dataIndex: 'x', sortable: true}
,					{header: "<?php __('Y'); ?>", dataIndex: 'y', sortable: true}
,					{header: "<?php __('Length'); ?>", dataIndex: 'length', sortable: true}
,					{header: "<?php __('Order'); ?>", dataIndex: 'order', sortable: true}
,					{header: "<?php __('Slip'); ?>", dataIndex: 'slip', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_field_coordinates,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			},
{
				xtype: 'grid',
				loadMask: true,
				stripeRows: true,
				store: store_field_records,
				title: '<?php __('Records'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_field_records.getCount() == '')
							store_field_records.reload();
					}
				},
				columns: [
					{header: "<?php __('Print'); ?>", dataIndex: 'print', sortable: true}
,					{header: "<?php __('Value'); ?>", dataIndex: 'value', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_field_records,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var FieldViewWindow = new Ext.Window({
			title: '<?php __('View Field'); ?>: <?php echo $field['Field']['name']; ?>',
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
				field_view_panel_1,
				field_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					FieldViewWindow.close();
				}
			}]
		});
