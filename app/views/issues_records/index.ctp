
var store_issuesRecords = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','ticket','created','field','value'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesRecords', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'ticket_id', direction: "ASC"},
	groupField: 'created'
});


function AddIssuesRecord() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesRecords', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var issuesRecord_data = response.responseText;
			
			eval(issuesRecord_data);
			
			IssuesRecordAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesRecord add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditIssuesRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesRecords', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var issuesRecord_data = response.responseText;
			
			eval(issuesRecord_data);
			
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

function DeleteIssuesRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesRecords', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('IssuesRecord successfully deleted!'); ?>');
			RefreshIssuesRecordData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the issuesRecord add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchIssuesRecord(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'issuesRecords', 'action' => 'search')); ?>',
		success: function(response, opts){
			var issuesRecord_data = response.responseText;

			eval(issuesRecord_data);

			issuesRecordSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the issuesRecord search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByIssuesRecordName(value){
	var conditions = '\'IssuesRecord.name LIKE\' => \'%' + value + '%\'';
	store_issuesRecords.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshIssuesRecordData() {
	store_issuesRecords.reload();
}


if(center_panel.find('id', 'issuesRecord-tab') != "") {
	var p = center_panel.findById('issuesRecord-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Issues Records'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'issuesRecord-tab',
		xtype: 'grid',
		store: store_issuesRecords,
		columns: [
			{header: "<?php __('Ticket'); ?>", dataIndex: 'ticket', sortable: true},
			{header: "<?php __('Created'); ?>", dataIndex: 'created', sortable: true},
			{header: "<?php __('Field'); ?>", dataIndex: 'field', sortable: true},
			{header: "<?php __('Value'); ?>", dataIndex: 'value', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "IssuesRecords" : "IssuesRecord"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewIssuesRecord(Ext.getCmp('issuesRecord-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add IssuesRecords</b><br />Click here to create a new IssuesRecord'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddIssuesRecord();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-issuesRecord',
					tooltip:'<?php __('<b>Edit IssuesRecords</b><br />Click here to modify the selected IssuesRecord'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditIssuesRecord(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-issuesRecord',
					tooltip:'<?php __('<b>Delete IssuesRecords(s)</b><br />Click here to remove the selected IssuesRecord(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove IssuesRecord'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteIssuesRecord(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove IssuesRecord'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected IssuesRecords'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteIssuesRecord(sel_ids);
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
					text: '<?php __('View IssuesRecord'); ?>',
					id: 'view-issuesRecord',
					tooltip:'<?php __('<b>View IssuesRecord</b><br />Click here to see details of the selected IssuesRecord'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewIssuesRecord(sel.data.id);
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
							store_issuesRecords.reload({
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
					id: 'issuesRecord_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByIssuesRecordName(Ext.getCmp('issuesRecord_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'issuesRecord_go_button',
					handler: function(){
						SearchByIssuesRecordName(Ext.getCmp('issuesRecord_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchIssuesRecord();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_issuesRecords,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-issuesRecord').enable();
		p.getTopToolbar().findById('delete-issuesRecord').enable();
		p.getTopToolbar().findById('view-issuesRecord').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-issuesRecord').disable();
			p.getTopToolbar().findById('view-issuesRecord').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-issuesRecord').disable();
			p.getTopToolbar().findById('view-issuesRecord').disable();
			p.getTopToolbar().findById('delete-issuesRecord').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-issuesRecord').enable();
			p.getTopToolbar().findById('view-issuesRecord').enable();
			p.getTopToolbar().findById('delete-issuesRecord').enable();
		}
		else{
			p.getTopToolbar().findById('edit-issuesRecord').disable();
			p.getTopToolbar().findById('view-issuesRecord').disable();
			p.getTopToolbar().findById('delete-issuesRecord').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_issuesRecords.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
