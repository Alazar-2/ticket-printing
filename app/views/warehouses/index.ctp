
var store_warehouses = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','string','format','start','end','current','status'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'warehouses', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'string'
});


function AddWarehouse() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'warehouses', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var warehouse_data = response.responseText;
			
			eval(warehouse_data);
			
			WarehouseAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the warehouse add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditWarehouse(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'warehouses', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var warehouse_data = response.responseText;
			
			eval(warehouse_data);
			
			WarehouseEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the warehouse edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewWarehouse(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'warehouses', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var warehouse_data = response.responseText;

            eval(warehouse_data);

            WarehouseViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the warehouse view form. Error code'); ?>: ' + response.status);
        }
    });
}

function DeleteWarehouse(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'warehouses', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Warehouse successfully deleted!'); ?>');
			RefreshWarehouseData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the warehouse add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchWarehouse(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'warehouses', 'action' => 'search')); ?>',
		success: function(response, opts){
			var warehouse_data = response.responseText;

			eval(warehouse_data);

			warehouseSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the warehouse search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByWarehouseName(value){
	var conditions = '\'Warehouse.name LIKE\' => \'%' + value + '%\'';
	store_warehouses.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshWarehouseData() {
	store_warehouses.reload();
}


if(center_panel.find('id', 'warehouse-tab') != "") {
	var p = center_panel.findById('warehouse-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Warehouses'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'warehouse-tab',
		xtype: 'grid',
		store: store_warehouses,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('String'); ?>", dataIndex: 'string', sortable: true},
			{header: "<?php __('Format'); ?>", dataIndex: 'format', sortable: true},
			{header: "<?php __('Start'); ?>", dataIndex: 'start', sortable: true},
			{header: "<?php __('End'); ?>", dataIndex: 'end', sortable: true},
			{header: "<?php __('Current'); ?>", dataIndex: 'current', sortable: true},
			{header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Warehouses" : "Warehouse"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewWarehouse(Ext.getCmp('warehouse-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Warehouses</b><br />Click here to create a new Warehouse'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddWarehouse();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-warehouse',
					tooltip:'<?php __('<b>Edit Warehouses</b><br />Click here to modify the selected Warehouse'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditWarehouse(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-warehouse',
					tooltip:'<?php __('<b>Delete Warehouses(s)</b><br />Click here to remove the selected Warehouse(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Warehouse'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteWarehouse(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Warehouse'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Warehouses'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteWarehouse(sel_ids);
										}
									}
								});
							}
						} else {
							Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbsplit',
					text: '<?php __('View Warehouse'); ?>',
					id: 'view-warehouse',
					tooltip:'<?php __('<b>View Warehouse</b><br />Click here to see details of the selected Warehouse'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewWarehouse(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'warehouse_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByWarehouseName(Ext.getCmp('warehouse_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'warehouse_go_button',
					handler: function(){
						SearchByWarehouseName(Ext.getCmp('warehouse_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchWarehouse();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_warehouses,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-warehouse').enable();
		p.getTopToolbar().findById('delete-warehouse').enable();
		p.getTopToolbar().findById('view-warehouse').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-warehouse').disable();
			p.getTopToolbar().findById('view-warehouse').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-warehouse').disable();
			p.getTopToolbar().findById('view-warehouse').disable();
			p.getTopToolbar().findById('delete-warehouse').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-warehouse').enable();
			p.getTopToolbar().findById('view-warehouse').enable();
			p.getTopToolbar().findById('delete-warehouse').enable();
		}
		else{
			p.getTopToolbar().findById('edit-warehouse').disable();
			p.getTopToolbar().findById('view-warehouse').disable();
			p.getTopToolbar().findById('delete-warehouse').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_warehouses.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
