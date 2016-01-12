var store_parent_fields = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','label','length','type','data','ticket'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentField() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_field_data = response.responseText;
			
			eval(parent_field_data);
			
			FieldAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the field add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentField(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_field_data = response.responseText;
			
			eval(parent_field_data);
			
			FieldEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the field edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewField(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var field_data = response.responseText;

			eval(field_data);

			FieldViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the field view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewFieldCoordinates(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'coordinates', 'action' => 'index2')); ?>/'+id,
		success: function(response, opts) {
			var parent_coordinates_data = response.responseText;

			eval(parent_coordinates_data);

			parentCoordinatesViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewFieldRecords(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'index2')); ?>/'+id,
		success: function(response, opts) {
			var parent_records_data = response.responseText;

			eval(parent_records_data);

			parentRecordsViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the campus view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentField(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Field(s) successfully deleted!'); ?>');
			RefreshParentFieldData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the field to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentFieldName(value){
	var conditions = '\'Field.name LIKE\' => \'%' + value + '%\'';
	store_parent_fields.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentFieldData() {
	store_parent_fields.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Fields'); ?>',
	store: store_parent_fields,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'fieldGrid',
	columns: [
                {header: "<?php __('ID'); ?>", dataIndex: 'id', sortable: true},
		{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
		{header: "<?php __('Label'); ?>", dataIndex: 'label', sortable: true},
		{header: "<?php __('Length'); ?>", dataIndex: 'length', sortable: true},
		{header: "<?php __('Type'); ?>", dataIndex: 'type', sortable: true},
		{header: "<?php __('Data'); ?>", dataIndex: 'data', sortable: true},
		{header:"<?php __('ticket'); ?>", dataIndex: 'ticket', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewField(Ext.getCmp('fieldGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Field</b><br />Click here to create a new Field'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentField();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-field',
				tooltip:'<?php __('<b>Edit Field</b><br />Click here to modify the selected Field'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentField(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-field',
				tooltip:'<?php __('<b>Delete Field(s)</b><br />Click here to remove the selected Field(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Field'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentField(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove Field'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected Field'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentField(sel_ids);
											}
									}
							});
						}
					} else {
						Ext.Msg.alert('<?php __('Warning'); ?>', '<?php __('Please select a record first'); ?>');
					};
				}
			}, ' ','-',' ', {
				xtype: 'tbsplit',
				text: '<?php __('View Field'); ?>',
				id: 'view-field2',
				tooltip:'<?php __('<b>View Field</b><br />Click here to see details of the selected Field'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewField(sel.data.id);
					};
				},
				menu : {
					items: [
 {
						text: '<?php __('View Coordinates'); ?>',
                        icon: 'img/table_view.png',
						cls: 'x-btn-text-icon',
						handler: function(btn) {
							var sm = g.getSelectionModel();
							var sel = sm.getSelected();
							if (sm.hasSelection()){
								ViewFieldCoordinates(sel.data.id);
							};
						}
					}
, {
						text: '<?php __('View Records'); ?>',
                        icon: 'img/table_view.png',
						cls: 'x-btn-text-icon',
						handler: function(btn) {
							var sm = g.getSelectionModel();
							var sel = sm.getSelected();
							if (sm.hasSelection()){
								ViewFieldRecords(sel.data.id);
							};
						}
					}
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_field_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentFieldName(Ext.getCmp('parent_field_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_field_go_button',
				handler: function(){
					SearchByParentFieldName(Ext.getCmp('parent_field_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_fields,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-field').enable();
	g.getTopToolbar().findById('delete-parent-field').enable();
        g.getTopToolbar().findById('view-field2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-field').disable();
                g.getTopToolbar().findById('view-field2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-field').disable();
		g.getTopToolbar().findById('delete-parent-field').enable();
                g.getTopToolbar().findById('view-field2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-field').enable();
		g.getTopToolbar().findById('delete-parent-field').enable();
                g.getTopToolbar().findById('view-field2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-field').disable();
		g.getTopToolbar().findById('delete-parent-field').disable();
                g.getTopToolbar().findById('view-field2').disable();
	}
});



var parentFieldsViewWindow = new Ext.Window({
	title: 'Field Under the selected Item',
	width: 700,
	height:375,
	minWidth: 700,
	minHeight: 400,
	resizable: false,
	plain:true,
	bodyStyle:'padding:5px;',
	buttonAlign:'center',
        modal: true,
	items: [
		g
	],

	buttons: [{
		text: 'Close',
		handler: function(btn){
			parentFieldsViewWindow.close();
		}
	}]
});

store_parent_fields.load({
    params: {
        start: 0,    
        limit: list_size
    }
});