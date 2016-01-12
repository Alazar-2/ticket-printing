
var store_records = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','issue','field','value'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'issue_id', direction: "ASC"},
	groupField: 'field_id'
});


function AddRecord() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var record_data = response.responseText;
			
			eval(record_data);
			
			RecordAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the record add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var record_data = response.responseText;
			
			eval(record_data);
			
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

function DeleteRecord(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Record successfully deleted!'); ?>');
			RefreshRecordData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the record add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchRecord(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'records', 'action' => 'search')); ?>',
		success: function(response, opts){
			var record_data = response.responseText;

			eval(record_data);

			recordSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the record search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByRecordName(value){
	var conditions = '\'Record.name LIKE\' => \'%' + value + '%\'';
	store_records.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshRecordData() {
	store_records.reload();
}


if(center_panel.find('id', 'record-tab') != "") {
	var p = center_panel.findById('record-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Records'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'record-tab',
		xtype: 'grid',
		store: store_records,
		columns: [
			{header: "<?php __('Issue'); ?>", dataIndex: 'issue', sortable: true},
			{header: "<?php __('Field'); ?>", dataIndex: 'field', sortable: true},
			{header: "<?php __('Value'); ?>", dataIndex: 'value', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Records" : "Record"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewRecord(Ext.getCmp('record-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Records</b><br />Click here to create a new Record'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddRecord();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-record',
					tooltip:'<?php __('<b>Edit Records</b><br />Click here to modify the selected Record'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditRecord(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-record',
					tooltip:'<?php __('<b>Delete Records(s)</b><br />Click here to remove the selected Record(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Record'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteRecord(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Record'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Records'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteRecord(sel_ids);
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
					text: '<?php __('View Record'); ?>',
					id: 'view-record',
					tooltip:'<?php __('<b>View Record</b><br />Click here to see details of the selected Record'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewRecord(sel.data.id);
						};
					},
					menu : {
						items: [
						]
					}
				}, ' ', '-',  '<?php __('Issue'); ?>: ', {
					xtype : 'combo',
					emptyText: 'All',
					store : new Ext.data.ArrayStore({
						fields : ['id', 'name'],
						data : [
							['-1', 'All'],
							<?php $st = false;foreach ($issues as $item){if($st) echo ",
							";?>['<?php echo $item['Issue']['id']; ?>' ,'<?php echo $item['Issue']['name']; ?>']<?php $st = true;}?>						]
					}),
					displayField : 'name',
					valueField : 'id',
					mode : 'local',
					value : '-1',
					disableKeyFilter : true,
					triggerAction: 'all',
					listeners : {
						select : function(combo, record, index){
							store_records.reload({
								params: {
									start: 0,
									limit: list_size,
									issue_id : combo.getValue()
								}
							});
						}
					}
				},
 '->', {
					xtype: 'textfield',
					emptyText: '<?php __('[Search By Name]'); ?>',
					id: 'record_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByRecordName(Ext.getCmp('record_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'record_go_button',
					handler: function(){
						SearchByRecordName(Ext.getCmp('record_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchRecord();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_records,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-record').enable();
		p.getTopToolbar().findById('delete-record').enable();
		p.getTopToolbar().findById('view-record').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-record').disable();
			p.getTopToolbar().findById('view-record').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-record').disable();
			p.getTopToolbar().findById('view-record').disable();
			p.getTopToolbar().findById('delete-record').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-record').enable();
			p.getTopToolbar().findById('view-record').enable();
			p.getTopToolbar().findById('delete-record').enable();
		}
		else{
			p.getTopToolbar().findById('edit-record').disable();
			p.getTopToolbar().findById('view-record').disable();
			p.getTopToolbar().findById('delete-record').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_records.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
