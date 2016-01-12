var store_parent_slips = new Ext.data.Store({
	reader: new Ext.data.JsonReader({
		root:'rows',
		totalProperty: 'results',
		fields: [
			'id','url','ticket','order'	
		]
	}),
	proxy: new Ext.data.HttpProxy({
		url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'list_data', $parent_id)); ?>'	})
});


function AddParentSlip() {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'add', $parent_id)); ?>',
		success: function(response, opts) {
			var parent_slip_data = response.responseText;
			
			eval(parent_slip_data);
			
			SlipAddWindow.show();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the slip add form. Error code'); ?>: ' + response.status);
		}
	});
}

function EditParentSlip(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'edit')); ?>/'+id+'/<?php echo $parent_id; ?>',
		success: function(response, opts) {
			var parent_slip_data = response.responseText;
			
			eval(parent_slip_data);
			
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

function ViewSlipCoordinates(id) {
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


function DeleteParentSlip(id) {
	Ext.Ajax.request({
		url: '<?php echo $this->Html->url(array('controller' => 'slips', 'action' => 'delete')); ?>/'+id,
		success: function(response, opts) {
			Ext.Msg.alert('<?php __('Success'); ?>', '<?php __('Slip(s) successfully deleted!'); ?>');
			RefreshParentSlipData();
		},
		failure: function(response, opts) {
			Ext.Msg.alert('<?php __('Error'); ?>', '<?php __('Cannot get the slip to be deleted. Error code'); ?>: ' + response.status);
		}
	});
}

function SearchByParentSlipName(value){
	var conditions = '\'Slip.name LIKE\' => \'%' + value + '%\'';
	store_parent_slips.reload({
		 params: {
			start: 0,
			limit: list_size,
			conditions: conditions
	    }
	});
}

function RefreshParentSlipData() {
	store_parent_slips.reload();
}



var g = new Ext.grid.GridPanel({
	title: '<?php __('Slips'); ?>',
	store: store_parent_slips,
	loadMask: true,
	stripeRows: true,
	height: 300,
	anchor: '100%',
    id: 'slipGrid',
	columns: [
		{header: "<?php __('Url'); ?>", dataIndex: 'url', sortable: true},
		{header:"<?php __('ticket'); ?>", dataIndex: 'ticket', sortable: true},
		{header: "<?php __('Order'); ?>", dataIndex: 'order', sortable: true}	],
	sm: new Ext.grid.RowSelectionModel({
		singleSelect: false
	}),
	viewConfig: {
		forceFit: true
	},
    listeners: {
        celldblclick: function(){
            ViewSlip(Ext.getCmp('slipGrid').getSelectionModel().getSelected().data.id);
        }
    },
	tbar: new Ext.Toolbar({
		items: [{
				xtype: 'tbbutton',
				text: '<?php __('Upload'); ?>',
				tooltip:'<?php __('<b>Upload Slip</b><br />Click here to create a new Slip'); ?>',
				icon: 'img/table_add.png',
				cls: 'x-btn-text-icon',
				handler: function(btn) {
					AddParentSlip();
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Edit'); ?>',
				id: 'edit-parent-slip',
				tooltip:'<?php __('<b>Edit Slip</b><br />Click here to modify the selected Slip'); ?>',
				icon: 'img/table_edit.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelected();
					if (sm.hasSelection()){
						EditParentSlip(sel.data.id);
					};
				}
			}, ' ', '-', ' ', {
				xtype: 'tbbutton',
				text: '<?php __('Delete'); ?>',
				id: 'delete-parent-slip',
				tooltip:'<?php __('<b>Delete Slip(s)</b><br />Click here to remove the selected Slip(s)'); ?>',
				icon: 'img/table_delete.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
					var sel = sm.getSelections();
					if (sm.hasSelection()){
						if(sel.length==1){
							Ext.Msg.show({
									title: '<?php __('Remove Slip'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove'); ?> '+sel[0].data.name+'?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													DeleteParentSlip(sel[0].data.id);
											}
									}
							});
						} else {
							Ext.Msg.show({
									title: '<?php __('Remove Slip'); ?>',
									buttons: Ext.MessageBox.YESNOCANCEL,
									msg: '<?php __('Remove the selected Slip'); ?>?',
									icon: Ext.MessageBox.QUESTION,
                                    fn: function(btn){
											if (btn == 'yes'){
													var sel_ids = '';
													for(i=0;i<sel.length;i++){
														if(i>0)
															sel_ids += '_';
														sel_ids += sel[i].data.id;
													}
													DeleteParentSlip(sel_ids);
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
				text: '<?php __('View Slip'); ?>',
				id: 'view-slip2',
				tooltip:'<?php __('<b>View Slip</b><br />Click here to see details of the selected Slip'); ?>',
				icon: 'img/table_view.png',
				cls: 'x-btn-text-icon',
				disabled: true,
				handler: function(btn) {
					var sm = g.getSelectionModel();
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
							var sm = g.getSelectionModel();
							var sel = sm.getSelected();
							if (sm.hasSelection()){
								ViewSlipCoordinates(sel.data.id);
							};
						}
					}
					]
				}

            }, ' ', '->', {
				xtype: 'textfield',
				emptyText: '<?php __('[Search By Name]'); ?>',
				id: 'parent_slip_search_field',
				listeners: {
					specialkey: function(field, e){
						if (e.getKey() == e.ENTER) {
							SearchByParentSlipName(Ext.getCmp('parent_slip_search_field').getValue());
						}
					}

				}
			}, {
				xtype: 'tbbutton',
				icon: 'img/search.png',
				cls: 'x-btn-text-icon',
				text: 'GO',
				tooltip:'<?php __('<b>GO</b><br />Click here to get search results'); ?>',
				id: 'parent_slip_go_button',
				handler: function(){
					SearchByParentSlipName(Ext.getCmp('parent_slip_search_field').getValue());
				}
			}, ' '
	]}),
	bbar: new Ext.PagingToolbar({
		pageSize: list_size,
		store: store_parent_slips,
		displayInfo: true,
		displayMsg: '<?php __('Displaying {0} - {1} of {2}'); ?>',
		beforePageText: '<?php __('Page'); ?>',
		afterPageText: '<?php __('of {0}'); ?>',
		emptyMsg: '<?php __('No data to display'); ?>'
	})
});
g.getSelectionModel().on('rowselect', function(sm, rowIdx, r) {
	g.getTopToolbar().findById('edit-parent-slip').enable();
	g.getTopToolbar().findById('delete-parent-slip').enable();
        g.getTopToolbar().findById('view-slip2').enable();
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-slip').disable();
                g.getTopToolbar().findById('view-slip2').disable();
	}
});
g.getSelectionModel().on('rowdeselect', function(sm, rowIdx, r) {
	if(this.getSelections().length > 1){
		g.getTopToolbar().findById('edit-parent-slip').disable();
		g.getTopToolbar().findById('delete-parent-slip').enable();
                g.getTopToolbar().findById('view-slip2').disable();
	}
	else if(this.getSelections().length == 1){
		g.getTopToolbar().findById('edit-parent-slip').enable();
		g.getTopToolbar().findById('delete-parent-slip').enable();
                g.getTopToolbar().findById('view-slip2').enable();
	}
	else{
		g.getTopToolbar().findById('edit-parent-slip').disable();
		g.getTopToolbar().findById('delete-parent-slip').disable();
                g.getTopToolbar().findById('view-slip2').disable();
	}
});



var parentSlipsViewWindow = new Ext.Window({
	title: 'Slip Under the selected Item',
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
			parentSlipsViewWindow.close();
		}
	}]
});

store_parent_slips.load({
    params: {
        start: 0,    
        limit: list_size
    }
});