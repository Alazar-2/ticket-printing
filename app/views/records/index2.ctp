var store_parent_records = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','issue','field','value'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentRecord() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_record_data = response.responseText;
			
			eval(parent_record_data);
			
			RecordAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the record add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_record_data = response.responseText;
			
			eval(parent_record_data);
			
			RecordEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the record edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var record_data = response.responseText;

			eval(record_data);

			RecordViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the record view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Record(s) successfully deleted!'); ?>');
			RefreshParentRecordData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the record to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentRecordName(value){
	var conditions = '\'Record.name LIKE\' => \'%' + value + '%\'';
	store_parent_records.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentRecordData() {
	store_parent_records.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Records'); ?>',
	store: store_parent_records,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'recordGrid',
	columns: [
		{header:"<?php __('issue'); ?>", dataIndex: 'issue', sortable: true},
		{header:"<?php __('field'); ?>", dataIndex: 'field', sortable: true},
		{header: "<?php __('Value'); ?>", dataIndex: 'value', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewRecord(Ext.getCmp('recordGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Record</b><br />Click here to create a new Record'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentRecord();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-record',
				tooltip:'<?php __('<b>Edit Record</b><br />Click here to modify the selected Record'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentRecord(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-record',
				tooltip:'<?php __('<b>Delete Record(s)</b><br />Click here to remove the selected Record(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Record'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentRecord(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove Record'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected Record'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentRecord(sel_ids);
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
				text: '<?php __('View Record'); ?>',
				id: 'view-record2',
				tooltip:'<?php __('<b>View Record</b><br />Click here to see details of the selected Record'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewRecord(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_record_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentRecordName(Ext.getCmp('parent_record_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_record_go_button',
				handler: function(){
					SearchByParentRecordName(Ext.getCmp('parent_record_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_records,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-record').enable();
	g.getTopToolbar().findById('delete-parent-record').enable();
        g.getTopToolbar().findById('view-record2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-record').disable();
                g.getTopToolbar().findById('view-record2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-record').disable();
		g.getTopToolbar().findById('delete-parent-record').enable();
                g.getTopToolbar().findById('view-record2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-record').enable();
		g.getTopToolbar().findById('delete-parent-record').enable();
                g.getTopToolbar().findById('view-record2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-record').disable();
		g.getTopToolbar().findById('delete-parent-record').disable();
                g.getTopToolbar().findById('view-record2').disable();
	}
});



var parentRecordsViewWindow = new Ext.Window({
	title: 'Record Under the selected Item',
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
			parentRecordsViewWindow.close();
		}
	}]
});

store_parent_records.load({
    params: {
        start: 0,    
        limit: list_size
    }
});