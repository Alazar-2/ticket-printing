var store_parent_issuesRecords = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','ticket','created','field','value'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesRecords', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentIssuesRecord() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesRecords', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_issuesRecord_data = response.responseText;
			
			eval(parent_issuesRecord_data);
			
			IssuesRecordAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesRecord add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentIssuesRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesRecords', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_issuesRecord_data = response.responseText;
			
			eval(parent_issuesRecord_data);
			
			IssuesRecordEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesRecord edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewIssuesRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesRecords', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var issuesRecord_data = response.responseText;

			eval(issuesRecord_data);

			IssuesRecordViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesRecord view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentIssuesRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesRecords', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('IssuesRecord(s) successfully deleted!'); ?>');
			RefreshParentIssuesRecordData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesRecord to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentIssuesRecordName(value){
	var conditions = '\'IssuesRecord.name LIKE\' => \'%' + value + '%\'';
	store_parent_issuesRecords.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentIssuesRecordData() {
	store_parent_issuesRecords.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('IssuesRecords'); ?>',
	store: store_parent_issuesRecords,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'issuesRecordGrid',
	columns: [
		{header:"<?php __('ticket'); ?>", dataIndex: 'ticket', sortable: true},
		{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
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
            ViewIssuesRecord(Ext.getCmp('issuesRecordGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add IssuesRecord</b><br />Click here to create a new IssuesRecord'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentIssuesRecord();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-issuesRecord',
				tooltip:'<?php __('<b>Edit IssuesRecord</b><br />Click here to modify the selected IssuesRecord'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentIssuesRecord(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-issuesRecord',
				tooltip:'<?php __('<b>Delete IssuesRecord(s)</b><br />Click here to remove the selected IssuesRecord(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove IssuesRecord'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentIssuesRecord(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove IssuesRecord'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected IssuesRecord'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentIssuesRecord(sel_ids);
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
				text: '<?php __('View IssuesRecord'); ?>',
				id: 'view-issuesRecord2',
				tooltip:'<?php __('<b>View IssuesRecord</b><br />Click here to see details of the selected IssuesRecord'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewIssuesRecord(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_issuesRecord_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentIssuesRecordName(Ext.getCmp('parent_issuesRecord_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_issuesRecord_go_button',
				handler: function(){
					SearchByParentIssuesRecordName(Ext.getCmp('parent_issuesRecord_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_issuesRecords,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-issuesRecord').enable();
	g.getTopToolbar().findById('delete-parent-issuesRecord').enable();
        g.getTopToolbar().findById('view-issuesRecord2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-issuesRecord').disable();
                g.getTopToolbar().findById('view-issuesRecord2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-issuesRecord').disable();
		g.getTopToolbar().findById('delete-parent-issuesRecord').enable();
                g.getTopToolbar().findById('view-issuesRecord2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-issuesRecord').enable();
		g.getTopToolbar().findById('delete-parent-issuesRecord').enable();
                g.getTopToolbar().findById('view-issuesRecord2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-issuesRecord').disable();
		g.getTopToolbar().findById('delete-parent-issuesRecord').disable();
                g.getTopToolbar().findById('view-issuesRecord2').disable();
	}
});



var parentIssuesRecordsViewWindow = new Ext.Window({
	title: 'IssuesRecord Under the selected Item',
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
			parentIssuesRecordsViewWindow.close();
		}
	}]
});

store_parent_issuesRecords.load({
    params: {
        start: 0,    
        limit: list_size
    }
});