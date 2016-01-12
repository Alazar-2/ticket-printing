
var store_ticket_fields = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','label','length','type','data','ticket'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'list_data', $ticket['Ticket']['id'])); ?>'	})
});
var store_ticket_issues = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','ticket','user','status','created'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'list_data', $ticket['Ticket']['id'])); ?>'	})
});
var store_ticket_slips = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','url','ticket','order'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'list_data', $ticket['Ticket']['id'])); ?>'	})
});
		
<?php $ticket_html = "<table cellspacing=3>" . 		"<tr><th align=right>" . __('Name', true) . ":</th><td><b>" . $ticket['Ticket']['name'] . "</b></td></tr>" . 
"</table>"; 
?>
		var ticket_view_panel_1 = {
			html : '<?php echo $ticket_html; ?>',
			frame : true,
			height: 80
		}
		var ticket_view_panel_2 = new Ext.TabPanel({
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
				store: store_ticket_fields,
				title: '<?php __('Fields'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_ticket_fields.getCount() == '')
							store_ticket_fields.reload();
					}
				},
				columns: [
					{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true}
,					{header: "<?php __('Label'); ?>", dataIndex: 'label', sortable: true}
,					{header: "<?php __('Length'); ?>", dataIndex: 'length', sortable: true}
,					{header: "<?php __('Type'); ?>", dataIndex: 'type', sortable: true}
,					{header: "<?php __('Data'); ?>", dataIndex: 'data', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_ticket_fields,
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
				store: store_ticket_issues,
				title: '<?php __('Issues'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_ticket_issues.getCount() == '')
							store_ticket_issues.reload();
					}
				},
				columns: [
					{header: "<?php __('User'); ?>", dataIndex: 'user', sortable: true}
,					{header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true}
,					{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_ticket_issues,
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
				store: store_ticket_slips,
				title: '<?php __('Slips'); ?>',
				enableColumnMove: false,
				listeners: {
					activate: function(){
						if(store_ticket_slips.getCount() == '')
							store_ticket_slips.reload();
					}
				},
				columns: [
					{header: "<?php __('Url'); ?>", dataIndex: 'url', sortable: true}
,					{header: "<?php __('Order'); ?>", dataIndex: 'order', sortable: true}
		
				],
				viewConfig: {
					forceFit: true
				},
				bbar: new Ext.PagingToolbar({
					pageSize: view_list_size,
					store: store_ticket_slips,
					displayInfo: true,
					displayMsg: '<?php __('Displaying'); ?> {0} - {1} <?php __('of'); ?> {2}',
					beforePageText: '<?php __('Page'); ?>',
					afterPageText: '<?php __('of'); ?> {0}',
					emptyMsg: '<?php __('No data to display'); ?>'
				})
			}			]
		});

		var TicketViewWindow = new Ext.Window({
			title: '<?php __('View Ticket'); ?>: <?php echo $ticket['Ticket']['name']; ?>',
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
				ticket_view_panel_1,
				ticket_view_panel_2
			],

			buttons: [{
				text: '<?php __('Close'); ?>',
				handler: function(btn){
					TicketViewWindow.close();
				}
			}]
		});
