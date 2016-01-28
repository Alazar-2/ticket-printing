
var store_issuesFieldsRecords = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'issueId','branchid','ticket','created','fieldId','label','type','value'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'branchid', direction: "ASC"},
	groupField: 'ticket_id'
});


function AddIssuesFieldsRecord() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var issuesFieldsRecord_data = response.responseText;
			
			eval(issuesFieldsRecord_data);
			
			IssuesFieldsRecordAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesFieldsRecord add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditIssuesFieldsRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var issuesFieldsRecord_data = response.responseText;
			
			eval(issuesFieldsRecord_data);
			
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

function DeleteIssuesFieldsRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('IssuesFieldsRecord successfully deleted!'); ?>');
			RefreshIssuesFieldsRecordData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesFieldsRecord add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchIssuesFieldsRecord(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesFieldsRecords', 'action' => 'search')); ?>',
		success: function(response, opts){
			var issuesFieldsRecord_data = response.responseText;

			eval(issuesFieldsRecord_data);

			issuesFieldsRecordSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the issuesFieldsRecord search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByIssuesFieldsRecordName(value){
	var conditions = '\'IssuesFieldsRecord.name LIKE\' => \'%' + value + '%\'';
	store_issuesFieldsRecords.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshIssuesFieldsRecordData() {
	store_issuesFieldsRecords.reload();
}


if(center_panel.find('id', 'issuesFieldsRecord-tab') != "") {
	var p = center_panel.findById('issuesFieldsRecord-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Issues Fields Records'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'issuesFieldsRecord-tab',
		xtype: 'grid',
		store: store_issuesFieldsRecords,
		columns: [
			{header: "<?php __('IssueId'); ?>", dataIndex: 'issueId', sortable: true},
			{header: "<?php __('Branchid'); ?>", dataIndex: 'branchid', sortable: true},
			{header: "<?php __('Ticket'); ?>", dataIndex: 'ticket', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('FieldId'); ?>", dataIndex: 'fieldId', sortable: true},
			{header: "<?php __('Label'); ?>", dataIndex: 'label', sortable: true},
			{header: "<?php __('Type'); ?>", dataIndex: 'type', sortable: true},
			{header: "<?php __('Value'); ?>", dataIndex: 'value', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "IssuesFieldsRecords" : "IssuesFieldsRecord"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewIssuesFieldsRecord(Ext.getCmp('issuesFieldsRecord-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add IssuesFieldsRecords</b><br />Click here to create a new IssuesFieldsRecord'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddIssuesFieldsRecord();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-issuesFieldsRecord',
					tooltip:'<?php __('<b>Edit IssuesFieldsRecords</b><br />Click here to modify the selected IssuesFieldsRecord'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditIssuesFieldsRecord(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-issuesFieldsRecord',
					tooltip:'<?php __('<b>Delete IssuesFieldsRecords(s)</b><br />Click here to remove the selected IssuesFieldsRecord(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove IssuesFieldsRecord'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteIssuesFieldsRecord(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove IssuesFieldsRecord'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected IssuesFieldsRecords'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteIssuesFieldsRecord(sel_ids);
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
					text: '<?php __('View IssuesFieldsRecord'); ?>',
					id: 'view-issuesFieldsRecord',
					tooltip:'<?php __('<b>View IssuesFieldsRecord</b><br />Click here to see details of the selected IssuesFieldsRecord'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewIssuesFieldsRecord(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('Ticket'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($tickets as $item){if($st) echo ",
							";?>['<?php echo $item['Ticket']['id']; ?>' ,'<?php echo $item['Ticket']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_issuesFieldsRecords.reload({
								params: {
									start: 0,
									limit: list_size,
									ticket_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'issuesFieldsRecord_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByIssuesFieldsRecordName(Ext.getCmp('issuesFieldsRecord_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'issuesFieldsRecord_go_button',
					handler: function(){
						SearchByIssuesFieldsRecordName(Ext.getCmp('issuesFieldsRecord_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchIssuesFieldsRecord();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_issuesFieldsRecords,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-issuesFieldsRecord').enable();
		p.getTopToolbar().findById('delete-issuesFieldsRecord').enable();
		p.getTopToolbar().findById('view-issuesFieldsRecord').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-issuesFieldsRecord').disable();
			p.getTopToolbar().findById('view-issuesFieldsRecord').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-issuesFieldsRecord').disable();
			p.getTopToolbar().findById('view-issuesFieldsRecord').disable();
			p.getTopToolbar().findById('delete-issuesFieldsRecord').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-issuesFieldsRecord').enable();
			p.getTopToolbar().findById('view-issuesFieldsRecord').enable();
			p.getTopToolbar().findById('delete-issuesFieldsRecord').enable();
		}
		else{
			p.getTopToolbar().findById('edit-issuesFieldsRecord').disable();
			p.getTopToolbar().findById('view-issuesFieldsRecord').disable();
			p.getTopToolbar().findById('delete-issuesFieldsRecord').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_issuesFieldsRecords.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
