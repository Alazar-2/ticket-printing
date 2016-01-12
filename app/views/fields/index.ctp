
var store_fields = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','name','label','length','type','data','ticket'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'name', direction: "ASC"},
	groupField: 'label'
});


function AddField() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var field_data = response.responseText;
			
			eval(field_data);
			
			FieldAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the field add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditField(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var field_data = response.responseText;
			
			eval(field_data);
			
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
function ViewParentCoordinates(id) {
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

function ViewParentRecords(id) {
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


function DeleteField(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Field successfully deleted!'); ?>');
			RefreshFieldData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the field add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchField(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'fields', 'action' => 'search')); ?>',
		success: function(response, opts){
			var field_data = response.responseText;

			eval(field_data);

			fieldSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the field search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchByFieldName(value){
	var conditions = '\'Field.name LIKE\' => \'%' + value + '%\'';
	store_fields.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshFieldData() {
	store_fields.reload();
}


if(center_panel.find('id', 'field-tab') != "") {
	var p = center_panel.findById('field-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Fields'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'field-tab',
		xtype: 'grid',
		store: store_fields,
		columns: [
			{header: "<?php __('Name'); ?>", dataIndex: 'name', sortable: true},
			{header: "<?php __('Label'); ?>", dataIndex: 'label', sortable: true},
			{header: "<?php __('Length'); ?>", dataIndex: 'length', sortable: true},
			{header: "<?php __('Type'); ?>", dataIndex: 'type', sortable: true},
			{header: "<?php __('Data'); ?>", dataIndex: 'data', sortable: true},
			{header: "<?php __('Ticket'); ?>", dataIndex: 'ticket', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Fields" : "Field"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewField(Ext.getCmp('field-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Fields</b><br />Click here to create a new Field'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddField();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-field',
					tooltip:'<?php __('<b>Edit Fields</b><br />Click here to modify the selected Field'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditField(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-field',
					tooltip:'<?php __('<b>Delete Fields(s)</b><br />Click here to remove the selected Field(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Field'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteField(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Field'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Fields'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteField(sel_ids);
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
					text: '<?php __('View Field'); ?>',
					id: 'view-field',
					tooltip:'<?php __('<b>View Field</b><br />Click here to see details of the selected Field'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
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
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentCoordinates(sel.data.id);
								};
							}
						}
,{
							text: '<?php __('View Records'); ?>',
                            icon: 'img/table_view.png',
							cls: 'x-btn-text-icon',
							handler: function(btn) {
								var sm = p.getSelectionModel();
								var sel = sm.getSelected();
								if (sm.hasSelection()){
									ViewParentRecords(sel.data.id);
								};
							}
						}
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
							store_fields.reload({
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
					id: 'field_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchByFieldName(Ext.getCmp('field_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'field_go_button',
					handler: function(){
						SearchByFieldName(Ext.getCmp('field_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchField();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_fields,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-field').enable();
		p.getTopToolbar().findById('delete-field').enable();
		p.getTopToolbar().findById('view-field').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-field').disable();
			p.getTopToolbar().findById('view-field').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-field').disable();
			p.getTopToolbar().findById('view-field').disable();
			p.getTopToolbar().findById('delete-field').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-field').enable();
			p.getTopToolbar().findById('view-field').enable();
			p.getTopToolbar().findById('delete-field').enable();
		}
		else{
			p.getTopToolbar().findById('edit-field').disable();
			p.getTopToolbar().findById('view-field').disable();
			p.getTopToolbar().findById('delete-field').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_fields.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
