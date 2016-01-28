var store_parent_issuesFieldsRecords = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'issueId','branchid','ticket','created','fieldId','label','type','value'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentIssuesFieldsRecord() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_issuesFieldsRecord_data = response.responseText;
			
			eval(parent_issuesFieldsRecord_data);
			
			IssuesFieldsRecordAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesFieldsRecord add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentIssuesFieldsRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_issuesFieldsRecord_data = response.responseText;
			
			eval(parent_issuesFieldsRecord_data);
			
			IssuesFieldsRecordEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesFieldsRecord edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewIssuesFieldsRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var issuesFieldsRecord_data = response.responseText;

			eval(issuesFieldsRecord_data);

			IssuesFieldsRecordViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesFieldsRecord view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentIssuesFieldsRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('IssuesFieldsRecord(s) successfully deleted!'); ?>');
			RefreshParentIssuesFieldsRecordData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesFieldsRecord to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentIssuesFieldsRecordName(value){
	var conditions = '\'IssuesFieldsRecord.name LIKE\' => \'%' + value + '%\'';
	store_parent_issuesFieldsRecords.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentIssuesFieldsRecordData() {
	store_parent_issuesFieldsRecords.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('IssuesFieldsRecords'); ?>',
	store: store_parent_issuesFieldsRecords,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'issuesFieldsRecordGrid',
	columns: [
		{header: "<?php __('IssueId'); ?>", dataIndex: 'issueId', sortable: true},
		{header: "<?php __('Branchid'); ?>", dataIndex: 'branchid', sortable: true},
		{header:"<?php __('ticket'); ?>", dataIndex: 'ticket', sortable: true},
		{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
		{header: "<?php __('FieldId'); ?>", dataIndex: 'fieldId', sortable: true},
		{header: "<?php __('Label'); ?>", dataIndex: 'label', sortable: true},
		{header: "<?php __('Type'); ?>", dataIndex: 'type', sortable: true},
		{header: "<?php __('Value'); ?>", dataIndex: 'value', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewIssuesFieldsRecord(Ext.getCmp('issuesFieldsRecordGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add IssuesFieldsRecord</b><br />Click here to create a new IssuesFieldsRecord'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentIssuesFieldsRecord();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-issuesFieldsRecord',
				tooltip:'<?php __('<b>Edit IssuesFieldsRecord</b><br />Click here to modify the selected IssuesFieldsRecord'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentIssuesFieldsRecord(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-issuesFieldsRecord',
				tooltip:'<?php __('<b>Delete IssuesFieldsRecord(s)</b><br />Click here to remove the selected IssuesFieldsRecord(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove IssuesFieldsRecord'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentIssuesFieldsRecord(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove IssuesFieldsRecord'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected IssuesFieldsRecord'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentIssuesFieldsRecord(sel_ids);
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
				text: '<?php __('View IssuesFieldsRecord'); ?>',
				id: 'view-issuesFieldsRecord2',
				tooltip:'<?php __('<b>View IssuesFieldsRecord</b><br />Click here to see details of the selected IssuesFieldsRecord'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewIssuesFieldsRecord(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_issuesFieldsRecord_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentIssuesFieldsRecordName(Ext.getCmp('parent_issuesFieldsRecord_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_issuesFieldsRecord_go_button',
				handler: function(){
					SearchByParentIssuesFieldsRecordName(Ext.getCmp('parent_issuesFieldsRecord_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_issuesFieldsRecords,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-issuesFieldsRecord').enable();
	g.getTopToolbar().findById('delete-parent-issuesFieldsRecord').enable();
        g.getTopToolbar().findById('view-issuesFieldsRecord2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-issuesFieldsRecord').disable();
                g.getTopToolbar().findById('view-issuesFieldsRecord2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-issuesFieldsRecord').disable();
		g.getTopToolbar().findById('delete-parent-issuesFieldsRecord').enable();
                g.getTopToolbar().findById('view-issuesFieldsRecord2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-issuesFieldsRecord').enable();
		g.getTopToolbar().findById('delete-parent-issuesFieldsRecord').enable();
                g.getTopToolbar().findById('view-issuesFieldsRecord2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-issuesFieldsRecord').disable();
		g.getTopToolbar().findById('delete-parent-issuesFieldsRecord').disable();
                g.getTopToolbar().findById('view-issuesFieldsRecord2').disable();
	}
});



var parentIssuesFieldsRecordsViewWindow = new Ext.Window({
	title: 'IssuesFieldsRecord Under the selected Item',
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
			parentIssuesFieldsRecordsViewWindow.close();
		}
	}]
});

store_parent_issuesFieldsRecords.load({
    params: {
        start: 0,    
        limit: list_size
    }
});