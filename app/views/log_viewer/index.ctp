
var store_logs = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'date','time','content'		
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'log_viewer', 'action' => 'list_data')); ?>'
	}),	
    sortInfo:{field: 'date', direction: "DESC"}
});


if(center_panel.find('id', 'log-tab') != "") {
	var p = center_panel.findById('region-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Logs'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'region-tab',
		xtype: 'grid',
		store: store_logs,
		columns: [
			{header: "<?php __('Date'); ?>", dataIndex: 'date', sortable: true},
			{header: "<?php __('Time'); ?>", dataIndex: 'time', sortable: true},
			{header: "<?php __('Content'); ?>", dataIndex: 'content', sortable: true}
		],
		viewConfig: {
            forceFit:true
        },
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			items: []
		})
	});
	
	center_panel.setActiveTab(p);
	
	store_logs.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
