
var store_slips = new Ext.data.GroupingStore({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','url','ticket','order'		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'list_data')); ?>'
	})
,	sortInfo:{field: 'url', direction: "ASC"},
	groupField: 'ticket_id'
});


function AddSlip() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'add')); ?>',
		success: function(response, opts) {
			var slip_data = response.responseText;
			
			eval(slip_data);
			
			SlipAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the slip add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditSlip(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'edit')); ?>/'+id,
		success: function(response, opts) {
			var slip_data = response.responseText;
			
			eval(slip_data);
			
			SlipEditWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the slip edit form. Error code'); ?>: ' + response.status);
		}
	});
}

function ViewSlip(id) {
    Ext.Ajax.request({
        url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'view')); ?>/'+id,
        success: function(response, opts) {
            var slip_data = response.responseText;

            eval(slip_data);

            SlipViewWindow.show();
        },
        failure: function(response, opts) {
            Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the slip view form. Error code'); ?>: ' + response.status);
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


function DeleteSlip(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Slip successfully deleted!'); ?>');
			RefreshSlipData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the slip add form. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchSlip(){
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'search')); ?>',
		success: function(response, opts){
			var slip_data = response.responseText;

			eval(slip_data);

			slipSearchWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>','<?php __('Cannot get the slip search form. Error Code'); ?>: ' + response.status);
		}
	});
}

function SearchBySlipName(value){
	var conditions = '\'Slip.name LIKE\' => \'%' + value + '%\'';
	store_slips.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshSlipData() {
	store_slips.reload();
}


if(center_panel.find('id', 'slip-tab') != "") {
	var p = center_panel.findById('slip-tab');
	center_panel.setActiveTab(p);
} else {
	var p = center_panel.add({
		title: '<?php __('Slips'); ?>',
		closable: true,
		loadMask: true,
		stripeRows: true,
		id: 'slip-tab',
		xtype: 'grid',
		store: store_slips,
		columns: [
			{header: "<?php __('Url'); ?>", dataIndex: 'url', sortable: true},
			{header: "<?php __('Ticket'); ?>", dataIndex: 'ticket', sortable: true},
			{header: "<?php __('Order'); ?>", dataIndex: 'order', sortable: true}
		],
		
		view: new Ext.grid.GroupingView({
            forceFit:true,
            groupTextTpl: '{text} ({[values.rs.length]} {[values.rs.length > 1 ? "Slips" : "Slip"]})'
        })
,
		listeners: {
			celldblclick: function(){
				ViewSlip(Ext.getCmp('slip-tab').getSelectionModel().getSelected().data.id);
			}
		},
		sm: new Ext.grid.RowSelectionModel({
			singleSelect: false
		}),
		tbar: new Ext.Toolbar({
			
			items: [{
					xtype: 'tbbutton',
					text: '<?php __('Add'); ?>',
					tooltip:'<?php __('<b>Add Slips</b><br />Click here to create a new Slip'); ?>',
					icon: 'img/table_add.png',
					cls: 'x-btn-text-icon',
					handler: function(btn) {
						AddSlip();
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Edit'); ?>',
					id: 'edit-slip',
					tooltip:'<?php __('<b>Edit Slips</b><br />Click here to modify the selected Slip'); ?>',
					icon: 'img/table_edit.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							EditSlip(sel.data.id);
						};
					}
				}, ' ', '-', ' ', {
					xtype: 'tbbutton',
					text: '<?php __('Delete'); ?>',
					id: 'delete-slip',
					tooltip:'<?php __('<b>Delete Slips(s)</b><br />Click here to remove the selected Slip(s)'); ?>',
					icon: 'img/table_delete.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelections();
						if (sm.hasSelection()){
							if(sel.length==1){
								Ext.Msg.show({
									title: '<?php __('Remove Slip'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											DeleteSlip(sel[0].data.id);
										}
									}
								});
							}else{
								Ext.Msg.show({
									title: '<?php __('Remove Slip'); ?>',
									buttons: Ext.MessageBox.YESNO,
									msg: '<?php __('Remove the selected Slips'); ?>?',
									icon: Ext.MessageBox.QUESTION,
									fn: function(btn){
										if (btn == 'yes'){
											var sel_ids = '';
											for(i=0;i<sel.length;i++){
												if(i>0)
													sel_ids += '_';
												sel_ids += sel[i].data.id;
											}
											DeleteSlip(sel_ids);
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
					text: '<?php __('View Slip'); ?>',
					id: 'view-slip',
					tooltip:'<?php __('<b>View Slip</b><br />Click here to see details of the selected Slip'); ?>',
					icon: 'img/table_view.png',
					cls: 'x-btn-text-icon',
					disabled: true,
					handler: function(btn) {
						var sm = p.getSelectionModel();
						var sel = sm.getSelected();
						if (sm.hasSelection()){
							ViewSlip(sel.data.id);
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
							store_slips.reload({
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
					id: 'slip_search_field',
					listeners: {
						specialkey: function(field, e){
							if (e.getKey() == e.ENTER) {
								SearchBySlipName(Ext.getCmp('slip_search_field').getValue());
							}
						}
					}
				}, {
					xtype: 'tbbutton',
					icon: 'img/search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('GO'); ?>',
                    tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
					id: 'slip_go_button',
					handler: function(){
						SearchBySlipName(Ext.getCmp('slip_search_field').getValue());
					}
				}, '-', {
					xtype: 'tbbutton',
					icon: 'img/table_search.png',
					cls: 'x-btn-text-icon',
					text: '<?php __('Advanced Search'); ?>',
                    tooltip:'<?php __('<b>Advanced Search...</b><br />Click here to get the advanced search form'); ?>',
					handler: function(){
						SearchSlip();
					}
				}
		]}),
		bbar: new Ext.PagingToolbar({
			pageSize: list_size,
			store: store_slips,
			displayInfo: true,
			displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
			beforePageText: '<?php __('Page'); ?>',
			afterPageText: '<?php __('of {0}'); ?>',
			emptyMsg: '<?php __('No data to display'); ?>'
		})
	});
	p.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
		p.getTopToolbar().findById('edit-slip').enable();
		p.getTopToolbar().findById('delete-slip').enable();
		p.getTopToolbar().findById('view-slip').enable();
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-slip').disable();
			p.getTopToolbar().findById('view-slip').disable();
		}
	});
	p.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
		if(this.getSelections().length > 1){
			p.getTopToolbar().findById('edit-slip').disable();
			p.getTopToolbar().findById('view-slip').disable();
			p.getTopToolbar().findById('delete-slip').enable();
		}
		else if(this.getSelections().length == 1){
			p.getTopToolbar().findById('edit-slip').enable();
			p.getTopToolbar().findById('view-slip').enable();
			p.getTopToolbar().findById('delete-slip').enable();
		}
		else{
			p.getTopToolbar().findById('edit-slip').disable();
			p.getTopToolbar().findById('view-slip').disable();
			p.getTopToolbar().findById('delete-slip').disable();
		}
	});
	center_panel.setActiveTab(p);
	
	store_slips.load({
		params: {
			start: 0,          
			limit: list_size
		}
	});
	
}
