var store_parent_issues = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','ticket','user','status','created'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentIssue() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_issue_data = response.responseText;
			
			eval(parent_issue_data);
			
			IssueAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issue add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentIssue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_issue_data = response.responseText;
			
			eval(parent_issue_data);
			
			IssueEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issue edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewIssue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'view')); ?>/'+id,
		success: function(response, opts) {
			var issue_data = response.responseText;

			eval(issue_data);

			IssueViewWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issue view form. Error code'); ?>: ' + response.status);
		}
	});
}


function DeleteParentIssue(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issues', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Issue(s) successfully deleted!'); ?>');
			RefreshParentIssueData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issue to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentIssueName(value){
	var conditions = '\'Issue.name LIKE\' => \'%' + value + '%\'';
	store_parent_issues.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentIssueData() {
	store_parent_issues.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Issues'); ?>',
	store: store_parent_issues,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'issueGrid',
	columns: [
		{header:"<?php __('ticket'); ?>", dataIndex: 'ticket', sortable: true},
		{header:"<?php __('user'); ?>", dataIndex: 'user', sortable: true},
		{header: "<?php __('Status'); ?>", dataIndex: 'status', sortable: true},
		{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewIssue(Ext.getCmp('issueGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Add'); ?>',
				tooltip:'<?php __('<b>Add Issue</b><br />Click here to create a new Issue'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentIssue();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-issue',
				tooltip:'<?php __('<b>Edit Issue</b><br />Click here to modify the selected Issue'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentIssue(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-issue',
				tooltip:'<?php __('<b>Delete Issue(s)</b><br />Click here to remove the selected Issue(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Issue'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentIssue(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove Issue'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected Issue'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentIssue(sel_ids);
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
				text: '<?php __('View Issue'); ?>',
				id: 'view-issue2',
				tooltip:'<?php __('<b>View Issue</b><br />Click here to see details of the selected Issue'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						ViewIssue(sel.data.id);
					};
				},
				menu : {
					items: [
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_issue_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentIssueName(Ext.getCmp('parent_issue_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_issue_go_button',
				handler: function(){
					SearchByParentIssueName(Ext.getCmp('parent_issue_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_issues,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-issue').enable();
	g.getTopToolbar().findById('delete-parent-issue').enable();
        g.getTopToolbar().findById('view-issue2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-issue').disable();
                g.getTopToolbar().findById('view-issue2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-issue').disable();
		g.getTopToolbar().findById('delete-parent-issue').enable();
                g.getTopToolbar().findById('view-issue2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-issue').enable();
		g.getTopToolbar().findById('delete-parent-issue').enable();
                g.getTopToolbar().findById('view-issue2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-issue').disable();
		g.getTopToolbar().findById('delete-parent-issue').disable();
                g.getTopToolbar().findById('view-issue2').disable();
	}
});



var parentIssuesViewWindow = new Ext.Window({
	title: 'Issue Under the selected Item',
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
			parentIssuesViewWindow.close();
		}
	}]
});

store_parent_issues.load({
    params: {
        start: 0,    
        limit: list_size
    }
});